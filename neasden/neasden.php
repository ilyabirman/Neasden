<?

define ('N_FRAG_STRENGTH_TEXT', 0); // grouped, typographed
define ('N_FRAG_STRENGTH_OPAQUE', 7); // typographed
define ('N_FRAG_STRENGTH_SACRED', 9); // returned as is

define ('HEL_SPECIAL_CHAR', "\x1");
//define ('HEL_SPECIAL_CHAR', "+");
define ('HEL_SPECIAL_SEQUENCE_LENGTH', 6);

define ('HEL_TAG', '\\' . HEL_SPECIAL_CHAR .'\d{'. HEL_SPECIAL_SEQUENCE_LENGTH .'}\\' . HEL_SPECIAL_CHAR);

define ('HEL_TAGS', '(?:'. HEL_TAG .')*');

define ('N_MAX_H_LEVEL', 6);
define ('N_DEFAULT_GROUP', 'p');

require 'config.php';

if (array_key_exists ('__overload', $_neasden_config)) {
  if (is_file ($_neasden_config['__overload'])) {
    $_default_config = $_neasden_config;
    require $_neasden_config['__overload'];
    $_neasden_config = array_merge ($_default_config, $_neasden_config);
    
    // now use this as a basis for profiles
    $_default_config = $_neasden_config;
  }
}

$_neasden_required_line_classes = array ();

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
    $_neasden_line_classes,
    $_neasden_required_line_classes;

  include 'languages/'. $_neasden_config['language'] .'.php';

  foreach ($_neasden_config['__extensions'] as $ext) {
    include 'extensions/'. $ext .'.php';
  }
  
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



function n__special_sequence ($index) {
  return HEL_SPECIAL_CHAR . str_pad ($index, HEL_SPECIAL_SEQUENCE_LENGTH, '0', STR_PAD_LEFT) . HEL_SPECIAL_CHAR;
}



function n__save_tag ($tag) {
  //return $tag;
  global $_neasden_saved_tags;
  $index = count ($_neasden_saved_tags);
  if (is_array ($tag)) $tag = $tag[0];
  $_neasden_saved_tags[$index] = $tag;
  return n__special_sequence ($index);

}



