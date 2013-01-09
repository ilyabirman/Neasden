<?

class NeasdenGroup_hr extends NeasdenGroup {

  function __construct ($neasden) {
    $neasden->define_line_class ('hr', '[-–—]{5,}');
    $neasden->define_group ('hr', '(-hr-)');
  }

  function render ($group, $myconf) {
    return "<hr />\n";
  }
  
}


?>