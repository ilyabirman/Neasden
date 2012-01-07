<?

n__define_line_class (
  'vimeo',
  'http\:\/\/(?:www\.)?(?:(?:vimeo\.com\/))(\d{8})'
);
n__define_group ('vimeo', '(-vimeo-)(-p-)*');

function n__render_group_vimeo ($group) {
  global $_neasden_config;
  $myconf = $_neasden_config['extensions']['youtube'];

  $p = false;

  $result = '<div class="'. $myconf['css-class'] .'">'."\n";
  foreach ($group as $line) {
  
    if ($line['class'] == 'vimeo') {
    
      $id = $line['class-data'][1];
      $result .= (
        '<iframe width="'. $myconf['width'] .'" height="'. $myconf['height'] .'" '.
        'src="http://player.vimeo.com/video/'. $id .'?title=0&amp;byline=0&amp;portrait=0" '.
        'frameborder="0" webkitAllowFullScreen mozallowfullscreen allowfullscreen>'.
        '</iframe>'.
        "\n"
      );
      
    } else {
    
      if (!$p) {
        $p = true;
        $result .= '<p>' . $line['content'];
      } else {
        $result .= '<br />' . "\n" . $line['content'];
      }
      
    }
    
  }

  $result .= '</div>'."\n";

  return $result;
  
}


?>