<?php return array (

  '__overload' => 'user/neasden/',
  
  '__profiles' => array (
    'simple' => array (
      'html.on' => false,
      'banned-groups' => array (
        'picture', 'fotorama', 'audio', 'youtube', 'vimeo'
      ),
    ),
   'kavychki' => array (
      'html.on' => true,
      'groups.on' => false,
      'typography.markup' => false,
      'typography.autohref' => false,
    ),
 ),
    
  'library' => '',
  
  'language' => 'ru',
  
  'html.on' => true,
  'html.elements.opaque' => 'p ul ol li pre',
  'html.elements.ignore' => 'div blockquote table tr td th thead tbody tfoot caption colgroup col',
  'html.elements.sacred' => 'object embed iframe head link script style code textarea',
  'html.basic' => false,

  'html.code.on' => true,
  // 'html.code.wrap' => array ('<pre><code>', '</code></pre>'),  
  'html.code.wrap' => array ('<pre><code class="%s">', '</code></pre>'),  
  'html.code.highlightjs' => true,

  'html.img.prefix' => 'pictures/',
  'html.img.detect' => true,

  'groups.on' => true,
  'groups.headings.char'  => '#',
  'groups.headings.plus'  => 0,
  'groups.quotes.char' => '>',
  'groups.lists.chars' => array ('-', '*'),
  'groups.generic-css-class' => 'txt-generic-object',
  'groups.classes' => array (
    'picture' => array (
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
    'fotorama' => array (
      'src-prefix' => 'http://neasden/',
      'folder' => 'pictures/',
      'css-class' => 'txt-picture', // see also var csscPrefix in scaleimage.js
      'max-width' => '768',
    ),
    'table' => array (
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
    'audio' => array (
      'src-prefix' => 'http://neasden/',
      'folder' => 'audio/',
    ),
  ),
  
  'typography.on' => true,
  'typography.quotes' => true,
  'typography.markup' => true,
  'typography.autohref' => true,
  'typography.nofollowhrefs' => false,
  'typography.cleanup' => array (
    '&nbsp;' => ' ',
    '&laquo;' => '«',
    '&raquo;' => '»',
    '&bdquo;' => '„',
    '&ldquo;' => '“',
    '&rdquo;' => '”',
  ),

); ?>