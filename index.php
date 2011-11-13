<?

/*
профили конфига
вариант для комментариев, без хтмла
дефис после неразрывного пробела не заменится на тире

*/

header ('Content-Type: text/html; charset=utf-8');
error_reporting (E_ALL & ~E_NOTICE);

if (!include 'neasden/neasden.php') die ('neasden init failed');

$text = file_get_contents ('tests/test-7.txt');

$res = '';

function stopwatch () {
  list ($usec, $sec) = explode (' ', microtime ());
  return ((float) $usec + (float) $sec);
}


$stopwatch = stopwatch ();
//for ($i = 0; $i < 100; $i ++) {
  $res = neasden ($text);
//}

$stopwatch = stopwatch () - $stopwatch;
echo 'Time: '. $stopwatch;

?>

<style>
  body { font-family: "Arial" }
  tt { font-family: "Consolas" }
  h1, h2, h3, h4, h5, h6, p, ul, ol, blockquote, .picture { margin: 0 0 .7em 0 }
  h1, h2, h3, h4, h5, h6 { font-weight: bold; }
  h1 { font-size: 130%; }
  h2 { font-size: 115%; }
  h3, h4, h5, h6 { font-size: 100%; }
  p+ul, p+ol { margin-top: -.7em }
  blockquote { color: #009; border-left: 1px #009 solid; padding-left: 1em }
  .txt-picture p { margin: .35em 0 .7em 0; font-size: 85%; }
  .txt-table { margin: 0 0 .7em 0; border-collapse: collapse }
  .txt-table td { border: 1px #ccc solid; padding: 10px }
  ul li, ol li { margin: 0 0 .35em 0 }
  td { padding: 0 1em 0 0 }
</style>

<?#= neasden_explain ($text); ?>

<!--
<pre><?= htmlspecialchars ($res) ?></pre>
-->

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
