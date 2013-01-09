<?

class NeasdenGroup_audio implements NeasdenRenderableGroup {
  
  private $neasden = null;

  function __construct ($neasden) {
    $this->neasden = $neasden;

    $neasden->define_line_class (
      'audio',
      '(?:\[play\])(.*)'
    );
    
    $neasden->define_group ('audio', '(-audio-)');

  }

  function render ($group, $myconf) {

    $this->neasden->require_link (@$this->neasden->config['library']. 'jquery/jquery.js');
    $this->neasden->require_link (@$this->neasden->config['library']. 'jouele/jquery.jplayer.min.js');
    $this->neasden->require_link (@$this->neasden->config['library']. 'jouele/jouele.css');
    $this->neasden->require_link (@$this->neasden->config['library']. 'jouele/jouele.js');
    
    $css_class = $this->neasden->config['groups.generic-css-class'];
    if (@$myconf['css-class']) $css_class = @$myconf['css-class'];
    
    $downloadstr = 'Download';
    if ($this->neasden->config['language'] == 'ru') $downloadstr = 'Скачать';
  
    $p = false;
  
    $result = (
      '<div class="'. $css_class .'">'."\n"
    );
    
    foreach ($group as $line) {
    
      @list ($href, $alt) = explode (' ', trim ($line['class-data'][1]), 2); // usafe
      if (!$alt) $alt = basename ($href);
      $zoneid = rand (1000, 9999);
  
      $player_html = '<a '.
        'class="jouele" '.
        'href="'. $href .'" '.
        'title="'. $downloadstr .'" '.
      '>'. $alt .'</a>'."\n";
      
      $player_html = $this->neasden->isolate ($player_html);
  
      $result .= $player_html;
      
    }
  
    if ($p) $result .= '</p>'."\n";
  
    $result .= '</div>'."\n";
  
    return $result;
    
  }
  
}

?>