<?

// Neasden v31

define ('N_FRAG_STRENGTH_TEXT', 0); // grouped, typographed
define ('N_FRAG_STRENGTH_OPAQUE', 7); // typographed
define ('N_FRAG_STRENGTH_SACRED', 9); // returned as is

define ('N_RX_SPECIAL_CHAR', "\x1");
define ('N_RX_SPECIAL_SEQUENCE_LENGTH', 6);

define ('N_RX_TAG', '\\' . N_RX_SPECIAL_CHAR .'\d{'. N_RX_SPECIAL_SEQUENCE_LENGTH .'}\\' . N_RX_SPECIAL_CHAR);

define ('N_RX_TAGS', '(?:'. N_RX_TAG .')*');

define ('N_MAX_H_LEVEL', 6);
define ('N_DEFAULT_GROUP', 'p');

require 'config.php';

$_default_config = $_neasden_config;

if (array_key_exists ('__overload', $_neasden_config)) {
  if (is_file ($_neasden_config['__overload']. 'config.php')) {
    $_default_config = $_neasden_config;
    require $_neasden_config['__overload']. 'config.php';
    $_neasden_config = array_merge ($_default_config, $_neasden_config);
    
    // now use this as a basis for profiles
    $_default_config = $_neasden_config;
  }
}

$_neasden_links = array ();

$_neasden_resources = array ();

$_neasden_required_line_classes = array ();

$_neasden_used_groups = array ();

$_neasden_line_classes = array (
 //'ul-item' => '\-+|\–+|\—+|\*+ *.+',
//  'hr'      => '[-–—]{5,}',
//  'tr'      => '\|([^\|]+\|)+',
//  'url'     => 'https?\:\/\/[^ ]+',
//  'youtube' => 'https?\:\/\/youtube[^ ]+',
);

$_neasden_groups = array (
  'empty'   => '(-empty-)+',
  'p'       => '(-p-)+',
  'h1'      => '(-h1-)+',
  'h2'      => '(-h2-)+',
  'h3'      => '(-h3-)+',
  'h4'      => '(-h4-)+',
  'h5'      => '(-h5-)+',
  'h6'      => '(-h6-)+',
  /*
  'list'    => '((-ol-item-)|(-ul-item-))((-ol-item-)|(-ul-item-)|(-p-))*',
  'hr'      => '(-hr-)',
  'table'   => '(-hr-)(-tr-)+(-hr-)?',
  */
  //'img'     => '(-img-name-)((-img-name-)|(-p-))*',
  //'youtube' => '(-youtube-href-)(-p-)*',
);

$_neasden_saved_tags = array ();



function n__init () {
  global
    $_neasden_config,
    $_neasden_language,
    $_neasden_extensions,
    $_neasden_line_classes,
    $_neasden_required_line_classes;

  include 'languages/'. $_neasden_config['language'] .'.php';
  
  $host_dir = dirname ($_SERVER['PHP_SELF']); # '/meanwhile'
  $host_dir = trim ($host_dir, '/').'/'; # 'meanwhile/'
  if ($host_dir == '/') $host_dir = '';
  
  $dir = rtrim (dirname (__FILE__), '/'). '/';
  $dir = str_replace ($_SERVER['DOCUMENT_ROOT'] .'/'. $host_dir, '', $dir);

  $extensions_folders = array (
    $dir. 'extensions',
    $_neasden_config['__overload']. 'extensions'
  );

  $_neasden_extensions = array ();

  foreach ($extensions_folders as $extensions_folder) {
    foreach (glob ($extensions_folder. '/*.php') as $file) {
      $name = basename ($file);
      if (substr ($name, -4) == '.php') $name = substr ($name, 0, strlen ($name) - 4);
      if (!array_key_exists ($name, $_neasden_extensions)) {
        $_neasden_extensions[$name] = array (
          'path' => dirname ($file) .'/'. $name .'/',
          'config' => @$_neasden_config['extensions'][$name],
        );
        //echo '+'.$file.'<br>';
        include $file;
      }
    }
  }
  /*
  echo '<pre>';
  print_r ($_neasden_extensions);
  die;
  //*/

  foreach ($_neasden_required_line_classes as $class => $no_need) {
    if (!array_key_exists ($class, $_neasden_line_classes)) {
      return false;
    }
  }
  
  return true;
  
}



