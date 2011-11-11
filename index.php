<?

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
  .p-picture p { margin: .35em 0 .7em 0; font-size: 85%; }
  .p-table { margin: 0 0 .7em 0; border-collapse: collapse }
  .p-table td { border: 1px #ccc solid; padding: 10px }
  ul li, ol li { margin: 0 0 .35em 0 }
  td { padding: 0 1em 0 0 }
</style>

<?#= neasden_explain ($text); ?>

<!--
<pre><?= htmlspecialchars ($res) ?></pre>
-->

<table>
<tr valign="top">
<td width="50%"><tt><?= nl2br (htmlspecialchars ($text)) ?></tt></td>
<td><?= $res ?></td>
</tr>
</table>

<script src="js/jquery.js"></script>
<script>
function imageShowRealSizes()
{
        this.blur();
        $(this).addClass('img-zoomed-in');
        var aOriginalSizes, iWidth, iHeight, dImg, dFullsizeImage; 
        /*
        // Размеры оригинального изображения берутся из имени ссылки, куда они записываются серверным скриптом
        aOriginalSizes = this.name.match(/(\d+)x(\d+)/i);
        */
        aOriginalSizes = [100, 100, 200, 200]
        iWidth = aOriginalSizes[1];
        iHeight = aOriginalSizes[2];
        // Объект изображения
        dImg = $('img', $(this));
        // Полноразмерное изображение
        dFullsizeImage = new Image();
        // Адрес полноразмерного изображения берётся из ссылки
        sSrc = this.href;
        $(dFullsizeImage).bind('load', function(){
            $(dImg).attr('src', sSrc);    
        });
        $(dFullsizeImage).attr('src', sSrc);
        dImg.stop();
		dImg.css("height","auto");
        dImg.animate({width: iWidth}, 'fast');
        return false;    
}

function imageShowPrewieSizes()
{
        this.blur();
        $(this).removeClass('img-zoomed-in');
        var aOriginalSizes, iWidth, iHeight, dImg; 
        /*
        aOriginalSizes = this.name.match(/(\d+)x(\d+)x(\d+)x(\d+)/i);
        */
        aOriginalSizes = [100, 100, 200, 200]
        iWidth = aOriginalSizes[3];
        iHeight = aOriginalSizes[4];
        // Объект изображения
        dImg = $('img', $(this));
        dImg.stop();
		dImg.css("height","auto");
        dImg.animate({width: iWidth}, 'fast');
        return false;    
}


if ($) $ (function () {
  $ ('a.link-to-big-picture')
    .toggle (imageShowRealSizes, imageShowPrewieSizes)
    .append('<div class="zoom-helper"></div>');
})
</script>