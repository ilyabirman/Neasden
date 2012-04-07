<?

/*
научить # фигачить заголовки не с первого уровня, а с другого

у экстеншенов должен быть легальный механизм изоляции кода
если дефисов больше трёх подряд, то не нужно их трогать ни один (-------)
просто урлы делать ссылками
в ХТМЛ-коде генерируемых таблиц какие-то мусорные пробелы
*/

header ('Content-Type: text/html; charset=utf-8');
//error_reporting (E_ALL & ~E_NOTICE);
error_reporting (E_ALL);

if (!include 'neasden/neasden.php') die ('neasden init failed');

$text = file_get_contents ('tests/test-9.txt');
$res = '';

function stopwatch () {
  list ($usec, $sec) = explode (' ', microtime ());
  return ((float) $usec + (float) $sec);
}


$stopwatch = stopwatch ();

$n = array (
  'text-original' => $text,
  'profile-name' => '',
  'explain' => true,
);

//$n = neasden ($text, 'simple', '');//, 'comments');
$n = neasden ($n);//, 'comments');
$res = $n['text-final'];
//$res = $n['explanation'];

$stopwatch = stopwatch () - $stopwatch;

?>

<html>
<head>
  
<link rel="stylesheet" href="style.css" />
<script src="js/jquery.js"></script>
<script src="js/scaleimage.js"></script>

<link rel="stylesheet" href="jouele/jouele.css" />
<script src="jouele/jquery.jplayer.min.js"></script>
<script src="jouele/jouele.js"></script>

<?

  if (in_array ('fotorama', $n['groups-used'])) {
    echo '<script src="/user/extensions/fotorama/fotorama.js"></script>';
    echo '<link rel="stylesheet" href="/user/extensions/fotorama/fotorama.css" />';    
  }

  /*
  foreach ($n['links-required'] as $link) {
  
    if (substr ($link, -3) == '.js') {
      echo '<script src="'. $link .'"></script>'. "\n";
    }
    if (substr ($link, -4) == '.css') {
      echo '<link rel="stylesheet" href="'. $link .'" />'. "\n";
    }
  }
  */
  
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

Groups:
<? print_r ($n['groups-used']); ?>

</pre>

</body>
</html>