function n__require_line_class ($class) {
  global $_neasden_required_line_classes;
  $_neasden_required_line_classes[$class] = true;
}



function n__define_line_class ($class, $regex) {
  global $_neasden_line_classes;
  $_neasden_line_classes[$class] = $regex;
}



function n__define_group ($group, $regex) {
  global $_neasden_groups;
  $_neasden_groups[$group] = $regex;
}



function n__resource_detected ($resource) {
  global $_neasden_resources;
  $_neasden_resources[] = $resource;
}


function n__require_link ($link) {
  global $_neasden_links;
  $_neasden_links[] = $link;
}


function n__special_sequence ($index) {
  return N_RX_SPECIAL_CHAR . str_pad ($index, N_RX_SPECIAL_SEQUENCE_LENGTH, '0', STR_PAD_LEFT) . N_RX_SPECIAL_CHAR;
}



function n__isolate ($tag) {
  //return $tag;
  global $_neasden_saved_tags;
  $index = count ($_neasden_saved_tags);
  if (is_array ($tag)) $tag = $tag[0];
  $_neasden_saved_tags[$index] = $tag;
  return n__special_sequence ($index);

}



function n__unisolate ($text) {

  global $_neasden_saved_tags;
  foreach ($_neasden_saved_tags as $index => $value) {
    $text = str_replace (n__special_sequence ($index), $value, $text);
  }
  return $text;

}



// puts quotes, really
function n__enclose_within_tagless ($text, $char, $enclosures) {

  if (count ($enclosures) == 0) return;
  if (count ($enclosures) == 1) $enclosures[1] = $enclosures[0];
  if (count ($enclosures) == 2) {
    $enclosures[3] = $enclosures[1];
    $enclosures[2] = $enclosures[1];
    $enclosures[1] = $enclosures[0];
  }
  if (count ($enclosures) == 3) $enclosures[3] = $enclosures[2];

  // obvious replacements
  if (1) {
    $text = preg_replace (
      '/((?:^|\s|\-)'. N_RX_TAGS .')'.
      preg_quote ($char).
      '(?!'. N_RX_TAGS .'($|\-|\s))/m',
      '$1'. $enclosures[0],
      $text
    );
  }
  
  if (1) {
    $text = preg_replace (
      '/(?<!^|\s|\-)('. N_RX_TAGS .')'.
      preg_quote ($char).
      '(?='. N_RX_TAGS ."(?:$|\-|\s))/m",
      '$1'. $enclosures[3],
      $text
    );
  }

  // remaining replacements
  if (1) {
    $len = strlen ($enclosures[0]);
    $qdepth = 0;
    for ($i = 0; $i < strlen ($text)-1; ++ $i) {
      $scan = substr ($text, $i, $len);
      if ($scan == $enclosures[0]) {
        $qdepth ++;
        if ($qdepth > 1) $text = substr ($text, 0, $i) . $enclosures[1] . substr ($text, $i + $len);
        $i += $len;
      }
      if ($scan == $enclosures[3]) {
        if ($qdepth > 1) $text = substr ($text, 0, $i) . $enclosures[2] . substr ($text, $i + $len);
        $qdepth --;
        $i += $len;
      }
      if ($i > strlen ($text)-1) break;
      if ($text[$i] == $char) {
        if ($qdepth > 0) {
          if ($qdepth > 1)
            $text = substr ($text, 0, $i) . $enclosures[2] . substr ($text, $i + 1);
          else
            $text = substr ($text, 0, $i) . $enclosures[3] . substr ($text, $i + 1);
          -- $qdepth;
        } else {
          $text = substr ($text, 0, $i) . $enclosures[0] . substr ($text, $i + 1);
          ++ $qdepth;
        }
      }
    }
  }
  
  return $text;                                                

}



// 

