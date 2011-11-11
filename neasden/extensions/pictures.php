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
      list ($width, $height) = $size;
      
      $filename_original = $filename;
      $is_scaled = false;

      // image too wide
      if ($width > $myconf['max-width']) {

        $is_scaled = true;
        $height = $height * ($myconf['max-width'] / $width);
        $width = $myconf['max-width'];

        // if scaled down images are or should be provided
        if (array_key_exists ('scaled-img-folder', $myconf)) {

          $filename_scaled = $myconf['scaled-img-folder'] . $filebasename;
        
          if (is_file ($filename_scaled)) {
            // use the scaled file
            $filename = $filename_scaled;
            $size = getimagesize ($filename);
            list ($width, $height) = $size;
          } elseif (array_key_exists ('scaled-img-provider', $myconf)) {
            // call the provider
            $filename = $myconf['scaled-img-provider'] . $filebasename;
          }

          // otherwise leave the file as-is, browser will scale it
          
        }

      }
      
      $image_html = (
        '<img src="'. $filename .'" '.
        'width="'. $width .'" height="'. $height.'" '.
        'alt="'. $alt .'" />'. "\n"
      );

      // wrap into a link if needed
      if ($myconf['scaled-img-link-to-original'] and $is_scaled) {
        $image_html = '<a href="'. $filename_original .'">'. $image_html .'</a>';
      }
      
      $result .= $image_html;
      
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