<?

$_neasden_config = array (

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
      'css-class' => 'picture',
    ),
  ),
  
);


?>