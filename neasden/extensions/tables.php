<?

n__require_line_class ('hr');
n__define_line_class ('tr', '\|([^\|]+\|)+');

n__define_group ('table', '(-hr-)(-tr-)+(-hr-)?');

function n__render_group_table ($group) {

  $result = '<table cellpadding="0" cellspacing="0" border="0">' ."\n";
  foreach ($group as $line) if ($line['class'] == 'tr') {
    $result .= "<tr>\n";
    $tr = explode ('|', trim ($line['content'], '|'));
    foreach ($tr as $td) {
      $result .= "<td>". trim ($td) ."</td>\n";
    }
    $result .= "</tr>\n";
  }

  $result .= "</table>\n";
  
  return $result;
  
}



?>