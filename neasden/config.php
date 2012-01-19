<?

$_neasden_config = array (

  '__overload' => 'user/',
  
  '__profiles' => array (
    'comments' => array (
      'with-html' => false,
      'banned-groups' => array (
        'picture',
      ),
    ),
  ),
  
  'opaque-elements' => array (
    'p', 'ul', 'ol', 'li', 'blockquote', 'table', 'pre', 'textarea',
  ),
  
  'sacred-elements' => array (
    'object', 'embed', 'iframe', 'script', 'style', 'code'
  ),
  
  'with-html' => true,
  'with-groups' => true,
  'with-typography' => true,
  
  'language' => 'ru',

  'char-headings'  => '#',

  'char-quotes'    => '>',
  
  'nettoyer' => array (
    '&nbsp;' => ' ',
    '&laquo;' => '«',
    '&raquo;' => '»',
    '&bdquo;' => '„',
    '&ldquo;' => '“',
    '&rdquo;' => '”',
  ),
  
  'generic-object-css-class' => 'txt-generic-object',

  'extensions' => array (
    'lists' => array (
      'chars-ul-items' => array ('-', '–', '—', '*'),
    ),
    'pictures' => array (
      'src-prefix' => 'http://neasden/',
      'folder' => 'pictures/',
      'css-class' => 'txt-picture', // see also var csscPrefix in scaleimage.js
      'max-width' => '768',
      'scaled-img-folder' => 'pictures/scaled/',
      //'scaled-img-provider' => '@scale-image:',
      'scaled-img-extension' => 'scaled.jpg',
      'scaled-img-link-to-original' => true,
      'scaled-img-link-to-original-class' => 'link-to-big-picture',
    ),
    'tables' => array (
      'css-class' => 'txt-table',
    ),
    'youtube' => array (
      'css-class' => 'txt-video',
      'width' => 768,
      'height' => 480,
    ),
    'vimeo' => array (
      'css-class' => 'txt-video',
      'width' => 768,
      'height' => 480,
    ),
  ),
  
);


?>