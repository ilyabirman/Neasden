<?

#http://www.youtube.com/watch?v=QvU-zrClsks
#http://youtu.be/QvU-zrClsks


n__define_line_class (
  'youtube',
  'http\:\/\/(?:www.)?(?:(?:youtube\.com\/watch\?v\=)|(?:youtu\.be\/))(.{11})'
);
n__define_group ('youtube', '(-youtube-)(-p-)*');

function n__render_group_youtube ($group) {
  //echo '<pre>';
  //print_r ($group);
  //die ($id);
  
  $id = $group[0]['class-data'][1];

  return '<iframe width="560" height="349" src="http://www.youtube.com/embed/'. $id .'" frameborder="0" allowfullscreen></iframe>' . "\n";
  
}

/*
<iframe width="560" height="349" src="http://www.youtube.com/embed/QvU-zrClsks" frameborder="0" allowfullscreen></iframe>

<object width="560" height="349"><param name="movie" value="http://www.youtube.com/v/QvU-zrClsks?version=3&amp;hl=en_US"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/QvU-zrClsks?version=3&amp;hl=en_US" type="application/x-shockwave-flash" width="560" height="349" allowscriptaccess="always" allowfullscreen="true"></embed></object>
*/


?>