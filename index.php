<?

/*
научить # фигачить заголовки не с первого уровня, а с другого
explanation должен быть пустым, если не просили
если дефисов больше трёх подряд, то не нужно их трогать ни один (-------)
в ХТМЛ-коде генерируемых таблиц какие-то мусорные пробелы
*/

header ('Content-Type: text/html; charset=utf-8');
//error_reporting (E_ALL & ~E_NOTICE);
error_reporting (E_ALL);
//*
ini_set('display_errors', 1);
error_reporting(~0);
define ('USER_FOLDER', '');
//*/

$text = file_get_contents ('tests/test-14.txt');
$res = '';

function stopwatch () {
  list ($usec, $sec) = explode (' ', microtime ());
  return ((float) $usec + (float) $sec);
}


$stopwatch = stopwatch ();

$inputarray = array (
  'config' => 'neasden/config.php',
  'text-original' => $text,
  'profile-name' => '',
  'explain' => true,
);

if (!include 'neasden/neasden.php') die ('neasden init failed');
$Nn = new Neasden;
$Nn->should_explain = true;
$Nn->profile_name = '';
$res = $Nn->format ($text);
//$res = $Nn->explanation;
$resources = $Nn->resources_detected;
$links = $Nn->links_required;
$groups = $Nn->groups_used;

$stopwatch = stopwatch () - $stopwatch;

?>

<html>
<head>
  
<link rel="stylesheet" href="style.css" />

<?
  
  foreach ($links as $link) {
  
    if (substr ($link, -3) == '.js') {
      echo '<script src="js/'. $link .'"></script>'. "\n";
    }
    if (substr ($link, -4) == '.css') {
      echo '<link rel="stylesheet" href="js/'. $link .'" />'. "\n";
    }
  }
  
?>
  
</head>
<body>

<?#= 'Time: '. $stopwatch; ?>

<?#= neasden_explain ($text); ?>

<?#= '<pre>'. htmlspecialchars ($res) .'</pre>' ?>

<!--
<table>
<tr valign="top">
<td width="50%"><tt><?= nl2br (htmlspecialchars ($text)) ?></tt></td>
<td></td>
</tr>
</table>
-->

<?= $res ?>

<pre>
Resources:
<? print_r ($resources); ?>

Links:
<? print_r ($links); ?>

Groups:
<? print_r ($groups); ?>

</pre>

</body>
</html>