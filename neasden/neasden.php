<?

define ('N_BLOCK_STRENGTH_TEXT', 0); // formatted, typographed
define ('N_BLOCK_STRENGTH_OPAQUE', 7); // typographed
define ('N_BLOCK_STRENGTH_SACRED', 9); // returned as is

define ('HEL_SPECIAL_CHAR', "\x1");
//define ('HEL_SPECIAL_CHAR', "+");
define ('HEL_SPECIAL_SEQUENCE_LENGTH', 6);

define ('HEL_TAG', '\\' . HEL_SPECIAL_CHAR .'\d{'. HEL_SPECIAL_SEQUENCE_LENGTH .'}\\' . HEL_SPECIAL_CHAR);

define ('HEL_TAGS', '('. HEL_TAG .')*');


define ('N_MAX_H_LEVEL', 6);

require 'config.php';
require 'helicon2.php';

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

$_neasden_explaining = false;



function n__init () {
  global
    $_neasden_config,
    $_neasden_language,
    $_neasden_line_classes,
    $_neasden_required_line_classes;

  include 'languages/'. $_neasden_config['language'] .'.php';

  foreach ($_neasden_config['extensions-list'] as $ext) {
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



function n__tag_name ($text) {
  if ($text[0] != '<') return;
  if ($text[strlen ($text) - 1] != '>') return;
  $text = ltrim (substr ($text, 1, -1)) . ' ';
  $text = substr ($text, 0, strpos ($text, ' '));
  return strtolower (rtrim ($text));
}



// return 'text', 'sacred' or 'opaque' for an html element
function n__element_strength ($element) {
  global $_neasden_config;
  if (array_key_exists ($element, $_neasden_config['block-strengths'])) {
    return $_neasden_config['block-strengths'][$element];
  }
  return N_BLOCK_STRENGTH_TEXT;
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




function n__split_blocks ($text) {
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
  $blocks = array ();
  $thisblock = array ('content' => '', 'strength' => -1);
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
      if ($thisblock['content']) {
        $blocks[] = $thisblock;
      }
      $thisblock = array ('content' => $r, 'strength' => -1);
      $r = '';
    }
    if ($state == 'comment' and substr ($r, -3, 3) == '-->') { 
      $state = 'text';
      $thisblock['content'] .= $r;
      $thisblock['strength'] = N_BLOCK_STRENGTH_SACRED;
      if ($thisblock['content']) {
        $blocks[] = $thisblock;
      }
      $thisblock = array ('content' => '', 'strength' => -1);
      $r = '';
    }
    
    // state change
    if ($state != $prevstate) {
      if ($prevstate == 'text' and $state == 'tag') {

        // state changes from text to tag,
        // so commit all previous text to this block
        // start a new run with a '<'
        // and then just see how it goes from there
        
        $thisblock['content'] .= substr ($r, 0, -1);
        $thisblock['strength'] = n__element_strength ($current_el);
        $r = substr ($r, -1, 1);
        
      } elseif ($prevstate == 'tag' and $state == 'text') {

        $tagname = n__tag_name ($r);

        if ($tagname[0] != '/') {
        
          // open tag

          if (
            n__element_strength ($tagname) > $thisblock['strength']
          ) {

            // new block is stronger,
            // so commit this block to blocks, start a new block
            if ($thisblock['content']) {
              $blocks[] = $thisblock;
            }
            $thisblock = array ('content' => $r, 'strength' => -1);
            
          } else {
          
            $thisblock['content'] .= $r;
            //$thisblock['content'] .= n__save_tag ($r);
            
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
              // so finish and append this block, start new block
              $thisblock['content'] .= $r;
              //$thisblock['content'] .= n__save_tag ($r);
              $blocks[] = $thisblock;
              $thisblock = array ('content' => '', 'strength' => -1);
              $r = '';
              
            }
            
          } else {

            if (
              in_array ($tagname, array_keys ($_neasden_config['block-strengths']))
            ) {
  
              // closing tag makes no sense, it wasn’t open

              // so end whatever block we have
              if ($thisblock['content']) {
                $blocks[] = $thisblock;
              }

              // make a new sacred block of this weird tag
              $blocks[] = array (
                'content' => $r,
                'strength' => N_BLOCK_STRENGTH_SACRED,
              );

              // and start new block
              $thisblock = array ('content' => '', 'strength' => -1);
              $r = '';
            }
          }
        }
        
      }
    }

    $prevstate = $state;

  }
  
  $thisblock['content'] .= $r;
  $thisblock['strength'] = n__element_strength ($current_el);
  $r = '';
  
  if ($thisblock['content']) {
    $blocks[] = $thisblock;
  }

  return $blocks;
  
}




