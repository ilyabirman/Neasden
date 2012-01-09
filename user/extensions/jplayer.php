<?

n__define_line_class (
  'jplayer',
  '(?:\[play\])(.*)'
);

n__define_group ('jplayer', '(-jplayer-)');

function n__render_group_jplayer ($group) {
  global $_neasden_config;

  $myconf = $_neasden_config['extensions']['jplayer'];

  $css_class = $_neasden_config['generic-object-css-class'];
  if (@$myconf['css-class']) $css_class = @$myconf['css-class'];
  
  $downloadstr = 'Download';
  if ($_neasden_config['language'] == 'ru') $downloadstr = 'Скачать';

  $p = false;

  n__require_link ($_neasden_config['__overload'] .'extensions/jplayer/player.js');
  n__require_link ($_neasden_config['__overload'] .'extensions/jplayer/jquery.jplayer.min.js');
  n__require_link ($_neasden_config['__overload'] .'extensions/jplayer/player.css');

  $result = (
    '<div class="'. $css_class .'"'.
    '>'."\n"
  );
  
  foreach ($group as $line) {
  
    list ($filebasename, $alt) = explode (' ', $line['content'], 2);
    $zoneid = rand (1000, 9999);

    $player_html = '

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
    
    '."\n";
    
    $player_html = n__save_tag ($player_html);

    $result .= $player_html;
    
  }

  if ($p) $result .= '</p>'."\n";

  $result .= '</div>'."\n";

  return $result;
  
}



?>