function n__process_double_brackets_contents_callback ($params) {
  global $_neasden_language;

  $text = @$params[1] . @$params[2] . @$params[3] . @$params[4];
  @list ($href, $text) = explode (' ', $text, 2);
  if (!@$text) $text = $href;
  
  $quotes = $_neasden_language['quotes'];
  $quotes_left = array ('"', $quotes[0], $quotes[1]);
  $quotes_right = array ('"', $quotes[2], $quotes[3]);
  $hang_left = mb_substr ($text, 0, 1);
  $hang_right = mb_substr ($text, -1);
  $quotes_should_hang = in_array ($hang_left, $quotes_left) and in_array ($hang_right, $quotes_right);

  if ($quotes_should_hang)  {
    $text = mb_substr ($text, 1, mb_strlen ($text) - 2);
    $a_in = n__isolate ('<a href="'. $href .'" class="nu">');
    $u_in = n__isolate ('<u>');
    $u_out = n__isolate ('</u>');
    $a_out = n__isolate ('</a>');
    return $a_in . $hang_left . $u_in . $text . $u_out . $hang_right . $a_out;
  } else {
    $a_in = n__isolate ('<a href="'. $href .'">');
    $a_out = n__isolate ('</a>');
    if (!@$text) $text = $href;
    return $a_in . $text . $a_out;
  }
  
}



// replacements, quotes, dashes, no-break spaces
// input text must be netto:
// no html entities, just actual utf-8 chars
  
function n__typography ($text) {
  global $_neasden_config, $_neasden_language;

  $nbsp = " ";
  
  $quotes = $_neasden_language['quotes'];
  $dash = $_neasden_language['dash'];

  //$span_tsp = n__isolate ('<span class=\"tsp\">'. $nbsp .'</span>');
  $nobr_in = n__isolate ('<nobr>');
  $nobr_out = n__isolate ('</nobr>');

  #echo htmlspecialchars ($text);
  #die;

  $text = preg_replace_callback ('/(?:\<[^\>]+\>)/isxu', 'n__isolate', $text);

  #echo htmlspecialchars ($text);
  #die;
  
  // double brackets
  $chars = array ('\\(', '\\)', '\\[', '\\]');
  $text = preg_replace_callback (
    '/'.
    '(?:'. $chars[0].$chars[0] .'(?!'. $chars[0] .')(?=\S)(.*?)'.  $chars[1].$chars[1] .')'.
    '|'.
    '(?:'. $chars[0].$chars[0] .'(?!'. $chars[0] .')(.*?)(?<=\S)'. $chars[1].$chars[1] .')'.
    '|'.
    '(?:'. $chars[2].$chars[2] .'(?!'. $chars[2] .')(?=\S)(.*?)'.  $chars[3].$chars[3] .')'.
    '|'.
    '(?:'. $chars[2].$chars[2] .'(?!'. $chars[2] .')(.*?)(?<=\S)'. $chars[3].$chars[3] .')'.
    '/imu',
    'n__process_double_brackets_contents_callback',
    $text
  );
  
  // wiki stuff
  $duomap = array ('/' => 'i', '*' => 'b', '-' => 's');
  foreach ($duomap as $from => $to) {
    if (!@$t_in[$to]) $t_in[$to] = n__isolate ('<'. $to .'>');
    if (!@$t_out[$to]) $t_out[$to] = n__isolate ('</'. $to .'>');
    $char = '\\'. $from;
    $text = preg_replace (
      '/'.
      '(?:'. $char.$char .'(?!'. $char .')(?=\S)(.*?)'. $char.$char .')'.
      '|'.
      '(?:'. $char.$char .'(?!'. $char .')(.*?)(?<=\S)'. $char.$char .')'.
      '/imu',
      $t_in[$to] . '$1$2' . $t_out[$to],
      $text
    );
  }
  

  // replacements
  if (1) {
    if (array_key_exists ('replacements', $_neasden_language)) {
      $text = str_replace (
        array_keys ($_neasden_language['replacements']),
        array_values ($_neasden_language['replacements']),
        $text
      );
    }
  }
  
  // quotes
  $text = n__enclose_within_tagless ($text, '"', $quotes);
  

  // dash
  $text = preg_replace (
    '/(?<=^| |'. preg_quote ($nbsp) .')('. N_RX_TAGS .')\-('. N_RX_TAGS .')(?= |$)/m',
    '$1'. $dash .'$2',
    $text
  );

  // space before dash
  $text = preg_replace ('/ ('. N_RX_TAGS .')'. preg_quote ($dash) .'/', $nbsp .'$1'. $dash, $text);

  // unions and prepositions
  if (1) {
    //die ($text);
    if ($nobreak_fw = $_neasden_language['with-next']) {
      $text = preg_replace (
        "/".
        "(?<!\pL|\-)".    // not-a—Unicode-letter-or-dash lookbehind
        $nobreak_fw .     // a preposition
        "(". N_RX_TAGS .")".
        " ".              // and a space
        "/isu",      
        '$1$2'. $nbsp,
        $text
      );
    }
  
    if ($nobreak_bw = $_neasden_language['with-prev']) {
      $text = preg_replace (
        "/".
        " ".             // a space
        "(". N_RX_TAGS .")".
        $nobreak_bw .    // a particle
        "(?!\pL|\-)".    // not-a—Unicode-letter-or-dash lookforward
        "/isu",      
        $nbsp .'$1$2',
        $text
      );
    }
  }


  return $text;

}




