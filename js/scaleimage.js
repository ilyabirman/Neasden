if ($) $ (function () {
  
  var imageAnimateWidthTo = function ($img, width) {
    
    $img.stop ()
    $img.css ('height', 'auto')
    $img.animate ({ width: width }, 'fast')
    
    return false
    
  }

  
  var imageShowRealSize = function () {
  
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
    
    return imageAnimateWidthTo ($img, fullWidth)
    
  }
  
  var imageShowPreviewSize = function () {
  
    this.blur ()
    $ (this).removeClass ('img-zoomed-in')
    
    var $img = $ ('img', $ (this))
    
    previewWidth = $img.data ('previewWidth')
    
    return imageAnimateWidthTo ($img, previewWidth)
    
  }
  
  $ ('a.link-to-big-picture')
    .toggle (imageShowRealSize, imageShowPreviewSize)
    .append('<div class="zoom-helper"></div>');

})
