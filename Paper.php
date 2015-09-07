<?php
namespace Publish;


function idfilenamelabel($id, $name, $label, $width=0, $height=0) {
    return array(
        'width' => $width,
        'height' => $height,
        'id' => $id,
        'filename' => $name,
        'label' => $label);
}

function idlabeltex($id, $label, $tex) {
    return array('id'    => $id, 'label' => $label, 'tex'   => $tex);
}
//*****************************************************************************
class Insertion {
    # OOPS! This shares between all derived classes.  TODO FIX THIS!
    private static $number = 0;
    private static $member = array();

    public static function Label($label) {
        $number = ++Insertion::$number;
        Insertion::$member[$label] = $number;
        return $number;
    }

    public static function Init($align="center", $border=1) {
        echo '<table border="'.$border.'" width="90%" align="'.$align.'"><tr><td>'.PHP_EOL;
    }

    public static function Row($align="center") {
        echo '</td></tr><tr><td align="'.$align.'">'.PHP_EOL;
    }

    public static function Col($align="center") {
        echo '</td><td align="'.$align.'">'.PHP_EOL;
    }

    public static function Fini() {
        echo '</td></tr></table>'.PHP_EOL;
    }
}

//*****************************************************************************
class Calculation extends Insertion {
    public static function insert($label, $tex) {
        $number = Insertion::Label($label);
        Insertion::Init('left', 0);
        echo '$$'.$tex.'$$'.PHP_EOL;
        Insertion::Col('right');
        echo 'Eqn ['.$number.']'.PHP_EOL;
        Insertion::Fini();
    }
}

//*****************************************************************************
class Paper {

