<?

n__define_line_class (
  'audio',
  '(?:\[play\])(.*)'
);

n__define_group ('audio', '(-audio-)');

function n__render_group_audio ($group) {
  global $_neasden_config, $_neasden_extensions;

  $myconf = @$_neasden_extensions['audio']['config'];

  $css_class = $_neasden_config['generic-object-css-class'];
  if (@$myconf['css-class']) $css_class = @$myconf['css-class'];
  
  $downloadstr = 'Download';
  if ($_neasden_config['language'] == 'ru') $downloadstr = 'Скачать';

  $p = false;

  $result = (
    '<div class="'. $css_class .'">'."\n"
  );
  
  foreach ($group as $line) {
  
    @list ($href, $alt) = explode (' ', trim ($line['class-data'][1]), 2);
    if (!$alt) $alt = basename ($href);
    $zoneid = rand (1000, 9999);

    $player_html = '<a '.
      'class="jouele" '.
      'href="'. $href .'" '.
      'title="'. $downloadstr .'" '.
    '>'. $alt .'</a>'."\n";
    
    $player_html = n__isolate ($player_html);

    $result .= $player_html;
    
  }

  if ($p) $result .= '</p>'."\n";

  $result .= '</div>'."\n";

  return $result;
  
}



?>