<?

/*
дефис после неразрывного пробела не заменится на тире
если дефисов больше трёх подряд, то не нужно их трогать ни один (-------)
выносить кавычки за ссылку
кастомные группы, например [-adinfo-] - и вставился текст про рекламу на сайте
просто урлы делать ссылками
neasden_detect, чтобы определить, какие картинки и другие ресурсы встречаются в тексте
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
//for ($i = 0; $i < 100; $i ++) {
  $res = neasden ($text);//, 'comments');
//}

$stopwatch = stopwatch () - $stopwatch;

?>

<html>
<head>
  <link rel="stylesheet" href="style.css" />
</head>
<body>

<?#= 'Time: '. $stopwatch; ?>

<?#= neasden_explain ($text); ?>

<?#= '<pre>'. htmlspecialchars ($res) .'</pre>' ?>

<table>
<tr valign="top">
<!--
<td width="50%"><tt><?= nl2br (htmlspecialchars ($text)) ?></tt></td>
-->
<td><?= $res ?></td>
</tr>
</table>

<script src="js/jquery.js"></script>
<script src="js/scaleimage.js"></script>

</body>
</html>