// any opaque fragment or a text fragment after formatting
// should be typographed with this function

function n__process_opaque_fragment ($text) {
  global $_neasden_config, $_neasden_tag_machine;
  
  // replace &laquo; with normal quote characters
  $text = str_replace (
    array_keys ($_neasden_config['nettoyer']),
    array_values ($_neasden_config['nettoyer']),
    $text
  );
  
  if ($_neasden_config['typography.on']) {
    $text = n__typography ($text);
  }
  
  $text = n__unisolate ($text);
  
  return $text;
  
}



function n__render_group ($class, $group) {
  
  #print_r ($group);
  #echo '<br />';

  if (!$class) return;
  
  $simple_group_classes = array (
    'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p'
  );

  if ($class == 'empty') {

    return '';

  } elseif (function_exists ('n__render_group_'. $class)) {
  
    return call_user_func ('n__render_group_'. $class, $group);
    
  } else {
  
    if (in_array ($class, $simple_group_classes)) {
      $ot = $ct = $class;
    } else {
      $ot = 'p neasden:class="'. $class .'"';
      $ct = 'p';
    }
    
    $lines_content = array ();
    foreach ($group as $line) {
      $lines_content[] = $line['content'];
    }
    $lines_content = implode ('<br />'."\n", $lines_content);
    
    return "<". $ot .">". $lines_content ."</". $ct .">\n";


  }
  
}



// return a group class by it’s current running definition
function n__matching_group ($rdef) {
  global $_neasden_groups, $_neasden_config, $_neasden_used_groups;
  foreach ($_neasden_groups as $group_class => $group_regex) {
    if (
      !@in_array ($group_class, $_neasden_config['banned-groups']) and
      preg_match ('/^'. $group_regex .'$/', $rdef)
    ) {
      $_neasden_used_groups[] = $group_class;
      return $group_class;
    }
  }
}



