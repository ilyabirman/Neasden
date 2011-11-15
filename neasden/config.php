<?

$_neasden_config = array (

  '__overload' => 'neasden-config.php',

  'block-strengths' => array (

    'code' => N_BLOCK_STRENGTH_OPAQUE,
    'pre' => N_BLOCK_STRENGTH_OPAQUE,
    'textarea' => N_BLOCK_STRENGTH_OPAQUE,

    'object' => N_BLOCK_STRENGTH_SACRED,
    'embed' => N_BLOCK_STRENGTH_SACRED,
    'iframe' => N_BLOCK_STRENGTH_SACRED,
    'script' => N_BLOCK_STRENGTH_SACRED,
    'style' => N_BLOCK_STRENGTH_SACRED,
    
  ),

  'format-blocks' => true,

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
  
  'with-typography' => true,
  
  'language' => 'ru',

  'extensions-list'  => array (
    'lists',
    'hr',
    'tables',
    'pictures',
    'youtube',
  ),

  'extensions' => array (
    'lists' => array (
      'chars-ul-items' => array ('-', '–', '—', '*'),
    ),
    'pictures' => array (
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
  ),
  
);


?>