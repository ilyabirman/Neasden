<?

n__define_line_class (
  'audio',
  '(?:\[play\])(.*)'
);

n__define_group ('audio', '(-audio-)');

function n__render_group_audio ($group) {
  global $_neasden_config;

  $myconf = @$_neasden_config['extensions']['audio'];

  $css_class = $_neasden_config['generic-object-css-class'];
  if (@$myconf['css-class']) $css_class = @$myconf['css-class'];
  
  $downloadstr = 'Download';
  if ($_neasden_config['language'] == 'ru') $downloadstr = 'Скачать';

  $p = false;
  
  //$mypath = $_neasden_config['__overload'];
  $mypath = 'neasden/';

  n__require_link ($mypath .'extensions/audio/player.js');
  n__require_link ($mypath .'extensions/audio/jquery.jplayer.min.js');
  n__require_link ($mypath .'extensions/audio/player.css');

  $result = (
    '<div class="'. $css_class .'"'.
    '>'."\n"
  );
  
  foreach ($group as $line) {
  
    list ($href, $alt) = explode (' ', trim ($line['class-data'][1]), 2);
    $zoneid = rand (1000, 9999);

    $player_html = '

      <div class="jplayer" id="jplayer-ui-zone-'. $zoneid .'">
        
        <div class="jplayer-invisible-object"></div>
        
        <a class="jplayer-swf-source" href="'. $mypath .'/extensions/audio/Jplayer.swf"></a>
        
        <div class="jplayer-progress-area">
          <div class="jplayer-mine-left"></div>
          <div class="jplayer-mine-right"></div>
          <div class="jplayer-mine">
            <div class="jplayer-load-bar-left jplayer-hidden" style="display: none"></div>
            <div class="jplayer-load-bar-right jplayer-hidden" style="display: none"></div>
            <div class="jplayer-load-bar jplayer-hidden" style="display: none"></div>
            <div class="jplayer-play-bar-left" style="display: none"></div>
            <div class="jplayer-play-bar"></div>
            <div class="jplayer-play-lift jplayer-hidden" style="display: none">
              <div class="jplayer-buffering" style="display: none"></div>
            </div>
          </div>
          
        </div>

        <div class="jplayer-info-area">
          <a class="jplayer-audio-source" href="'. $href .'" title="'. $downloadstr .'"></a>

          <div class="jplayer-play-control">
            <div class="jplayer-unavailable jplayer-to-hide"></div>
            <div class="jplayer-hidden" style="display: none">
              <div class="jplayer-play"></div>
              <div class="jplayer-pause" style="display: none"></div>
            </div>
          </div>
            
          <div class="jplayer-play-time"></div>
          <div class="jplayer-total-time"></div>
          
          <div class="jplayer-name">'. $alt .'</div>
          
        </div>
        
      </div>
    
    '."\n";
    
    $player_html = n__save_tag ($player_html);

    $result .= $player_html;
    
  }

  if ($p) $result .= '</p>'."\n";

  $result .= '</div>'."\n";

  return $result;
  
}



?>