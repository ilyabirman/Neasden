<?

  define ('HEL_VERSION', '2');
  
  
  // replacements, quotes, dashes, no-break spaces
  // input text must be netto:
  // no html entities, just actual utf-8 chars
    
  function hel2_kavychki ($text) {
    global $_neasden_config, $_neasden_language;

    $nbsp = " ";
    
    $quotes = $_neasden_language['quotes'];
    $dash = $_neasden_language['dash'];

    //$span_tsp = n__save_tag ('<span class=\"tsp\">'. $nbsp .'</span>');
    $nobr_in = n__save_tag ('<nobr>');
    $nobr_out = n__save_tag ('</nobr>');

    //echo $text;
    //die;

    $text = preg_replace_callback ('/(?:\<[^\>]+\>)/isxu', 'n__save_tag', $text);

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

    
    /*
    
    // obvious quotes
    $text = preg_replace (
      '/((^|\s|\-)'. HEL_TAGS .')\"(?!'. HEL_TAGS .'($|\-|\s))/m',
      '$1'. $quotes[0],
      $text
    );
    
    $text = preg_replace (
      '/(?<!^|\s|\-)('. HEL_TAGS .'\")(?='. HEL_TAGS ."($|\-|\s))/m",
      '$2'. $quotes[3],
      $text
    );

    // remaining quotes
    if (1) {
      $len = strlen ($quotes[0]);
      $qdepth = 0;
      for ($i = 0; $i < strlen ($text)-1; ++ $i) {
        $scan = substr ($text, $i, $len);
        if ($scan == $quotes[0]) {
          $qdepth ++;
          if ($qdepth > 1) $text = substr ($text, 0, $i) . $quotes[1] . substr ($text, $i + $len);
          $i += $len;
        }
        if ($scan == $quotes[3]) {
          if ($qdepth > 1) $text = substr ($text, 0, $i) . $quotes[2] . substr ($text, $i + $len);
          $qdepth --;
          $i += $len;
        }
        if ($i > strlen ($text)-1) break;
        if ($text[$i] == '"') {
          if ($qdepth > 0) {
            if ($qdepth > 1)
              $text = substr ($text, 0, $i) . $quotes[2] . substr ($text, $i + 1);
            else
              $text = substr ($text, 0, $i) . $quotes[3] . substr ($text, $i + 1);
            -- $qdepth;
          } else {
            $text = substr ($text, 0, $i) . $quotes[0] . substr ($text, $i + 1);
            ++ $qdepth;
          }
        }
      }
    }                                                
    
    */
    
    
    // dash
    if (1) {
      $text = preg_replace ('/ \- /m', ' '. $dash .' ', $text);
      $text = preg_replace ('/^('. HEL_TAGS .')\- /m', '$1'. $dash .' ', $text);
      $text = preg_replace ('/ \-('. HEL_TAGS .')$/m', $nbsp . $dash. '$1', $text);
    }


    // unions and prepositions
    if ($nobreak_fw = $_neasden_language['with-next']) {
      $text = preg_replace (
        "/".
        "(?<!\pL|\-)".    // not-a—Unicode-letter-or-dash lookbehind
        $nobreak_fw .     // a preposition
        HEL_TAGS.
        "\s".             // and a space
        "/isu",      
        '$1$2'. $nbsp,
        $text
      );
    }

    if ($nobreak_bw = $_neasden_language['with-prev']) {
      $text = preg_replace (
        "/".
        "\s".            // a space
        HEL_TAGS.
        $nobreak_bw .    // a particle
        "(?!\pL|\-)".    // not-a—Unicode-letter-or-dash lookforward
        "/isu",      
        $nbsp .'$1$2',
        $text
      );
    }
    /*

    // пушкин а. с.
    $text = preg_replace (
      "/([A-ZА-Я]\.)(". HEL_TAG .")? *(". HEL_TAG .")?([A-ZА-Я]\.)(". HEL_TAG .")? *(". HEL_TAG .")?([A-ZА-Я][a-zа-я]+)/u",
      $nobr_in .'$1$2&nbsp;$3$4$5&nbsp;$6$7'. $nobr_out,
      $text
    );

    $text = preg_replace (
      "/([A-ZА-Я][a-zа-я]+)(". HEL_TAG .")? +(". HEL_TAG .")?([A-ZА-Я]\.)(". HEL_TAG .")? *(". HEL_TAG .")?([A-ZА-Я]\.)/u",
      $nobr_in .'$1$2&nbsp;$3$4$5&nbsp;$6$7'. $nobr_out,
      $text
    );

    */


    $text = n__restore_tags ($text);

    return $text;

  }
   
?>