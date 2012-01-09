<?

n__define_line_class (
  'jplayer',
  '(?:\[play\])(.*)'
);

n__define_group ('jplayer', '(-jplayer-)(-p-)*');

function n__render_group_jplayer ($group) {
  global $_neasden_config;

  $myconf = $_neasden_config['extensions']['jplayer'];
  
  $downloadstr = 'Download';
  if ($_neasden_config['language'] == 'ru') $downloadstr = 'Скачать';

  $p = false;

  n__require_link ($_neasden_config['__overload'] .'extensions/jplayer/player.js');
  n__require_link ($_neasden_config['__overload'] .'extensions/jplayer/jquery.jplayer.min.js');
  n__require_link ($_neasden_config['__overload'] .'extensions/jplayer/player.css');

  $result = (
    '<div class="'. $myconf['css-class'] .'"'.
    '>'."\n"
  );
  
  foreach ($group as $line) {
    list ($filebasename, $alt) = explode (' ', $line['content'], 2);
    
    if ($line['class'] == 'jplayer') {
      /*
      $result .= $line['class-data'][1];

      n__resource_detected ($filebasename);
      
      $filename = $myconf['folder'] . $filebasename;
      $size = getimagesize ($filename);
      list ($width, $height) = $size;

      if ($width > $myconf['max-width']) {
        $height = $height * ($myconf['max-width'] / $width);
        $width = $myconf['max-width'];
      }
      */

      $zoneid = rand (1000, 9999);

      $player_html = '

        <blockquote>
        <div class="jplayer" id="jplayer-ui-zone-'. $zoneid .'">
          
          <div class="jplayer-invisible-object"></div>
          <div class="jplayer-ui" style="display: none">
            
            <div class="jplayer-edge jplayer-left"></div>
            <div class="jplayer-edge jplayer-right"></div>
  
            <div class="jplayer-edge jplayer-right jplayer-right-pending"></div>
            <div class="jplayer-edge jplayer-left jplayer-play-left-played" style="display: none"></div>
            
            <div class="jplayer-play-mine">
              <div class="jplayer-dynamic-bar jplayer-load-bar"></div>
              <div class="jplayer-dynamic-bar jplayer-play-bar"></div>
  
              <div class="jplayer-load-bar-end"></div>
              <div class="jplayer-play-lift">
                <div class="jplayer-buffering" style="display: none"></div>
              </div>
            </div>
            
            <div class="jplayer-control jplayer-play"></div>
            <div class="jplayer-control jplayer-pause" style="display: none"></div>
            
            <div class="jplayer-time-info">
              <div class="jplayer-play-time">0:00</div>
              <div class="jplayer-total-time"></div>
            </div>

          </div>

          <a class="jplayer-audio-source" href="'. $line['class-data'][1] .'" title="'. $downloadstr .'"></a>
          <a class="jplayer-swf-source" href="user/neasden/extensions/jplayer/Jplayer.swf"></a>
          
          
        </div>
        <div id="jplayer_inspector"></div>
        </blockquote>
      
      '."\n";
      
      $player_html = n__save_tag ($player_html);

      $result .= $player_html;
      
    } else {
      if (!$p) {
        $p = true;
        $result .= '<p>' . $filebasename;
      } else {
        $result .= '<br />' . "\n" . $filebasename;
      }
    }
  }

  if ($p) $result .= '</p>'."\n";

  $result .= '</div>'."\n";

  return $result;
  
}



?>le="repeat">repeat</a></li>
                  <li><a href="javascript:;" class="jp-repeat-off" tabindex="1" title="repeat off" style="display: none; ">repeat off</a></li>
                </ul>
              </div>
              <div class="jp-title">
                <ul>
                  <li>Cro Magnon Man</li>
                </ul>
              </div>
              <div class="jp-no-solution" style="display: none; ">
                <span>Update Required</span>
                To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
              </div>
            </div>
          </div>
          <div id="jplayer_inspector"></div>
      '.
      $script_sacred. "\n";

      $result .= $player_html;
      
    } else {
      if (!$p) {
        $p = true;
        $result .= '<p>' . $filebasename;
      } else {
        $result .= '<br />' . "\n" . $filebasename;
      }
    }
  }

  if ($p) $result .= '</p>'."\n";

  $result .= '</div>'."\n";

  return $result;
  
}



?>