function n__parse_group_line ($line) {
  global $_neasden_config, $_neasden_line_classes;

  $line = rtrim ($line);
  
  $result = array (
    'content' => $line,
    'quote-level' => 0,
    'class' => 'p',
    'class-data' => null,
  );
  
  if (strlen ($line) == 0) {
    $result['class'] = 'empty';
    return $result;
  }
  
  // headings
  $line_hashless = ltrim ($line, $_neasden_config['char-headings']);
  $heading_level = strlen ($line) - strlen ($line_hashless);
  if ($heading_level > 0 and $line_hashless[0] == ' ') {
    $result['content'] = ltrim ($line_hashless, ' ');
    $result['class'] = 'h'. min ($heading_level, N_MAX_H_LEVEL);
    return $result;
  }
  
  /*
  // ordered list items
  $line_numberless = ltrim ($line, '0123456789');
  $line_number = substr ($line, 0, strlen ($line) - strlen ($line_numberless));
  if ($line_number != '' and substr ($line_numberless, 0, 2) == '. ') {
    if ($c = ltrim (substr ($line_numberless, 1), ' ')) {
      $result['content'] = $c;
      $result['class'] = 'ol-item';
      $result['class-data'] = $line_number;
      return $result;
    }
  }
  
  // unordered list items
  if (strstr ($_neasden_config['chars-ul-items'], $line[0])) {
    if ($c = ltrim (substr ($line, 1), ' ' . $line[0])) {
      $result['content'] = $c;
      $result['class'] = 'ul-item';
      return $result;
    }
  }
  //*/

#  } elseif (function_exists ('n__render_group_'. $class)) {
#    return call_user_func ('n__render_group_'. $class, $group);

  
  // other classes
  foreach ($_neasden_line_classes as $class => $regex) {
    $regex = '/^(?:'. $regex .')$/isu';
    if (preg_match ($regex, $line, $matches)) {
      if (
        !function_exists ('n__detect_class_'. $class)
        or call_user_func ('n__detect_class_'. $class, $line)
      ) {
        $result['class'] = $class;
        $result['class-data'] = $matches;
        return $result;
      }
    }
  }
  
  return $result;
  
}



function n__groups ($text) {
  global $_neasden_config, $_neasden_groups;

  $text = str_replace ("\r\n", "\n", $text); 
  $text = str_replace ("\r", "\n", $text); 
  $src_lines = explode ("\n", $text);
  $src_lines[] = '';

  $prev_quote_level = 0;

  $prev_spaceshift = 0;
  $depths_spaceshifts = array (0);
  $depth = 0;
  
  $list_levels = array ();
 
  $last_group_class = N_DEFAULT_GROUP;
 
  $groups = array ();
  $good_buffer = array ();
  
  $rdef = '';

  foreach ($src_lines as $src_line) {
  
    // quote level
    $line_quoteless = ltrim ($src_line, $_neasden_config['char-quotes']);
    $quote_level = strlen ($src_line) - strlen ($line_quoteless);
    $src_line = $line_quoteless;
    $quote_level_changed = ($prev_quote_level != $quote_level);
    $quote_level_inc = max (0, $quote_level - $prev_quote_level);
    $quote_level_dec = max (0, $prev_quote_level - $quote_level);
    $prev_quote_level = $quote_level;

    // analize spaceshifts and depth
    $line = ltrim ($src_line, ' ');
    $spaceshift = strlen ($src_line) - strlen ($line);
    if ($spaceshift > $prev_spaceshift) {
      $depth ++;
      $depths_spaceshifts[] = $spaceshift;
    }
    if ($spaceshift < $prev_spaceshift) {
      $new_depth = 0;
      foreach ($depths_spaceshifts as $depth_spaceshift) {
        if ($spaceshift > $depth_spaceshift) {
          $new_depth ++;
        } else {
          $spaceshift = $depth_spaceshift;
          break;
        }
      }
      while ($depth > $new_depth) {
        $depth --;
        array_pop ($depths_spaceshifts);
      }
    }
    $prev_spaceshift = $spaceshift;

    // parse and match line groups
    
    $line = n__parse_group_line ($line);
    $line['result'] = '';
    $line['depth'] = $depth;
    $rdef .= '-'. $line['class'] .'-';
        
    $line['debug'] = implode ("-\n-", explode ('--', $rdef));

    $match_found = false;

    if ($group_class = n__matching_group ($rdef) and !$quote_level_changed) {
      $last_group_class = $group_class;
      $match_found = true;
      $good_buffer[] = $line;
    }
    
    #$line['debug'] .= "\n".'gbuf: '. $good_buffer[0]['class'];
    #$line['debug'] .= "\n".'gbuf: '. count ($good_buffer);

    if ($quote_level_changed or !$match_found) {

      if ($quote_level_changed) {
        $line['debug'] .= "\n".'qlc ';
      }

      if (!$match_found) {
        $line['debug'] .= "\n".'nomatch ';
      }
      
      #print_r ($last_group_class);
      #echo '<br>';
      
      $line['result'] = n__render_group ($last_group_class, $good_buffer);

      for ($i=0; $i<$quote_level_inc; $i++) $line['result'] .= '<blockquote>'."\n";
      for ($i=0; $i<$quote_level_dec; $i++) $line['result'] .= '</blockquote>'."\n";
      
      // now the widow line should be processed as part of next group

      $good_buffer = array ($line);
      $rdef = '-'. $line['class'] .'-';
      $last_group_class = n__matching_group ($rdef) or $last_group_class = N_DEFAULT_GROUP;
      
    }
    
    $groups[] = $line;
    
  }

  $another_line['result'] = n__render_group ($last_group_class, $good_buffer);
  $groups[] = $another_line;

  return $groups;

}




