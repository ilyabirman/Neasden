$ (function () {

  var formatTime = function (seconds) {
    
    var sec = Math.round (seconds) % 60
    var min = ((Math.round (seconds) - sec) % 3600) / 60
    var heu = (Math.round (seconds) - sec - (min * 60)) / 3600
    
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
      playtimetext = formatTime (playedTime)
    }

    totaltimetext = ''
    if ($ (cssSelectorAncestor).data ('totalTime') > 0) {
      if ($ (cssSelectorAncestor).data ('isExactTotalTime')) {
        totaltimetext = formatTime ($ (cssSelectorAncestor).data ('totalTime'))
      } else {
        var min = $ (cssSelectorAncestor).data ('totalTime') / 60
        var d = (Math.log (min) / Math.log (10))
        min = Math.round (Math.pow (10, Math.round (d*10)/10))
        totaltimetext = '~ ' + formatTime (min * 60)
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
    
    $ (playerSelector).find ('.jplayer-buffering').stop ().fadeTo (1, 1)

    jposition (playerSelector, pixels, $ (playerSelector).data ('totalTime') * playhead)
    $ (playerObject).data ('wantSeekToTime', $ (playerSelector).data ('totalTime') * playhead)
    
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
        $ ('.jplayer .jplayer-to-hide').hide ()
        
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
      
      progress: function (event) { 
        
        updateLoadBar (thisSelector, event.jPlayer.status.seekPercent)
      
      },
      
      timeupdate: function (event) {
        
        updateLoadBar (thisSelector, event.jPlayer.status.seekPercent)
        
        var maxWidth = $ (thisSelector + ' .jplayer-progress-area').width ()
        var playpx = Math.floor (event.jPlayer.status.currentTime / $ (thisSelector).data ('totalTime') * (maxWidth))
        
        if (event.jPlayer.status.seekPercent >= 100) {
          $ (thisSelector).data ('isExactTotalTime', true)
          $ (thisSelector).data ('totalTime', event.jPlayer.status.duration)
        } else if (event.jPlayer.status.seekPercent > 0) {
          $ (thisSelector).data ('totalTime', event.jPlayer.status.duration / event.jPlayer.status.seekPercent * 100)
        } else {
          $ (thisSelector).data ('totalTime', 0)
        }
        
                
        if (
          (! ($ (this).data ('wantSeekToTime') >= 0)) ||
          (event.jPlayer.status.currentTime - $ (this).data ('wantSeekToTime')) >= .33
        ) {
          var curtime = -1
          if ($ (this).data ('isDirty')) curtime = event.jPlayer.status.currentTime
          $ (thisSelector).find ('.jplayer-buffering').stop ().fadeTo (333, 0)
          jposition (thisSelector, playpx, curtime)
          $ (this).data ('wantSeekToTime', -1)
        }
        
        /*
        $ (thisSelector).find ('.jplayer-name').html (
          'now = ' + event.jPlayer.status.currentTime + '<br />' +
          'wantSeekToTime = ' + $ (this).data ('wantSeekToTime') + '<br />' +
          'wantSeekForward = '  + $ (this).data ('wantSeekForward') + '<br />' +
          'playpx = ' + playpx + '<br />' +
          ''
        )
        //*/
      
      }
    })

  })

  
})