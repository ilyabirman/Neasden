$ (function () {

  var formatTime = function (seconds) {
    
    var sec = Math.round (seconds) % 60
    var min = ((Math.round (seconds) - sec) % 3600) / 60
    var heu = (Math.round (seconds) - sec - (min * 60)) / 3600
    
    if (sec < 10) sec = '0' + sec
    if (heu && (min < 10)) min = '0' + min
    
    return (heu? (heu + ':') : '') + min + ':' + sec
    
  }
  
  var updateTimeDisplay = function (playerSelector, playedTime) {
    
    var playtimetext = '', totaltimetext = ''
    
    if (playedTime >= 0) {
      playtimetext = formatTime (playedTime)
    }

    if ($ (playerSelector).data ('totalTime') > 0) {
      if ($ (playerSelector).data ('isExactTotalTime')) {
        totaltimetext = formatTime ($ (playerSelector).data ('totalTime'))
      } else {
        var min = $ (playerSelector).data ('totalTime') / 60
        var d = (Math.log (min) / Math.log (10))
        min = Math.round (Math.pow (10, Math.round (d*10)/10))
        totaltimetext = '~ ' + formatTime (min * 60)
      }
    }

    $ (playerSelector).find ('.jplayer-play-time').text (playtimetext)
    $ (playerSelector).find ('.jplayer-total-time').text (totaltimetext)
    
  }
  
  var updateLoadBar = function (playerSelector, seekPercent) {
        
    var maxWidth = $ (playerSelector).find ('.jplayer-progress-area').width ()
    var pixels = Math.floor (Math.min (100, seekPercent) / 100 * maxWidth)

    $ (playerSelector).find ('.jplayer-load-bar').css ('width', pixels + 'px')
    $ (playerSelector).find ('.jplayer-load-bar-right').css ('left', pixels + 'px')
    
  }
  

  var updatePlayBar = function (playerSelector, pixels) {
    
    if (pixels > 0) {
      $ (playerSelector).find ('.jplayer-play-bar-left').show () 
    } else {
      $ (playerSelector).find ('.jplayer-play-bar-left').hide () 
    }
    $ (playerSelector).find ('.jplayer-play-lift').css ('left', pixels + 'px')
    $ (playerSelector).find ('.jplayer-play-bar').css ('width', pixels + 'px')

  }

  /*
  $ ('.jplayer .jplayer-audio-source').addClass ('jplayer-audio-source-shifted')
  $ ('.jplayer .jplayer-ui').show ()
  $ ('.jplayer .jplayer-load-bar').css ('width', '200px')
  $ ('.jplayer .jplayer-load-bar-right').css ('left', '200px')
  updatePlayBar ('',50);
  updateTimeDisplay ('', 50)
  return;
  */
  
  var willSeekTo = function (playerSelector, playerObject, tryPixels) {

    var maxWidth = $ (playerSelector).find ('.jplayer-progress-area').width ()
    var loadWidth = $ (playerSelector).find ('.jplayer-load-bar').width ()
    var pixels = Math.min (Math.max (tryPixels, 0), loadWidth)
    var playhead = pixels/maxWidth
    var playheadSeekable = pixels/loadWidth
    
    if ((maxWidth == 0) || loadWidth == 0) playheadSeekable = playhead = 0
    
    $ (playerSelector).find ('.jplayer-buffering').stop ().fadeTo (1, 1)

    updatePlayBar (playerSelector, pixels)
    updateTimeDisplay (playerSelector, $ (playerSelector).data ('totalTime') * playhead)
    
    $ (playerObject).data ('wantSeekToTime', $ (playerSelector).data ('totalTime') * playhead)
    
    $ (playerObject).jPlayer ('play')
    $ (playerObject).jPlayer ('playHead', playheadSeekable*100)
    
    return false
    
  }
  
  $ (".jplayer").each (function () {
    var thisSelector = '#' + this.id

    $ (thisSelector).append (
      $ ('<div class="jplayer-invisible-object"></div>'),
      $ ('<div class="jplayer-progress-area"></div>')
    )
    
    // progress area
    $ (thisSelector).find ('.jplayer-progress-area').append (
      $ ('<div class="jplayer-mine-left"></div>'),
      $ ('<div class="jplayer-mine-right"></div>'),
      $ ('<div class="jplayer-mine"></div>')
    )
    $ (thisSelector).find ('.jplayer-mine').append (
      $ ('<div class="jplayer-load-bar-left jplayer-hidden" style="display: none"></div>'),
      $ ('<div class="jplayer-load-bar-right jplayer-hidden" style="display: none"></div>'),
      $ ('<div class="jplayer-load-bar jplayer-hidden" style="display: none"></div>'),
      $ ('<div class="jplayer-play-bar-left" style="display: none"></div>'),
      $ ('<div class="jplayer-play-bar"></div>'),
      $ ('<div class="jplayer-play-lift jplayer-hidden" style="display: none">')
    )
    $ (thisSelector).find ('.jplayer-play-lift').append (
      $ ('<div class="jplayer-buffering" style="display: none"></div>')
    )
   
    // info area
    $ (thisSelector).find ('.jplayer-play-control').append (
      $ ('<div class="jplayer-unavailable jplayer-to-hide"></div>'),
      $ ('<div class="jplayer-play-pause jplayer-hidden" style="display: none"></div>')
    )
    $ (thisSelector).find ('.jplayer-play-pause').append (
      $ ('<div class="jplayer-play"></div>'),
      $ ('<div class="jplayer-pause" style="display: none"></div>')
    )
    
    
    $ (thisSelector).find ('.jplayer-invisible-object').jPlayer ({
      
      swfPath: $ (thisSelector).find ('.jplayer-swf-source').attr ('href'),
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
          mp3: $ (thisSelector).find ('.jplayer-audio-source').attr ('href'),
        })
        
        $ (thisSelector).find ('.jplayer-mine').mousedown (function (e) {
          isMouseDown = true;
          e.stopPropagation ()
          e.preventDefault ()
          return willSeekTo (thisSelector, me, e.pageX - $ (thisSelector).find ('.jplayer-mine').offset ().left)
        })
        
        $ (document.body).mouseup (function () { isMouseDown = false })
        
        $ (document.body).mousemove (function (e) {
          if (isMouseDown) {
            e.stopPropagation ()
            e.preventDefault ()
            return willSeekTo (thisSelector, me, e.pageX - $ (thisSelector).find ('.jplayer-mine').offset ().left)
          }
        })
        
      },
      
      play: function (event) { $ (this).data ('isDirty', 1) },
      
      progress: function (event) { 
        
        updateLoadBar (thisSelector, event.jPlayer.status.seekPercent)
      
      },
      
      timeupdate: function (event) {
        
        updateLoadBar (thisSelector, event.jPlayer.status.seekPercent)
        
        var maxWidth = $ (thisSelector).find ('.jplayer-progress-area').width ()
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
          updatePlayBar (thisSelector, playpx)
          updateTimeDisplay (thisSelector, curtime)
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