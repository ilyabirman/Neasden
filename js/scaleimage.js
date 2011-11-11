function imageShowRealSize () {

  this.blur ()
  $ (this).addClass ('img-zoomed-in')
  
  var $img = $ ('img', $ (this))
  
  $img.data ({ 'previewWidth': $img.width () })

  fullWidth = $ (this).attr ('width')

  // full picture src is a’s href
  fullSrc = this.href
  
  bigImg = new Image ()
  $ (bigImg).attr ('src', fullSrc);
  $ (bigImg).bind ('load', function () {
    $img.attr ('src', fullSrc)
  })
  
  $img.stop ()
  $img.css ('height', 'auto')
  $img.animate ({ width: fullWidth }, 'fast')
  
  return false
  
}

function imageShowPreviewSize () {

  this.blur ()
  $ (this).removeClass ('img-zoomed-in')
  
  var $img = $ ('img', $ (this))
  
  previewWidth = $img.data ('previewWidth')
  
  $img.stop ()
  $img.css ('height', 'auto')
  $img.animate ({ width: previewWidth }, 'fast')
  
  return false
  
}


if ($) $ (function () {
  $ ('a.link-to-big-picture')
    .toggle (imageShowRealSize, imageShowPreviewSize)
    .append('<div class="zoom-helper"></div>');
})
