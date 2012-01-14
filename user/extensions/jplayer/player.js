$ (function () {
// вот это всё мегалевак, эти переменные должны быть раздельные для каждого плеера, присутствующего на странице:  
  var desiredpos = -1
  var currentpos = -1
  var desiredmorethancurrent = 1
  var movetimeout
  var totaltime = 0
  var loadpercent = 0
  var vol = 2
  var loadpx = 0
  var lasttime = -1
  var exacttotaltime = 0
  var isplaying = 0
  
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
      if (exacttotaltime) {
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

    loadpx2 = ((loadpx > playpx) ? loadpx : playpx)
    $ (cssSelectorAncestor + ' .jplayer-load-bar').css ('width', loadpx2 + 'px')
    $ (cssSelectorAncestor + ' .jplayer-load-bar-right').css ('left', loadpx2 + 'px')

  }

/*
  $ ('.jplayer .jplayer-audio-source').addClass ('jplayer-audio-source-shifted')
  $ ('.jplayer .jplayer-ui').show ()
  $ ('.jplayer .jplayer-load-bar').css ('width', '200px')
  $ ('.jplayer .jplayer-load-bar-right').css ('left', '200px')
  jposition ('',50, 50);
  return;
*/
  
  jmoveto = function (cssSelectorAncestor, player, movepx) {
    maxWidth = $ (cssSelectorAncestor + ' .jplayer-progress-area').width ()
    loadWidth = $ (cssSelectorAncestor + ' .jplayer-load-bar').width ()
    limit = maxWidth
    if ($ (player).jPlayer ('solution', 'flash')) limit = loadWidth
    if (movepx < 0) movepx = 0
    if (movepx > limit) movepx = limit
    playhead = movepx/maxWidth
    playheadSeekable = movepx/loadWidth
    if (limit == 0) playhead = 0
    jposition (cssSelectorAncestor, movepx, totaltime*playhead)
    desiredpos = Math.ceil (movepx)
    desiredmorethancurrent = (desiredpos >= currentpos)
    //alert (currentpos)
    $ (cssSelectorAncestor + ' .jplayer-buffering').fadeTo (1, 1)
    if ($ (player).data (movetimeout)) clearTimeout (movetimeout)
    $ (player).data (
      movetimeout,
      setTimeout (function () {
        $ (player).jPlayer ('play')
        $ (player).jPlayer ('playHead', playheadSeekable*100)
      }, 100)
    )
    return false
  }
  
  $ (".jplayer").each (function () {

    var currentCssSelectorAncestor = '#' + this.id

    $ (currentCssSelectorAncestor + ' .jplayer-invisible-object').jPlayer ({
      swfPath: $ (currentCssSelectorAncestor + ' .jplayer-swf-source').attr ('href'),
      preload: 'metadata',
      volume: 100,
      cssSelectorAncestor: currentCssSelectorAncestor,
      cssSelector: {
        play: '.jplayer-play',
        pause: '.jplayer-pause',
      },
      solution: 'html,flash',
      supplied: 'mp3',
      //errorAlerts: true,
      //warningAlerts: true,
      ready: function (event) {
        $ ('.jplayer .jplayer-audio-source').addClass ('jplayer-audio-source-shifted')
        $ ('.jplayer .jplayer-hidden').show ()
        var me = this
        var mousedown = false
        $ (me).jPlayer ("setMedia", {
          mp3: $ (currentCssSelectorAncestor + ' .jplayer-audio-source').attr ('href'),
        })
        $ (currentCssSelectorAncestor + ' .jplayer-mine').mousedown (function (e) {
          mousedown = true;
          e.stopPropagation ()
          e.preventDefault ()
          return jmoveto (currentCssSelectorAncestor, me, e.pageX - $ (currentCssSelectorAncestor + ' .jplayer-mine').offset ().left)
        })
        $ (document.body).mouseup (function () { 
          mousedown = false
        })
        $ (document.body).mousemove (function (e) {
          if (mousedown) {
            e.stopPropagation ()
            e.preventDefault ()
            return jmoveto (currentCssSelectorAncestor, me, e.pageX - $ (currentCssSelectorAncestor + ' .jplayer-mine').offset ().left)
          }
        })
      },
      play: function (event) { isplaying = 1 },
      timeupdate: function (event) {
  
        loadPercent = event.jPlayer.status.seekPercent
        exacttotaltime = exacttotaltime || (event.jPlayer.status.seekPercent == 100)
        totaltime = event.jPlayer.status.duration / (loadPercent / 100)
        if (isNaN (totaltime)) totaltime = 0
             
        maxWidth = $ (currentCssSelectorAncestor + ' .jplayer-progress-area').width ()
    
        if (loadPercent > 100) loadPercent = 100
        loadpx = Math.round ((loadPercent)/100*maxWidth)
        playpx = Math.round (event.jPlayer.status.currentTime/totaltime*(maxWidth))
         
        $ (currentCssSelectorAncestor + ' .jplayer-name').html (
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
          if (isplaying) curtime = event.jPlayer.status.currentTime
          jposition (currentCssSelectorAncestor, playpx, curtime)
          $ (currentCssSelectorAncestor + ' .jplayer-buffering').fadeTo (1000, 0)
          desiredpos = -1
        }
        
        currentpos = playpx
        if (isNaN (currentpos)) currentpos = -1
        
      }
    })

  })

  
})