function n__restore_tags ($text) {

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
  $text = preg_replace (
    '/((^|\s|\-)'. HEL_TAGS .')'.
    preg_quote ($char).
    '(?!'. HEL_TAGS .'($|\-|\s))/m',
    '$1'. $enclosures[0],
    $text
  );
  
  $text = preg_replace (
    '/(?<!^|\s|\-)('. HEL_TAGS .
    preg_quote ($char).
    ')(?='. HEL_TAGS ."($|\-|\s))/m",
    '$2'. $enclosures[3],
    $text
  );

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



// replacements, quotes, dashes, no-break spaces
// input text must be netto:
// no html entities, just actual utf-8 chars
  
function n__typography ($text) {
  global $_neasden_config, $_neasden_language;

  $nbsp = " ";
  
  $quotes = $_neasden_language['quotes'];
  $dash = $_neasden_language['dash'];

  //$span_tsp = n__save_tag ('<span class=\"tsp\">'. $nbsp .'</span>');
  $nobr_in = n__save_tag ('<nobr>');
  $nobr_out = n__save_tag ('</nobr>');

  #echo htmlspecialchars ($text);
  #die;

  $text = preg_replace_callback ('/(?:\<[^\>]+\>)/isxu', 'n__save_tag', $text);

  #echo htmlspecialchars ($text);
  #die;
  
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
  

  $b_in = n__save_tag ('<b>');
  $b_out = n__save_tag ('</b>');
  $i_in = n__save_tag ('<i>');
  $i_out = n__save_tag ('</i>');

  /*
  $text = n__enclose_within_tagless ($text, '*', array ($b_in, $i_in, $i_out, $b_out));
  $text = n__enclose_within_tagless ($text, '_', array ($i_in, $b_in, $b_out, $i_out));
  */
  
  // italics
  $text = preg_replace (
    '/(?:\_(?=\S)(.*?)\_)|(?:\_(.*?)(?<=\S)\_)/isu',
    $i_in . '$1$2' . $i_out,
    $text
  );

  // bold
  $text = preg_replace (
    '/(?:\*(?=\S)(.*?)\*)|(?:\*(.*?)(?<=\S)\*)/isu',
    $b_in . '$1$2' . $b_out,
    $text
  );
  
  // dash
  $text = preg_replace (
    '/(?<=^| |'. preg_quote ($nbsp) .')('. HEL_TAGS .')\-('. HEL_TAGS .')(?= |$)/m',
    '$1'. $dash .'$2',
    $text
  );

  // space before dash
  $text = preg_replace ('/ ('. HEL_TAGS .')'. preg_quote ($dash) .'/', $nbsp .'$1'. $dash, $text);

  // unions and prepositions
  if (1) {
    //die ($text);
    if ($nobreak_fw = $_neasden_language['with-next']) {
      $text = preg_replace (
        "/".
        "(?<!\pL|\-)".    // not-a—Unicode-letter-or-dash lookbehind
        $nobreak_fw .     // a preposition
        "(". HEL_TAGS .")".
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
        "(". HEL_TAGS .")".
        $nobreak_bw .    // a particle
        "(?!\pL|\-)".    // not-a—Unicode-letter-or-dash lookforward
        "/isu",      
        $nbsp .'$1$2',
        $text
      );
    }
  }


  $text = n__restore_tags ($text);

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
  
  if ($_neasden_config['with-typography']) {
    $text = n__typography ($text);
  }
  
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
  global $_neasden_groups, $_neasden_config;
  foreach ($_neasden_groups as $group_class => $group_regex) {
    if (
      !@in_array ($group_class, $_neasden_config['banned-groups']) and
      preg_match ('/^'. $group_regex .'$/', $rdef)
    ) {
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
  if (in_array ($element, $_neasden_config['sacred-elements'])) {
    return N_FRAG_STRENGTH_SACRED;
  }
  if (in_array ($element, $_neasden_config['opaque-elements'])) {
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
            //$thisfrag['content'] .= n__save_tag ($r);
            
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
              //$thisfrag['content'] .= n__save_tag ($r);
              $fragments[] = $thisfrag;
              $thisfrag = array ('content' => '', 'strength' => -1);
              $r = '';
              
            }
            
          } else {

            if (
              in_array ($tagname, $_neasden_config['opaque-elements']) or
              in_array ($tagname, $_neasden_config['sacred-elements'])
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
  if (!$_neasden_config['with-html']) {
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



function neasden_explain ($text) {
  global $_neasden_intent;
  
  $_neasden_intent = 'explain';
 
  $result = '';
  
  $result .= '<style>';
  $result .= 'table.neasden-explanation { font-size: 85%; background: #f0f0f0 }';
  $result .= 'table.neasden-explanation td { border-top: 1px #ccc solid; padding: 2px 8px 2px 2px }';
  $result .= 'table.neasden-explanation tr.frag td { border-top: 2px #000 solid }';
  $result .= '</style>';
  
  $result .= '<table class="neasden-explanation" cellspacing="0" cellpadding="0" border="0">';
  
  $result .= '<tr valign="top">';
  $result .= '<td><pre><b>frags and groups</b></pre></td>';
  $result .= '<td><pre><b>processing</b></pre></td>';
  $result .= '<td><pre><b>result</b></pre></td>';
  $result .= '</tr>';
  
  foreach (n__format_fragments ($text) as $frag) {

    $color = '#f00';
    if ($frag['strength'] == N_FRAG_STRENGTH_TEXT) $color = '#080';
    if ($frag['strength'] == N_FRAG_STRENGTH_OPAQUE) $color = '#00a';
    if ($frag['strength'] == N_FRAG_STRENGTH_SACRED) $color = '#000';
    
    $result .= '<tr valign="top" class="frag">';
    $result .= '<td style="background: #ffc; color: '. $color .'"><pre>['. htmlspecialchars ($frag['content']) .']</pre></td>';
    
    /*
    $result .= '<td>';
    $result .= '<table cellspacing="0" cellpadding="4" border="1" style="border-color: #fefefe">';
    
    $result .= '</table>';
    
    $result .= '</td>';
    
    */
    
    if (is_array (@$frag['processing'])) {
      $result .= '<td><pre>see below ↓</pre></td>';
    } else {
      $result .= '<td><pre>['. @print_r ($frag['debug'], true) .']</pre></td>';
    }
    $result .= '<td><pre>['. htmlspecialchars ($frag['result']) .']</pre></td>';
    $result .= '</tr>';

    if (is_array (@$frag['processing'])) {
      foreach ($frag['processing'] as $group) {
        $result .= '<tr valign="top">';
        $result .= '<td><pre>['. @htmlspecialchars  ($group['content']) .']</pre></td>';
        $result .= '<td><pre>['. @str_repeat ('>', $group['depth']) .''. @$group['class'] .' ('. @$group['class-data'] .')<br />'. @print_r ($group['debug'], true) .']</pre></td>';
        $result .= '<td><pre>['. @htmlspecialchars  ($group['result']) .']</pre></td>';
        $result .= '</tr>';
      }
    }

    
  } 

  $result .= '</table>';
  
  return $result;

}



function neasden_detect ($text) {
  global $_default_config, $_neasden_config, $_neasden_intent;

  $_neasden_intent = 'detect';
}



function neasden ($text, $profile = '') {
  global $_default_config, $_neasden_config, $_neasden_intent;

  $_neasden_intent = 'render'; 

  if ($profile and @$_default_config['__profiles'][$profile]) {
    $_neasden_config = array_merge ($_default_config, $_default_config['__profiles'][$profile]);
  }
  
  #echo '<pre>';
  #print_r ($_neasden_config);

  $result = '';
  foreach (n__format_fragments ($text) as $frag) {
    $result .= $frag['result'];
  }

  return $result;

}


return n__init ();


?>