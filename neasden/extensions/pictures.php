<?

n__define_line_class ('picture', '.*\.(jpe?g|gif|png)');

function n__detect_class_picture ($line) {
  global $_neasden_config;
  
  return is_file ($_neasden_config['extensions']['pictures']['folder'] . $line);
}

n__define_group ('picture', '(-picture-)(-p-)*');

function n__render_group_picture ($group) {
  global $_neasden_config;
  $myconf = $_neasden_config['extensions']['pictures'];

  $p = false;

  $result = '<div class="'. $myconf['css-class'] .'">'."\n";
  foreach ($group as $line) {
    if ($line['class'] == 'picture') {
      $filename = $myconf['folder'] . $line['content'];
      $size = getimagesize ($filename);
      $result .= '<img src="'. $filename .'" '. $size[3] .' alt="" />'. "\n";
    } else {
      if (!$p) {
        $p = true;
        $result .= '<p>' . $line['content'];
      } else {
        $result .= '<br />' . "\n" . $line['content'];
      }
    }
  }

  if ($p) $result .= '</p>'."\n";

  $result .= '</div>'."\n";
  
  return $result;
  
}



?>