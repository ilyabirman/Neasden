<?

n__define_line_class (
  'youtube',
  'http\:\/\/(?:www\.)?(?:(?:youtube\.com\/watch\/?\?v\=)|(?:youtu\.be\/))(.{11})'
);
n__define_group ('youtube', '(-youtube-)(-p-)*');

function n__render_group_youtube ($group, $myconf) {
  global $_neasden_config, $_neasden_extensions;

  $p = false;

  $result = '<div class="'. $myconf['css-class'] .'">'."\n";
  foreach ($group as $line) {
  
    if ($line['class'] == 'youtube') {
    
      $id = $line['class-data'][1];
      $result .= (
        '<iframe width="'. $myconf['width'] .'" height="'. $myconf['height'] .'" '.
        'src="http://www.youtube.com/embed/'. $id .'" frameborder="0" allowfullscreen>'.
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