// return a fragment strength for an html element

function n__element_strength ($element) {
  global $_neasden_config;
  /*
  if (array_key_exists ($element, $_neasden_config['fragment-strengths'])) {
    return $_neasden_config['fragment-strengths'][$element];
  }
  */
  if (strstr (' '. $_neasden_config['html.elements.sacred'] .' ', ' '. $element .' ')) {
    return N_FRAG_STRENGTH_SACRED;
  }
  if (strstr (' '. $_neasden_config['html.elements.opaque'] .' ', ' '. $element .' ')) {
    return N_FRAG_STRENGTH_OPAQUE;
  }
  return N_FRAG_STRENGTH_TEXT;
}



// return a clean html element name given its html representation
// e. g. '<P Class=some>' -> 'p'

function n__element_name ($text) {
  if ($text[0] != '<') return;
  if ($text[strlen ($text) - 1] != '>') return;
  $text = ltrim (substr ($text, 1, -1)) . ' ';
  $text = substr ($text, 0, strpos ($text, ' '));
  return strtolower (rtrim ($text));
}



// splits the full text into fragments which can be treated
// completely indepentenly, and then treats them so

function n__split_fragments ($text) {
  global $_neasden_config;

  $machine = array (
    'text' => array (
      '<' => 'tag',
    ),
    'tag' => array (
      '>' => 'text',
      "'" => 'attr-s',
      '"' => 'attr-d',
    ),
    'attr-s' => array (
      "'" => 'tag',
    ),
    'attr-d' => array (
      '"' => 'tag',
    ),
    'comment' => array (),
  );
  
  $l = strlen ($text);
  $r = '';
  $state = 'text';
  $prevstate = 'text';
  $tagstack = array ();
  $fragments = array ();
  $thisfrag = array ('content' => '', 'strength' => -1);
  $current_el = '';
  
  for ($i = 0; $i < $l; $i ++ ) {
  
    $c = $text[$i];
    $r .= $c;
    
    // auto manage state machine
    if (array_key_exists ($c,  $machine[$state])) {
      $state = $machine[$state][$c];
    }

    // html comments: manually manage states
    if ($state == 'tag' and $r == '<!--') {
      $state = 'comment';
      if ($thisfrag['content']) {
        $fragments[] = $thisfrag;
      }
      $thisfrag = array ('content' => $r, 'strength' => -1);
      $r = '';
    }
    if ($state == 'comment' and substr ($r, -3, 3) == '-->') { 
      $state = 'text';
      $thisfrag['content'] .= $r;
      $thisfrag['strength'] = N_FRAG_STRENGTH_SACRED;
      if ($thisfrag['content']) {
        $fragments[] = $thisfrag;
      }
      $thisfrag = array ('content' => '', 'strength' => -1);
      $r = '';
    }
    
    // state change
    if ($state != $prevstate) {
      if ($prevstate == 'text' and $state == 'tag') {

        // state changes from text to tag,
        // so commit all previous text to this fragment
        // start a new run with a '<'
        // and then just see how it goes from there
        $thisfrag['content'] .= substr ($r, 0, -1);
        $thisfrag['strength'] = n__element_strength ($current_el);
        $r = substr ($r, -1, 1);
        
      } elseif ($prevstate == 'tag' and $state == 'text') {

        $tagname = n__element_name ($r);

        if ($tagname[0] != '/') {
        
          // open tag

          if (
            n__element_strength ($tagname) > $thisfrag['strength']
          ) {

            // new fragment is stronger,
            // so commit this fragment to fragments, start a new fragment
            if ($thisfrag['content']) {
              $fragments[] = $thisfrag;
            }
            $thisfrag = array ('content' => $r, 'strength' => -1);
            
          } else {
          
            $thisfrag['content'] .= $r;
            //$thisfrag['content'] .= n__isolate ($r);
            
          }
            
          $tagstack[] = $tagname;
          $current_el = $tagname;
          $r = '';
          
        } else {

          // close tag
          $tagname = substr ($tagname, 1);

          if (in_array ($tagname, $tagstack)) {
          
            $strength_before = n__element_strength ($current_el);
            
            // so tag is in stack, so we force close it
            while (array_pop ($tagstack) != $tagname);
            // if anything remains in the stack, that’s new current tag
            if (count ($tagstack) > 0) {
              $current_el = $tagstack [count ($tagstack) - 1];
            } else {
              $current_el = '';
            }
            
            if (n__element_strength ($current_el) < $strength_before) {

              // so we are now off sacred elements, 
              // so finish and append this fragment, start new fragment
              $thisfrag['content'] .= $r ."\n";
              //$thisfrag['content'] .= n__isolate ($r);
              $fragments[] = $thisfrag;
              $thisfrag = array ('content' => '', 'strength' => -1);
              $r = '';
              
            }
            
          } else {

            if (
              strstr (' '. $_neasden_config['html.elements.sacred'] .' ', ' '. $tagname .' ') or
              strstr (' '. $_neasden_config['html.elements.opaque'] .' ', ' '. $tagname .' ')
            ) {
  
              // closing tag makes no sense, it wasn’t open

              // so end whatever fragments we have
              if ($thisfrag['content']) {
                $fragments[] = $thisfrag;
              }

              // make a new sacred fragment of this weird tag
              $fragments[] = array (
                'content' => $r,
                'strength' => N_FRAG_STRENGTH_SACRED,
              );

              // and start new fragment
              $thisfrag = array ('content' => '', 'strength' => -1);
              $r = '';
            }
          }
        }
        
      }
    }

    $prevstate = $state;

  }
  
  $thisfrag['content'] .= $r;
  $thisfrag['strength'] = n__element_strength ($current_el);
  $r = '';
  
  if ($thisfrag['content']) {
    $fragments[] = $thisfrag;
  }

  return $fragments;
  
}



