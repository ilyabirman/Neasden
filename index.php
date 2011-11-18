<?

/*
профили конфига
вариант для комментариев, без хтмла
дефис после неразрывного пробела не заменится на тире
если дефисов больше трёх подряд, то не нужно их трогать ни один (-------)
выносить кавычки за ссылку
кастомные блоки, например [-adinfo-] - и вставился текст про рекламу на сайте
просто урлы делать ссылками
neasden_detect, чтобы определить, какие картинки и другие ресурсы встречаются в тексте

*/

header ('Content-Type: text/html; charset=utf-8');
//error_reporting (E_ALL & ~E_NOTICE);
error_reporting (E_ALL);

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
#echo 'Time: '. $stopwatch;

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

  .txt-picture { margin-bottom: .7em }
  .txt-picture p { margin: .35em 0 .7em 0; font-size: 85%; }
  .txt-picture a { position: relative; display: inline-block }
  .txt-picture a img { border: 1px #ccc solid }
  .txt-picture a:hover img { border-color: #f33 }
  .txt-picture a .txt-picture-zoom-icon,
  .txt-picture a .txt-picture-zoom-in,
  .txt-picture a .txt-picture-zoomable {
    position: absolute; 
    width: .7em; height: .7em
  }
  .txt-picture a .txt-picture-zoom-icon { right: .35em; top: .35em; display: none }
  .txt-picture a:hover .txt-picture-zoom-icon { display: block }
  .txt-picture a .txt-picture-zoom-in { right: .35em; border-right: 1px #f33 solid }
  .txt-picture a .txt-picture-zoomable { top: .35em; border-top: 1px #f33 solid }
  .txt-picture a.txt-picture-zoomed .txt-picture-zoom-in { display: none }

  .txt-table { margin: 0 0 .7em 0; border-collapse: collapse }
  .txt-table td { border: #ccc solid; border: 1px 0; padding: .35em 1em .35em 0 }
  
  ul li, ol li { margin: 0 0 .35em 0 }
  td { padding: 0 1em 0 0 }
  
  span.slaquo-s {
    margin-right: 0.7em;
  }
  span.hlaquo-s {
      margin-left: -0.7em;
  }
  
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