    private static $eqno = 0;
    private static $eqarray = array();
    private static $imgno = 0;
    private static $imgarray = array();
    private static $preno = 0;
    private static $prearray = array();

//-----------------------------------------------------------------------------
    public static function Box($text) {
        echo '<table border="1" width="95%" align="center"><tr><td>'.PHP_EOL;
        echo $text.PHP_EOL;
        echo '</td></tr></table>'.PHP_EOL;
    }

//-----------------------------------------------------------------------------
    public static function LiBox($title, $text) {
        echo '<li>'.$title.PHP_EOL;
        echo '<table border="1" width="95%" align="center"><tr><td>'.PHP_EOL;
        echo $text.PHP_EOL;
        echo '</td></tr></table>'.PHP_EOL;
        echo '</li>'.PHP_EOL;
    }

//-----------------------------------------------------------------------------
    public static function Equation($the = array(
            'tex' => 'e^{i\pi}+1 = 0',
            'id'  => '@@identity@@',
            'label' => 'Euler\'s identity'
        )
    ) {
        $tex   = $the['tex'];
        $id    = $the['id'];
        $label = $the['label'];
        Paper::$eqno++;
        Paper::$eqarray[$id] = Paper::$eqno;
        echo '<table width="100%" align="right">';
        echo '<tr><td align="left" width="70%">'.PHP_EOL;
        echo '$$'.$tex.'$$'.PHP_EOL;
        if ($label != '') {
            echo '</td><td align="right"><i>'.$label.'</i>'.PHP_EOL;
        }
        echo '</td><td align="right">'.'['.Paper::$eqno.']'.PHP_EOL;
        echo '</td></tr></table>'.PHP_EOL;
        return $id;
    }

//-----------------------------------------------------------------------------
    public static function EqReference($label) {
        echo 'Equation['.Paper::$eqarray[$label].']';
    }

//-----------------------------------------------------------------------------
    public static function Listing($label, $text, $listing) {
        Paper::$preno++;
        Paper::$prearray[$label] = Paper::$preno;
        echo '<table border="1" align="center"><tr><td align="left">'.PHP_EOL;
        echo '<pre>'.$listing.'</pre>'.PHP_EOL;
        echo '</td></tr><tr><td align="center">'.PHP_EOL;
        echo 'Listing '.Paper::$preno.': '.$text.PHP_EOL;
        echo '</td></tr></table>'.PHP_EOL;
    }

//-----------------------------------------------------------------------------
    public static function ListingReference($label) {
        echo 'Listing['.Paper::$prearray[$label].']';
    }

//-----------------------------------------------------------------------------
    public static function Figures($id, $label, $images = array()) {
        $sub = 0;
        Paper::$imgno++;
        Paper::$imgarray[$id] = Paper::$imgno;
        echo '<table width="90%" border="1" align="center">'.PHP_EOL;
        echo '<caption align="bottom">';
        echo 'Figure '.Paper::$imgno.': ';
        echo $label.'</caption>'.PHP_EOL;
        echo '<tr>'.PHP_EOL;
        foreach ($images as $image) {
            $sub++;
            $filename = $image['filename'];
            $id = $image['id'];
            $label = $image['label'];
            Paper::$imgarray[$id] = Paper::$imgno.'.'.$sub;
            $imageData = base64_encode(file_get_contents($filename));
            $src = 'data: '.mime_content_type($filename).';base64,'.$imageData;
            $label = $image['text'];
            $W = array_key_exists('width', $image) ? $image['width'] : 0;
            $H = array_key_exists('height', $image) ? $image['height'] : 0;
            //$T = array_key_exists('text', $image) ? $image['text'] : '';
            echo '<td align="center" valign="top">'.PHP_EOL;
            echo '<div>';
            echo '<img src="'.$src.'" ';
            if ($W != 0 or $H != 0) {
                echo 'width="'.$W.'" height="'.$H.'" />';
            }
            echo '</div><div>'.PHP_EOL;
            echo 'Figure '.Paper::$imgno.'.'.$sub.': '.$label.PHP_EOL;
            echo '</div>'.PHP_EOL;
            echo '</td>'.PHP_EOL;
        }
        echo '</td></tr></table>'.PHP_EOL;
    }

//-----------------------------------------------------------------------------
    public static function FigReference($label, $sub='') {
        echo 'Figure '.Paper::$imgarray[$label].'';
    }

//-----------------------------------------------------------------------------
    public static function Div($title, $level) {
        static $div = array('</div>', '<div>');
    
        assert($level > -3);
    
        $close = ($level < 0);
        if ($close) echo $div[$level == -1].PHP_EOL;
        // if ($level = -1) echo "<h3>".$title."</h3>".PHP_EOL;
        return $close;
    }
    
//-----------------------------------------------------------------------------
    public static function Section($title, $level=0) {
        static $first = array('1', 1, '1');
        static $place = array('1', 1, '1');
        static $h = array('h3', 'h4', 'h5');
        static $incr = false;
        static $last = 0;
    
        if (Paper::Div($title, $level)) return;
    
        assert($level >= 0 && $level <= 2, "Levels must be in range 0-2.");
        $diff = $level - $last;
        assert($diff <= 1, "Levels may only increase by 1.");
    
        if ($incr) {
            $place[$level]++;
        }
        $incr = true;
    
        if ($last < $level) {
            $place[$level] = $first[$level];
        }
        $last = $level;
    
        $b = '<'.$h[$level].'>Section ';
        $a = '</'.$h[$level].'>';
        echo "$b".
            implode('.', array_slice($place, 0, $level + 1)).
            ": $title$a";
        echo PHP_EOL;
    }
    
//-----------------------------------------------------------------------------
    public static function Appendix($title, $level=0) {
        static $first = array('A', 1, 'a');
        static $place = array('A', 1, 'a');
        static $h = array('h3', 'h4', 'h5');
        static $incr = false;
        static $last = 0;
    
        if (Paper::Div($title, $level)) return;
    
        assert($level >= -2 && $level <= 2, "Levels must be in range 0-2.");
        $diff = $level - $last;
        assert($diff <= 1, "Levels may only increase by 1.");
    
        if ($incr) {
            $place[$level]++;
        }
        $incr = true;
    
        if ($last < $level) {
            $place[$level] = $first[$level];
        }
        $last = $level;
    
        $b = '<'.$h[$level].'>Appendix ';
        $a = '</'.$h[$level].'>';
        echo "$b".
            implode('.', array_slice($place, 0, $level + 1)).
            ": $title$a";
        echo PHP_EOL;
    }
    
//-----------------------------------------------------------------------------
    public static function Head($data) {
        $title = $data['title'];
        if (!$title) $title = 'Untitled';
        $subtitle = $data['subtitle'];
        if (!$subtitle) $subtitle = '';
        $author = $data['author'];
        if (!$author) $author = '';
        date_default_timezone_set('US/Eastern');
        $date = date('M d Y');
        $ret  = '</html>'.PHP_EOL;
        $ret .= ' <head>'.PHP_EOL;
        $ret .= '  <title>'.$title.'</title>'.PHP_EOL;
        $ret .= '  <script type="text/javascript"'.PHP_EOL;
        $ret .= '   src="https://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML">'.PHP_EOL;
        $ret .= '  </script>'.PHP_EOL;
        $ret .= ' </head>'.PHP_EOL;
        $ret .= ' <body>'.PHP_EOL;
        $ret .= '  <h1 align="center">'.$title.'</h1>'.PHP_EOL;
        $ret .= '  <h2 align="center">'.$subtitle.'</h2>'.PHP_EOL;
        $ret .= '  <h3 align="center">'.$author.'</h3>'.PHP_EOL;
        $ret .= '  <h3 align="center">'.$date.'</h3>'.PHP_EOL;
        $ret .= '  <hr />'.PHP_EOL;
        echo $ret;
    }

//-----------------------------------------------------------------------------
    public static function Tail() {
        $ret = '</body>'.PHP_EOL;
        $ret = '</html>'.PHP_EOL;
        echo $ret;
    }
}
?>
