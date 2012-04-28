<?

$_neasden_config = array (

  '__overload' => 'user/neasden/',
  
  '__profiles' => array (
    'simple' => array (
      'html.on' => false,
      'banned-groups' => array (
        'picture', 'fotorama', 'audio', 'youtube', 'vimeo'
      ),
    ),
  ),
    
  'language' => 'ru',
  
  'html.on' => true,
  'html.elements.opaque' => 'p ul ol li blockquote table pre textarea',
  'html.elements.sacred' => 'object embed iframe script style code',

  'groups.on' => true,
  'groups.generic-css-class' => 'txt-generic-object',
  'groups.headings.char'  => '#',
  'groups.quotes.char' => '>',
  'groups.lists.chars' => array ('-', '*'),
  
  'typography.on' => true,
  'typography.markup' => true,
  'typography.autohref' => true,
  'typography.cleanup' => array (
    '&nbsp;' => ' ',
    '&laquo;' => '«',
    '&raquo;' => '»',
    '&bdquo;' => '„',
    '&ldquo;' => '“',
    '&rdquo;' => '”',
  ),
  

  'extensions' => array (

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