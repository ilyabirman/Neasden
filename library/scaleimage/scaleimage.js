if ($) $ (function () {
  
  var csscPrefix = 'txt-picture'
  
  var imageAnimateWidthTo = function ($img, width) {
    
    $img.stop ()
    $img.css ('height', 'auto')
    $img.animate ({ width: width }, 'fast')
    
    return false
    
  }

  
  var imageShowRealSize = function () {
  
    this.blur ()
    $ (this).addClass (csscPrefix + '-zoomed')
    
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
    $ (this).removeClass (csscPrefix + '-zoomed')
    
    var $img = $ ('img', $ (this))
    
    previewWidth = $img.data ('previewWidth')
    
    return imageAnimateWidthTo ($img, previewWidth)
    
  }
  
  $ ('a.' + csscPrefix + '-zoom-link').toggle (imageShowRealSize, imageShowPreviewSize)

})
