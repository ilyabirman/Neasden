<?

//n__define_line_class ('picture', '.*\.(jpe?g|gif|png)');
n__define_line_class ('picture', '.*\.(jpe?g|gif|png)(?: +(.+))?');

function n__detect_class_picture ($line) {
  global $_neasden_config;
  
  list ($filebasename, ) = explode (' ', $line, 2);  
  return is_file ($_neasden_config['extensions']['pictures']['folder'] . $filebasename);
}

n__define_group ('picture', '(-picture-)(-p-)*');

function n__render_group_picture ($group) {
  global $_neasden_config;
  $myconf = $_neasden_config['extensions']['pictures'];

  $p = false;

  $result = '<div class="'. $myconf['css-class'] .'">'."\n";
  foreach ($group as $line) {
    list ($filebasename, $alt) = explode (' ', $line['content'], 2);
    
    if ($line['class'] == 'picture') {
      $filename = $myconf['folder'] . $filebasename;
      $size = getimagesize ($filename);
      $result .= '<img src="'. $filename .'" '. $size[3] .' alt="'. $alt .'" />'. "\n";
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