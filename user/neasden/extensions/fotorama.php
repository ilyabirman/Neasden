<?

n__require_line_class ('picture');
n__define_group ('fotorama', '(-picture-){2,}(-p-)*');

function n__render_group_fotorama ($group, $myconf) {
  global $_neasden_config, $_neasden_extensions;

  n__require_link ('jquery.js');
  n__require_link ('fotorama/fotorama.css');
  n__require_link ('fotorama/fotorama.js');

  if (is_array (@$_neasden_extensions['fotorama']['config'])) {
    $myconf = array_merge (
      $myconf,
      @$_neasden_extensions['fotorama']['config']
    );
  }

  $p = false;

  $result = (
    '<div class="'. $myconf['css-class'] .' fotorama" '.
    //'data-width="'. $myconf['max-width'] .'" '.
    'data-thumbsPreview="false" '.
    'data-zoomToFit="false" '.
    //'data-resize="true"'.
    '>'."\n"
  );
  
  foreach ($group as $line) {
    list ($filebasename, $alt) = explode (' ', $line['content'].' ', 2);
    $alt = trim ($alt);
    
    if ($line['class'] == 'picture') {

      n__resource_detected ($filebasename);
      
      $filename = $myconf['folder'] . $filebasename;
      $size = getimagesize ($filename);
      list ($width, $height) = $size;

      if ($width > $myconf['max-width']) {
        $height = $height * ($myconf['max-width'] / $width);
        $width = $myconf['max-width'];
      }

      $image_html = (
        '<img src="'. $myconf['src-prefix'] . $filename .'" '.
        'width="'. $width .'" height="'. $height.'" '.
        'alt="'. $alt .'" />'. "\n"
      );

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