function n__format_fragments ($text) {
  global $_neasden_config, $_neasden_intent;

  // remove html if necessary
  if (!$_neasden_config['html.on']) {
    $text = str_replace ('<', '&lt;', $text);
    #$text = str_replace ('>', '&gt;', $text);
  }
    
  // dirty split
  $initial_fragments = n__split_fragments ($text);

  // process initial fragments
  $resulting_fragments = array ();  
  foreach ($initial_fragments as $initial_fragment) {
   
    // if explaining, borough the initial
    // explanation to result
    if ($_neasden_intent == 'explain') {
      $resulting_fragment = $initial_fragment;
    }
    
    $resulting_fragment['result'] = $initial_fragment['content'];

    // text fragments should be formatted
    if (
      $_neasden_config['with-groups'] and
      $initial_fragment['strength'] == N_FRAG_STRENGTH_TEXT
    ) {

      $resulting_fragment['result'] = '';
      $resulting_fragment['processing'] = array ();

      foreach (n__groups ($initial_fragment['content']) as $group) {
        $resulting_fragment['processing'][] = $group;
        $resulting_fragment['result'] .= $group['result'];
      }
      
    }
  

    // opaque fragments should be typographed
    if ($initial_fragment['strength'] < N_FRAG_STRENGTH_SACRED) {
      $resulting_fragment['result'] = n__process_opaque_fragment ($resulting_fragment['result']);
    }

    $resulting_fragments[] = $resulting_fragment;
    
  }
  
  return $resulting_fragments;
  
}


