<?php

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

$text = file_get_contents ('tests/test-24.txt');
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
// $Nn->profile_name = 'kavychki';

$res = $Nn->format ($text);

// $res = $Nn->explanation;

$stopwatch = stopwatch () - $stopwatch;

?>

<html>
<head>
  

<!--
<link rel="stylesheet" href="http://yandex.st/highlightjs/7.4/styles/ascetic.min.css">
<script src="http://yandex.st/highlightjs/7.4/highlight.min.js"></script>
<script>hljs.initHighlightingOnLoad();</script>
-->


<link rel="stylesheet" href="style.css" />

<?php
  
  foreach ($Nn->links_required as $link) {
  
    if (substr ($link, -3) == '.js') {
      echo '<script src="library/'. $link .'"></script>'. "\n";
    }
    if (substr ($link, -4) == '.css') {
      echo '<link rel="stylesheet" href="library/'. $link .'" />'. "\n";
    }
  }
  
?>
  
</head>
<body>

<?php #= 'Time: '. $stopwatch; ?>

<?php #= neasden_explain ($text); ?>

<?php #= '<pre>'. htmlspecialchars ($res) .'</pre>' ?>

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
<?php print_r ($Nn->resources_detected); ?>

Links:
<?php print_r ($Nn->links_required); ?>

Groups:
<?php print_r ($Nn->groups_used); ?>

</pre>

<hr>

<?= $stopwatch ?>

</body>
</html>