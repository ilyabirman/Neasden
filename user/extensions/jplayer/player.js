$ (function () {
// вот это всё мегалевак, эти переменные должны быть раздельные для каждого плеера, присутствующего на странице:  
  var desiredpos = -1
  var currentpos = -1
  var desiredmorethancurrent = 1
  var totaltime = 0
  var loadpercent = 0
  
  var fmttime = function (milliseconds) {
    var ptime, sec, min, heu
    ptime = Math.round (milliseconds / 1000)
    sec = ptime % 60
    min = ((ptime - sec) % 3600) / 60
    heu = (ptime - sec - (min * 60)) / 3600
    if (sec < 10) sec = '0' + sec
    if (heu && (min < 10)) min = '0' + min
    return (heu? (heu + ':') : '') + min + ':' + sec
  }
  
  var forceLimit = function (value, atleast, nomore) {
    return (Math.min (Math.max (value, atleast), nomore))
  }
  
  var updateLoadBar = function (playerSelector, seekPercent) {
        
    var maxWidth = $ (playerSelector).find ('.jplayer-progress-area').width ()
    var pixels = Math.floor (Math.min (100, seekPercent) / 100 * maxWidth)

    $ (playerSelector).find ('.jplayer-load-bar').css ('width', pixels + 'px')
    $ (playerSelector).find ('.jplayer-load-bar-right').css ('left', pixels + 'px')
    
  }
  
  jposition = function (cssSelectorAncestor, playpx, playedTime) {
    if (playpx > 0) {
      $ (cssSelectorAncestor + ' .jplayer-play-bar-left').show () 
    } else {
      $ (cssSelectorAncestor + ' .jplayer-play-bar-left').hide () 
    }
    $ (cssSelectorAncestor + ' .jplayer-play-lift').css ('left', playpx + 'px')
    $ (cssSelectorAncestor + ' .jplayer-play-bar').css ('width', playpx + 'px')
    
    playtimetext = ''
    if (playedTime >= 0) {
      playtimetext = fmttime (playedTime * 1000)
    }

    totaltimetext = ''
    if (totaltime > 0) {
      if ($ (cssSelectorAncestor).data ('isExactTotalTime')) {
        totaltimetext = fmttime (totaltime * 1000)
      } else {
        var min = totaltime / 60
        var d = (Math.log (min) / Math.log (10))
        min = Math.round (Math.pow (10, Math.round (d*10)/10))
        totaltimetext = '~ ' + fmttime (min * 60 * 1000)
      }
    }

    $ (cssSelectorAncestor + ' .jplayer-play-time').text (playtimetext)
    $ (cssSelectorAncestor + ' .jplayer-total-time').text (totaltimetext)


  }

/*
  $ ('.jplayer .jplayer-audio-source').addClass ('jplayer-audio-source-shifted')
  $ ('.jplayer .jplayer-ui').show ()
  $ ('.jplayer .jplayer-load-bar').css ('width', '200px')
  $ ('.jplayer .jplayer-load-bar-right').css ('left', '200px')
  jposition ('',50, 50);
  return;
*/
  
  var willSeekTo = function (playerSelector, playerObject, pixels) {

    var maxWidth = $ (playerSelector).find ('.jplayer-progress-area').width ()
    var loadWidth = $ (playerSelector).find ('.jplayer-load-bar').width ()
    
    pixels = forceLimit (pixels, 0, loadWidth)
    playhead = pixels/maxWidth
    playheadSeekable = pixels/loadWidth
    if (loadWidth == 0) playhead = 0
    
    jposition (playerSelector, pixels, totaltime*playhead)
    desiredpos = Math.floor (pixels)
    desiredmorethancurrent = (desiredpos >= currentpos)
    $ (playerSelector).find ('.jplayer-buffering').fadeTo (1, 1)
    $ (playerObject).jPlayer ('play')
    $ (playerObject).jPlayer ('playHead', playheadSeekable*100)
    
    return false
    
  }
  
  $ (".jplayer").each (function () {

    var thisSelector = '#' + this.id

    $ (thisSelector + ' .jplayer-invisible-object').jPlayer ({
      
      swfPath: $ (thisSelector + ' .jplayer-swf-source').attr ('href'),
      preload: 'metadata',
      volume: 100,
      
      cssSelectorAncestor: thisSelector,
      cssSelector: {
        play: '.jplayer-play',
        pause: '.jplayer-pause',
      },
      
      solution: 'html,flash',
      supplied: 'mp3',
      
      errorAlerts: false,
      
      ready: function (event) {
        
        var me = this
        var isMouseDown = false
        
        $ ('.jplayer .jplayer-hidden').show ()
        
        $ (this).jPlayer ("setMedia", {
          mp3: $ (thisSelector + ' .jplayer-audio-source').attr ('href'),
        })
        
        $ (thisSelector).find ('.jplayer-mine').mousedown (function (e) {
          isMouseDown = true;
          e.stopPropagation ()
          e.preventDefault ()
          return willSeekTo (thisSelector, me, e.pageX - $ (thisSelector + ' .jplayer-mine').offset ().left)
        })
        
        $ (document.body).mouseup (function () { isMouseDown = false })
        
        $ (document.body).mousemove (function (e) {
          if (isMouseDown) {
            e.stopPropagation ()
            e.preventDefault ()
            return willSeekTo (thisSelector, me, e.pageX - $ (thisSelector + ' .jplayer-mine').offset ().left)
          }
        })
        
      },
      
      play: function (event) { $ (this).data ('isDirty', 1) },
      
      progress: function (event) { updateLoadBar (thisSelector, event.jPlayer.status.seekPercent) },
      
      timeupdate: function (event) {
  
        updateLoadBar (thisSelector, event.jPlayer.status.seekPercent)
        
        loadPercent = event.jPlayer.status.seekPercent
        if (loadPercent > 100) loadPercent = 100
        $ (thisSelector).data ('isExactTotalTime', $ (this).isExactTotalTime || (event.jPlayer.status.seekPercent == 100))
        totaltime = event.jPlayer.status.duration / (loadPercent / 100)
        if (isNaN (totaltime)) totaltime = 0
             
        maxWidth = $ (thisSelector + ' .jplayer-progress-area').width ()
    
        playpx = Math.floor (event.jPlayer.status.currentTime/totaltime*(maxWidth))
         
        $ (thisSelector + ' .jplayer-name').html (
          'now = ' + event.jPlayer.status.currentTime + '<br />' +
          'desired = ' + desiredpos + '<br />' +
          'play = ' + playpx + '<br />' +
          'dmore = '  + desiredmorethancurrent
        )
                
        if (
          (desiredpos < 0) ||
          ((desiredmorethancurrent) && (playpx >= desiredpos)) ||
          ((!desiredmorethancurrent) && (playpx < currentpos))
        ) {
          var curtime = -1
          if ($ (this).data ('isDirty')) curtime = event.jPlayer.status.currentTime
          jposition (thisSelector, playpx, curtime)
          $ (thisSelector + ' .jplayer-buffering').fadeTo (1000, 0)
          desiredpos = -1
        }
        
        currentpos = playpx
        if (isNaN (currentpos)) currentpos = -1
        
      }
    })

  })

  
})