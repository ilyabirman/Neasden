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

if (!include 'neasden/neasden.php') die ('neasden init failed');

$text = file_get_contents ('tests/test-12.txt');
$res = '';

function stopwatch () {
  list ($usec, $sec) = explode (' ', microtime ());
  return ((float) $usec + (float) $sec);
}


$stopwatch = stopwatch ();

$n = neasden (array (
  'config' => 'neasden/config.php',
  'text-original' => $text,
  'profile-name' => '',
  'explain' => true,
));



$res = $n['text-final'];

//$res = $n['explanation'];

$stopwatch = stopwatch () - $stopwatch;

?>

<html>
<head>
  
<link rel="stylesheet" href="style.css" />

<?
  /*
  if (in_array ('picture', $n['groups-used'])) {
    echo '<script src="js/scaleimage.js"></script>'. "\n";
  }

  if (in_array ('fotorama', $n['groups-used'])) {
    echo '<script src="js/fotorama/fotorama.js"></script>'. "\n";
    echo '<link rel="stylesheet" href="js/fotorama/fotorama.css" />'. "\n";
  }

  if (in_array ('audio', $n['groups-used'])) {
    echo '<link rel="stylesheet" href="js/jouele/jouele.css" />'. "\n";
    echo '<script src="js/jouele/jquery.jplayer.min.js"></script>'. "\n";
    echo '<script src="js/jouele/jouele.js"></script>'. "\n";
  }
  */
  
  //*
  foreach ($n['links-required'] as $link) {
  
    if (substr ($link, -3) == '.js') {
      echo '<script src="js/'. $link .'"></script>'. "\n";
    }
    if (substr ($link, -4) == '.css') {
      echo '<link rel="stylesheet" href="js/'. $link .'" />'. "\n";
    }
  }
  //*/
  
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
<? print_r ($n['resources-detected']); ?>

Links:
<? print_r ($n['links-required']); ?>

Groups:
<? print_r ($n['groups-used']); ?>

</pre>

</body>
</html>