function n__parse_line ($line) {
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


function n__render_group ($class, $group) {

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



function n__matching_group ($rdef) {
  global $_neasden_groups;
  foreach ($_neasden_groups as $group_class => $group_regex) {
    if (preg_match ('/^'. $group_regex .'$/', $rdef)) {
      return $group_class;
    }
  }
}



function n__groups ($text) {
  global $_neasden_config, $_neasden_groups, $_neasden_explaining;

  $text = str_replace ("\r\n", "\n", $text); 
  $text = str_replace ("\r", "\n", $text); 
  $src_lines = explode ("\n", $text);
  $src_lines[] = '';

  $prev_quote_level = 0;

  $prev_spaceshift = 0;
  $depths_spaceshifts = array (0);
  $depth = 0;
  
  $list_levels = array ();
 
  $last_group_class = '';
 
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
    
    $line = n__parse_line ($line);
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
        $line['debug'] .= "\n".'qlc';
      }

      if (!$match_found) {
        $line['debug'] .= "\n".'nomatch ';
      }
      
      $line['result'] = n__render_group ($last_group_class, $good_buffer);

      for ($i=0; $i<$quote_level_inc; $i++) $line['result'] .= '<blockquote>'."\n";
      for ($i=0; $i<$quote_level_dec; $i++) $line['result'] .= '</blockquote>'."\n";
      
      // now the widow line should be processed as part of next group

      $good_buffer = array ($line);
      $rdef = '-'. $line['class'] .'-';
      $last_group_class = n__matching_group ($rdef);
      
    }
    

    $groups[] = $line;
    
  }

  $another_line['result'] = n__render_group ($last_group_class, $good_buffer);
  $groups[] = $another_line;

  return $groups;

}



// any opaque block or a text block after formatting
// should be typographed with this function

function n__block_typography ($text) {
  global $_neasden_config, $_neasden_tag_machine;
  
  // replace &laquo; with normal quote characters
  $text = str_replace (
    array_keys ($_neasden_config['nettoyer']),
    array_values ($_neasden_config['nettoyer']),
    $text
  );
  
  if ($_neasden_config['with-typography']) {
    $text = hel2_kavychki ($text);
  }
  
  return $text;
  
}



function n__format_blocks ($text) {
  global $_neasden_config, $_neasden_explaining;

  // dirty split
  $initial_blocks = n__split_blocks ($text);

  // process initial blocks
  $resulting_blocks = array ();  
  foreach ($initial_blocks as $initial_block) {

    if ($_neasden_explaining) {
      $resulting_block = $initial_block;
    }
    
    // if explaining, borough the initial
    // explanation to result
    $resulting_block['result'] = $initial_block['content'];

    // text blocks should be formatted
    if (
      $_neasden_config['format-blocks'] and
      $initial_block['strength'] == N_BLOCK_STRENGTH_TEXT
    ) {

      $resulting_block['result'] = '';
      $resulting_block['processing'] = array ();

      foreach (n__groups ($initial_block['content']) as $group) {
        $resulting_block['processing'][] = $group;
        $resulting_block['result'] .= $group['result'];
      }
      
    }

    // opaque blocks should be typographed
    if ($initial_block['strength'] < N_BLOCK_STRENGTH_SACRED) {
      $resulting_block['result'] = n__block_typography ($resulting_block['result']);
    }

    $resulting_blocks[] = $resulting_block;
    
  }
  
  return $resulting_blocks;
  
}



function neasden_explain ($text) {
  global $_neasden_explaining;
  
  $_neasden_explaining = true;
 
  $result = '';
  
  $result .= '<style>';
  $result .= 'table.neasden-explanation { font-size: 85%; background: #f0f0f0 }';
  $result .= 'table.neasden-explanation td { border-top: 1px #ccc solid; padding: 2px 8px 2px 2px }';
  $result .= 'table.neasden-explanation tr.block td { border-top: 2px #000 solid }';
  $result .= '</style>';
  
  $result .= '<table class="neasden-explanation" cellspacing="0" cellpadding="0" border="0">';
  
  $result .= '<tr valign="top">';
  $result .= '<td><pre><b>blocks and groups</b></pre></td>';
  $result .= '<td><pre><b>processing</b></pre></td>';
  $result .= '<td><pre><b>result</b></pre></td>';
  $result .= '</tr>';
  
  foreach (n__format_blocks ($text) as $block) {

    $color = '#f00';
    if ($block['strength'] == N_BLOCK_STRENGTH_TEXT) $color = '#080';
    if ($block['strength'] == N_BLOCK_STRENGTH_OPAQUE) $color = '#00a';
    if ($block['strength'] == N_BLOCK_STRENGTH_SACRED) $color = '#000';
    
    $result .= '<tr valign="top" class="block">';
    $result .= '<td style="background: #ffc; color: '. $color .'"><pre>['. htmlspecialchars ($block['content']) .']</pre></td>';
    
    /*
    $result .= '<td>';
    $result .= '<table cellspacing="0" cellpadding="4" border="1" style="border-color: #fefefe">';
    
    $result .= '</table>';
    
    $result .= '</td>';
    
    */
    
    if (is_array ($block['processing'])) {
      $result .= '<td><pre>see below ↓</pre></td>';
    } else {
      $result .= '<td><pre>['. @print_r ($block['debug'], true) .']</pre></td>';
    }
    $result .= '<td><pre>['. htmlspecialchars ($block['result']) .']</pre></td>';
    $result .= '</tr>';

    if (is_array ($block['processing'])) {
      foreach ($block['processing'] as $group) {
        $result .= '<tr valign="top">';
        $result .= '<td><pre>['. htmlspecialchars  ($group['content']) .']</pre></td>';
        $result .= '<td><pre>['. str_repeat ('>', $group['depth']) .''.$group['class'] .' ('. $group['class-data'] .')<br />'. @print_r ($group['debug'], true) .']</pre></td>';
        $result .= '<td><pre>['. htmlspecialchars  ($group['result']) .']</pre></td>';
        $result .= '</tr>';
      }
    }

    
  } 

  $result .= '</table>';
  
  $_neasden_explaining = false;

  return $result;

}



function neasden ($text) {

  $result = '';
  foreach (n__format_blocks ($text) as $block) {
    $result .= $block['result'];
  }

  return $result;

}


return n__init ();


?>