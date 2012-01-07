<?

$_neasden_config = array (

  '__overload' => 'neasden-config.php',
  
  '__profiles' => array (
    'comments' => array (
      'with-html' => false,
      'banned-groups' => array (
        'picture',
      ),
    ),
  ),

  '__extensions' => array (
    'lists',
    'hr',
    'tables',
    'pictures',
    'youtube',
  ),
  
  'opaque-elements' => array (
    'p', 'ul', 'ol', 'li', 'blockquote', 'table', 'pre', 'textarea',
  ),
  
  'sacred-elements' => array (
    'object', 'embed', 'iframe', 'script', 'style', 'code'
  ),
  
  /*
  'fragment-strengths' => array (

    'code' => N_FRAG_STRENGTH_OPAQUE, // inline element
    'pre' => N_FRAG_STRENGTH_OPAQUE,
    'textarea' => N_FRAG_STRENGTH_OPAQUE,
    'p' => N_FRAG_STRENGTH_OPAQUE,
    'ul' => N_FRAG_STRENGTH_OPAQUE,
    'ol' => N_FRAG_STRENGTH_OPAQUE,
    'blockquote' => N_FRAG_STRENGTH_OPAQUE,
    'li' => N_FRAG_STRENGTH_OPAQUE,

    'object' => N_FRAG_STRENGTH_SACRED,
    'embed' => N_FRAG_STRENGTH_SACRED,
    'iframe' => N_FRAG_STRENGTH_SACRED,
    'script' => N_FRAG_STRENGTH_SACRED,
    'style' => N_FRAG_STRENGTH_SACRED,
    
  ),
  */
  
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

  'extensions' => array (
    'lists' => array (
      'chars-ul-items' => array ('-', '–', '—', '*'),
    ),
    'pictures' => array (
      'src-prefix' => 'http://neasden/',
      'folder' => 'pictures/',
      'css-class' => 'txt-picture', // see also var csscPrefix in scaleimage.js
      'max-width' => '368',
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
  ),
  
);


?>