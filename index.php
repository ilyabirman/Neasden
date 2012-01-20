<?

/*
у экстеншенов должен быть легальный механизм изоляции кода
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

$text = file_get_contents ('tests/test-11.txt');
$res = '';

function stopwatch () {
  list ($usec, $sec) = explode (' ', microtime ());
  return ((float) $usec + (float) $sec);
}


$stopwatch = stopwatch ();
//for ($i = 0; $i < 100; $i ++) {
  $n = neasden ($text, '', '');//, 'comments');
  //$res = $n['result'];
  $res = $n;
//}

$stopwatch = stopwatch () - $stopwatch;

?>

<html>
<head>
  <link rel="stylesheet" href="style.css" />
  <script src="js/jquery.js" type="text/javascript"></script>
  <script src="js/scaleimage.js"></script>
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
<?# print_r ($n['resources']); ?>
</pre>

</body>
</html>