function neasden ($object) {
  global $_default_config, $_neasden_config, $_neasden_intent, $_neasden_resources, $_neasden_links, $_neasden_used_groups;
  
  $text = $object['text-original'];
  $profile = @$object['profile-name'] or $profile = '';
  
  $last_mb_encoding = mb_internal_encoding ();
  mb_internal_encoding ('utf-8');

  if (@$object['explain']) $_neasden_intent = 'explain';
  
  $_neasden_resources = array ();

  if ($profile and @$_default_config['__profiles'][$profile]) {
    $_neasden_config = array_merge ($_default_config, $_default_config['__profiles'][$profile]);
  }
  
  $text_final = '';

  $explanation = '';
  $explanation .= '<style>';
  $explanation .= 'table.neasden-explanation { font-size: 85%; background: #f0f0f0 }';
  $explanation .= 'table.neasden-explanation td { border-top: 1px #ccc solid; padding: 2px 8px 2px 2px }';
  $explanation .= 'table.neasden-explanation tr.frag td { border-top: 2px #000 solid }';
  $explanation .= '</style>';
  $explanation .= '<table class="neasden-explanation" cellspacing="0" cellpadding="0" border="0">';
  $explanation .= '<tr valign="top">';
  $explanation .= '<td><tt><b>frags and groups</b></tt></td>';
  $explanation .= '<td><tt><b>processing</b></tt></td>';
  $explanation .= '<td><tt><b>result</b></tt></td>';
  $explanation .= '</tr>';
  
  foreach (n__format_fragments ($text) as $frag) {
  
    $text_final .= $frag['result'];
    
    if ($_neasden_intent == 'explain') {

      $color = '#f00';
      if ($frag['strength'] == N_FRAG_STRENGTH_TEXT) $color = '#080';
      if ($frag['strength'] == N_FRAG_STRENGTH_OPAQUE) $color = '#00a';
      if ($frag['strength'] == N_FRAG_STRENGTH_SACRED) $color = '#000';
      
      $explanation .= '<tr valign="top" class="frag">';
      $explanation .= '<td style="background: #ffc; color: '. $color .'"><tt>['. htmlspecialchars ($frag['content']) .']</tt></td>';
      
      if (is_array (@$frag['processing'])) {
        $explanation .= '<td><tt>see below ↓</tt></td>';
      } else {
        $explanation .= '<td><tt>['. @print_r ($frag['debug'], true) .']</tt></td>';
      }
      $explanation .= '<td><tt>['. htmlspecialchars ($frag['result']) .']</tt></td>';
      $explanation .= '</tr>';
  
      if (is_array (@$frag['processing'])) {
        foreach ($frag['processing'] as $group) {
          $explanation .= '<tr valign="top">';
          $explanation .= '<td><tt>['. @htmlspecialchars  ($group['content']) .']</tt></td>';
          $explanation .= '<td><tt>['. @str_repeat ('>', $group['depth']) .''. @$group['class'] .' ('. @$group['class-data'] .')<br />'. @print_r ($group['debug'], true) .']</tt></td>';
          $explanation .= '<td><tt>['. @htmlspecialchars  ($group['result']) .']</tt></td>';
          $explanation .= '</tr>';
        }
      }

    }
    
  }

  $explanation .= '</table>';

  $preresult = '';

  /*
  foreach (array_unique ($_neasden_links) as $link) {
  
    if (substr ($link, -3) == '.js') {
      $preresult .= '<script src="'. $link .'"></script>'. "\n";
    }
    if (substr ($link, -4) == '.css') {
      $preresult .= '<style type="text/css" >@import url('. $link .'); </style>'. "\n";
    }

  }
  */

  $text_final = $preresult . $text_final;
  
  
  mb_internal_encoding ($last_mb_encoding);
  
  $result = array (
    'text-final' => $text_final,
    'explanation' => $explanation,
    'groups-used' => array_unique ($_neasden_used_groups),
    'links-required' => array_unique ($_neasden_links),
    'resources-detected' => $_neasden_resources,
  );

  return $result;

}


return n__init ();


?>