<?php
// php Xenofile.php > Xenofile.html
// TODO add X_table as a numberable entity.
// Probably needs PHP version 5.4
namespace Xenofile;

error_reporting(E_ALL);

/*
// TODO Fix this so it works.
$phpfile = substr($argv[0], 0, strrpos($argv[0], '.'));
$keyfile = $phpfile.".html";
print_r($keyfile."<br/>");
if (file_exists($keyfile)) {
    echo "EXIST!"."<br/>";
    $before = "Notice: Use of undefined constant ";
    $middle = " - assumed '";
    $after = "' in ";
    $token = "\w+";
    $pattern = "/{$before}{$token}{$middle}{$token}{$after}/";
    $basis = strlen("/{$before}{$middle}{$after}/");
    $keydata = file_get_contents($keyfile);
    $keywords = [];
    print_r($pattern."<br/>");
    $keycount = preg_match($pattern, $keydata, $matches);
    print_r($keycount."<br/>");
    var_dump($matches);
    if ($keycount) foreach($matches as $match) {
        var_dump($match);
        $length = (strlen($match) - $basis) / 2;
        $part1 = substr($match, strlen($before), $length);
        $part2 = substr($match, strlen($before.$middle)+$length, $length);
        assert($part1 == $part2);
        $keywords[] = $part1;
    }
    var_dump($keywords);
    X_keywords($keywords);
}
*/

$xenofile_keywords = [
    'Airy', 'align', 'args',
    'border',
    'caption',
    'Euler', 'exit',
    'filename', 'funcname',
    'gnuplot',
    'height',
    'id', 'index',
    'keyword',
    'label', 'level', 'line', 'listing',
    'png',
    'tex', 'text', 'title',
    'valign',
    'width',
];

X_keywords($xenofile_keywords);

//aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
function gossip() {
    $params = func_get_args();
    echo '<p><pre>';
    echo str_repeat('+', 80).PHP_EOL;
    foreach ($params as $param) {
        print_r($param);
        echo PHP_EOL;
    }
    $backtrace = debug_backtrace();

    echo str_repeat('1', 80).PHP_EOL;
    $parent = $backtrace[1];
    $function = "{$parent['function']}({$parent[line]})";
    echo $function.PHP_EOL;
    print_r($parent[args]).PHP_EOL;

    echo str_repeat('2', 80).PHP_EOL;
    if (count($backtrace) > 2) {
        $grand = $backtrace[2];
        $function = "{$grand['function']}({$grand[line]})";
        echo $function.PHP_EOL;
        print_r($grand[args]).PHP_EOL;
    } else {
        echo "No grandparent".PHP_EOL;
    }

    echo str_repeat('-', 80).PHP_EOL;
    echo '</pre></p>'.PHP_EOL;
    if (in_array('exit', $params)) exit(0);
}

//aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
function X_keywords($keywords = []) {
    foreach($keywords as $keyword) {
        if (defined($keyword)) {
            assert(constant($keyword) == $keyword);
        } else {
            define($keyword, $keyword);
        }
    }
}

//aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
function X_store($fragment='') {
    static $html = '';
    return $html .= $fragment;
}

//aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
function HTML($fragment='') {
    X_store($fragment);
    return $fragment;
}

//aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
function Alpha_letter($number,$upper,$alpha) {
    assert(is_int($number) and is_bool($upper));
    assert(is_array($alpha) and is_array($alpha[0]));

    if ($number  < 0) $result= '~'.Alpha_letter(-$number,$upper,$alpha);
    else if ($number == 0) $result= '&#x2610;';
    else {
        $result = '';
        $number -= 1;
        $size = count($alpha[0]);
        $result .= $alpha[$upper][intval($number % $size)];
        $number = intval($number/$size);
        while ($number > 0) {
            $result = $alpha[$upper][intval($number % $size)-1].$result;
            $number = intval($number/$size);
        }
    }
    return $result;
}

//aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
function Number($number, $zero) {
    assert(is_int($number));
    if ($number == 0) $result = sprintf("&#x%05x;", $zero);
    else if ($number  < 0) $result = '-'.Number(-$number, $zero);
    else {
        $result = '';
        while ($number != 0) {
            $digit = ($number % 10) + $zero;
            $number = intval($number/10);
            $result = sprintf("&#x%05x;", $digit).$result;
        }
    }
    return $result;
}

//bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb
function NumberAs($name=null, $number=0) {
    // http://www.fileformat.info/info/unicode/category/Nd/list.htm
    static $language = [
        'ANSI'                          => 0x30,
        'Arabic-Indic'                  => 0x660,
        'Extended Arabic-Indic'         => 0x6f0,
        #'NKO'=>0x7c0,
        'Devanagari'                    => 0x966,
        'Bengali'                       => 0x9e6,
        'Gurmukhi'                      => 0xa66,
        'Gujarati'                      => 0xae6,
        'Oriya'                         => 0xb66,
        'Tamil'                         => 0xbe6,
        'Telugu'                        => 0xc66,
        'Kannada'                       => 0xce6,
        'Malayalam'                     => 0xd66,
        #'Sinhala'=>0xde6,
        'Thai'                          => 0xe50,
        'Lao'                           => 0xed0,
        'Tibetan'                       => 0xf20,
        'Myanmar'                       => 0x1040,
        #'Myanmar Shan'=>0x1090,
        'Khmer'                         => 0x17e0,
        'Mongolian'                     => 0x1810,
        #'Limub'=>0x1946,
        #'New Tai Lue'=>0x19d0,
        #'Tai Tham Hora'=>0x1a80,
        #'Tai Tham Tham'=>0x1a90,
        #'Balinese'=>0x1b50,
        #'Sundanese'=>0x1bb0,
        #'Lepcha'=>0x1c40,
        #'Ol Chiki'=>0x1c50,
        #'Vai'=>0xa620,
        #'Saurashtra'=>0xa8d0,
        #'Kayah Li'=>0xa900,
        #'Javanese'=>0xa9d0,
        #'Myanmar Tai Laing'=>0xa9f0,
        #'Cham'=>0xaa50,
        #'Meetei Mayek'=>0xabf0,
        'Fullwidth'                     => 0xff10,
        #'Osmanya'=>0x104a0,
        #'Brahmi'=>0x11066,
        #'Sora Sompeng'=>0x110f0,
        #'Chakma'=>0x11136,
        #'Sharada'=>0x111d0,
        #'Khudawadi'=>0x112f0,
        #'Tirhuta'=>0x114d0,
        #'Modi'=>0x11650,
        #'Takri'=>0x116c0,
        #'Warang Citi'=>0x118e0,
        #'Mro'=>0x16a60,
        #'Pahawh Hmong'=>0x16b50,
        'Mathematical Bold'             => 0x1d7ce,
        'Mathematical Double-Struck'    => 0x1d7d8,
        'Mathematical Sans-Serif'       => 0x1d7e2,
        'Mathematical Sans-Serif Bold'  => 0x1d7ec,
        'Mathematical Monospace'        => 0x1d7f6,
    ];
    // Default call with no args returns names of languages.
    if ($name==null) return array_keys($language);
    assert(array_key_exists($name, $language));
    return Number($number, $language[$name]);
}

//bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb
function Hindi_number(  $number) { return NumberAs('Devanagari'  , $number); }
function Arabic_number( $number) { return NumberAs('Arabic-Indic', $number); }
function Bengali_number($number) { return NumberAs('Bengali'     , $number); }
function Tibetan_number($number) { return NumberAs('Tibetan'     , $number); }

//bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb
function Chinese_number($number) {
    static $minus='&#x8d1f;';  // https://en.wiktionary.org/wiki/%E8%B4%9F
    static $u=null;
    static $factors=null;

    assert(is_int($number));
    if (!$u) $u=explode(',','3007,4e00,4e8c,4e09,56db,4e94,516d,4e03,516b,4e5d');
    if (!$factors) $factors = [
        intval(pow(10,12))=>'&#x5146;',
        intval(pow(10, 8))=>'&#x4ebf;',
        intval(pow(10, 4))=>'&#x842c;',
        intval(pow(10, 3))=>'&#x5343;',
        intval(pow(10, 2))=>'&#x767e;',
        intval(pow(10, 1))=>'&#x5341;',
        ];
    $digit = function($i) use($u) { return "&#x{$u[$i]};"; };

    if ($number  < 0) $return = $minus.Chinese_number(-$number);
    if ($number == 0) $return = $digit(0);
    else {
        $return = '';
        // Use zeros based on https://en.wikipedia.org/wiki/Chinese_numerals
        foreach ($factors as $factor => $char) {
            if ($number > $factor) {
                $dividend  = intval($number / $factor);
                $return .= Chinese_number($dividend).$char;
                $number = intval($number % $factor);
            } else if ($number == $factor) {
                $return .= $digit(1).$char;
                $number = intval($number % $factor);
            }
        }
        if ($number > 0 and $number < 10) $return .= $digit($number);
    }
    return $return;
}

//ccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccc
function Romanic_number($number, $upcase = true) { 
    static $table = [
        'M'=>1000, 'CM'=>900, 'D'=> 500, 'CD'=>400,
        'C'=> 100, 'XC'=> 90, 'L'=>  50, 'XL'=> 40,
        'X'=>  10, 'IX'=>  9, 'V'=>   5, 'IV'=>  4,
        'I'=>1]; 
    assert(is_int($number) and is_bool($upcase));
    $return = ''; // must be between 1 and 3888 (MMMDCCCLXXXVIII)
    if (0 < $number and $number <= 3888) {
        $return = ''; 
        foreach($table as $roman=>$arabic) { 
            while ($number>=$arabic) { $number -= $arabic; $return .= $roman; }
        }
    } 
    $return = $upcase ? $return : strtolower($return); 
    return $return;
} 

//ddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd
function establish($args) {
    $result = [];
    foreach ($args as $index => $pair) {
        $a = $pair[0];
        $z = $pair[1];
        $temp = [];
        if (($z-$a) > 4) {
            foreach (range($a,$z) as $i) $temp[] = "&#{$i};";
        } else {
            foreach ($pair as $i) $temp[] = "&#{$i};";
        }
        $result[] = $temp;
    }
    return $result;
}

//eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee
function render_number($number, $id=_) {
    static $English = null;
    static $Greek   = null;

    // JIT
    if (!$English) $English = establish([[ 97,122], [ 65, 90]]);
    if (!$Greek  ) $Greek   = establish([
        [945,946,947,948,949,950,951,952,953,954,955,956,
         957,958,959,960,961,963,964,965,966,967,968,969],
        [913,914,915,916,917,918,919,920,921,922,923,924,
         925,926,927,928,929,931,932,933,934,935,936,937],
    ]);

    if (!$id) $id = '_';
    assert(strlen("$id") == 1);

    $ID = strtoupper($id);
    $upper = ($ID == $id);

    //echo "[$id] ";

    $number = intval($number);

    if      ($ID == 'C') $result = Chinese_number($number                );
    else if ($ID == 'A') $result =  Arabic_number($number                );
    else if ($ID == 'E') $result =   Alpha_letter($number,$upper,$English);
    else if ($ID == 'G') $result =   Alpha_letter($number,$upper,$Greek  );
    else if ($ID == 'H') $result =       NumberAs('Devanagari',$number   );
    else if ($ID == 'R') $result = Romanic_number($number,$upper         );
    else if ($ID == 'T') $result =       NumberAs('Tibetan',   $number   );
    else $result = "$number";
    return $result;
}

//hhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh
function newline() {
    $result = '<br clear="all"/><!-- drop rendering under this -->'.PHP_EOL;
    return $result;
}

//hhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh
function center($text) {
    $result = '<div align="center">'.$text.'</div>'.PHP_EOL;
    return $result;
}

//iiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiii
function rCount() {
    $args = func_get_args();
    array_walk_recursive(
        $args,
        function($v, $k) use(&$count) {
            if(array_key_exists($k, $count)) $count[$k]++;
            else $count[$k]=1;
        },
        $count=array()
    );
    return $count;
}

//11111111111111111111111111111111111111111111111111111111111111111111111111111
function X_grep($words, $dict) {
    $greps = array_map(
        function($w) {
            // Look for both singular and plural
            $s = rtrim($w, 's');
            return "egrep '/$w/|/$w \(|/$s/|/$s \('";
        },
        $words);
    $greps[0] .= " $dict";
    $grep = implode('|', $greps);
    return `$grep`;
}

//11111111111111111111111111111111111111111111111111111111111111111111111111111
function X_EnglishChinese() {
    $args = func_get_args();
    assert(count($args) != 0);
    $dir = \dirname(__FILE__);
    $dict = "$dir/Chinese/cedict_1_0_ts_utf-8_mdbg.txt";
    $plural = array_map(
        function($w) { return strtolower($w); },
        $args);

    $result = X_grep($plural, $dict);
    $line = explode(PHP_EOL, $result);
    return explode(' ', $line[0])[0];
}

//11111111111111111111111111111111111111111111111111111111111111111111111111111
function X_sequence($group, $label, $depth=null) {
    static $levels = 5;
    static $sequence = array();
    static $lookup = array();

    $value = '';

    //vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
    if ($group == '' and $label == '') {
        // Debugging
        $value .= '{';
        $comma = '';
        foreach ($lookup as $k => $v) {
            $value .= "{$comma}'{$k}': ".'{';
            $comma2 = '';
            foreach ($v as $k2 => $v2) {
                $value .= "{$comma2}'{$k2}': {$v2}";
                $comma2 = ', ';
            }
            $value .= '}';
            $comma = ', ';
        }
        $value .= '}'.PHP_EOL;
        return $value;
    }
    //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

    if (!in_array($group, $lookup)) {
        // Initialize a group if a new one is presented.
        $sequence[$group] = array_fill(0, $levels, 0);
        $value = "{$sequence[$group][0]}";
        $lookup[$group][$label] = $value;
    }

    if ($depth==null) {
        // Return the lookup value if no depth presented.
        //gossip($lookup, $group, $label);
        $value = $lookup[$group][$label];
    } else {
        // Increment the value at the depth index.
        $sequence[$group][$depth]++;
        $sequence[$group][$depth+1] = 0;
        $value = "{$sequence[$group][0]}";
        foreach (range(1,$depth-1) as $level) {
            $value .= ".{$sequence[$group][$level]}";
        }
        $lookup[$group][$label]=$value;
    }
    return $value;
}

//22222222222222222222222222222222222222222222222222222222222222222222222222222
function X_table4($source=[]) {
    static $default = [
        'level' =>0,
        'border'=>2,
        'align' =>'center',
        'width' =>"50%",
        'dict'  =>[],
        'data'  =>[
            [['9'=>'a'],['8'=>'b'            ],['7'=>'c']],
            [['6'=>'d'],['5'=>'e','bold'=>'b'],['4'=>'f']],
            [['3'=>'g'],['2'=>'h'            ],['1'=>'i']],
        ],
    ];
    static $align = [
        '0'=>['align'=>'center', 'valign'=>'middle'],
        '1'=>['align'=>'left'  , 'valign'=>'top'   ],
        '2'=>['align'=>'center', 'valign'=>'top'   ],
        '3'=>['align'=>'right' , 'valign'=>'top'   ],
        '4'=>['align'=>'left'  , 'valign'=>'middle'],
        '5'=>['align'=>'center', 'valign'=>'middle'],
        '6'=>['align'=>'right' , 'valign'=>'middle'],
        '7'=>['align'=>'left'  , 'valign'=>'bottom'],
        '8'=>['align'=>'center', 'valign'=>'bottom'],
        '9'=>['align'=>'right' , 'valign'=>'bottom'],
        ];
    static $attr = [
        'bold'=>'b', 'italic'=>'i', 'underline'=>'u'
        ];
    static $initialize = true;
    $source = array_merge($default, $source);
    $ignore = ['data', 'dict'];

    $level = $source['level'];
    $number =
        ($initialize) ?
        update('Table', '_Er____') :
        update('Table', $level);
    $initialize = false;

    if (!is_array($source)) {
        assert(is_string($source));
        return ($number);
    }

    // Construct the outermost table tag.
    $result  = '';
    $result .= PHP_EOL.PHP_EOL.'<!-- X_table4 -->'.PHP_EOL;
    $result .= '<table';
    foreach ($source as $key=>$val) {
        if (in_array($key, $ignore)) continue;
        $result .= " $key=\"$val\"";
    }
    $result .= '>';

    // Construct the rows
    foreach ($source['data'] as $row) { // [[9=>a],[8=>b],[7=>c]]
        $result .= ' <tr>'.PHP_EOL;
        foreach ($row as $krow=>$col) { // [9=>a]
            $result .= '  <td';
            foreach ($col as $kcol=>$cell) { // 9=>a
                if (!is_int($kcol)) continue;
                foreach ($align[$kcol] as $kw=>$vl) {
                    $result .= " $kw=\"$vl\"";
                }
            }
            $result .= '>';
            $around = [];
            foreach ($col as $kcol=>$cell) { // 9=>a
                if (is_int($kcol)) continue;
                else if (in_array($kcol, $attr)) $around[] = $kcol;
                else if (in_array($cell, $attr)) $around[] = $cell;
            }
            foreach ($around as $a) $result .= "<$a>";
            foreach ($col as $kcol=>$cell) { // 9=>a
                if (substr($cell,0,1) == '_')
                    $cell = '<b>'.substr($cell, 1).'</b>';
                if (is_int($kcol)) $result .= $cell;
            }
            foreach (array_reverse($around) as $a) $result .= "</$a>";
            $result .= '</td>'.PHP_EOL;
        }
        $result .= '</tr>'.PHP_EOL;
    }
    $result .= "</table>".PHP_EOL.PHP_EOL;
    return $result;
}

//22222222222222222222222222222222222222222222222222222222222222222222222222222
function X_gnuplot($parameters=[]) {
    $default = [
        text     => '#No gnuplot plot',
        label    => 'No gnuplot label',
        filename => ''
    ];
    $params = array_merge($default, $parameters);
    //gossip($params);
    extract($params);
    assert($filename);
    //assert($text);
    `echo "$text"|gnuplot`;
    return ''.
        '<table align="left">'.
        '<tr><td align="center">'.
        '<img src="Airy.gnuplot.png" width="256" height="256"/>'.
        '</td></tr><tr><td align="center" width="258">'.
        $label.
        '</td></tr></table>';
}

//22222222222222222222222222222222222222222222222222222222222222222222222222222
function X_comment() {
    $msg = implode(' ', func_get_args());
    $value = PHP_EOL."<!-- {$msg} -->".PHP_EOL;
    return $value;
}

//22222222222222222222222222222222222222222222222222222222222222222222222222222
function X_labels($storage, $requested, $keyword) {
    //gossip($storage, $requested, $keyword);
    $plural = (count($requested) != 1) ? 's' : '';
    $return = "<nobr>{$keyword}{$plural}<sup>".
           implode(',', array_map(
               function($arg) use($storage) { return $storage[$arg]; },
               $requested)).
           "</sup></nobr>";
    return $return;
}

//22222222222222222222222222222222222222222222222222222222222222222222222222222
function X_make($source) {
    // Executes arguments redirected into an HTML file, and returns contents.
    $result = '';
    //$result .= X_comment('source', $source);
    $period = strrpos($source, ".");
    $basename = substr($source, 0, $period);
    //$result .= X_comment('basename', $basename);
    $target = "{$basename}.html";
    //$result .= X_comment('target', $target);
    $execute =
        (file_exists($target)) ?
        (filemtime($target) < filemtime($source)) :
        true;
    //$result .= X_comment('execute', $execute);
    if ($execute) {
        $command = implode(' ', func_get_args());
        `$command > $target`;
        $result .= X_comment('command', $command, $target);
    }
    $return = $result.file_get_contents($target).PHP_EOL;
    return $return;
}

//22222222222222222222222222222222222222222222222222222222222222222222222222222
function X_head($data=[]) {
    static $MathJaxURL = "https://cdn.mathjax.org/mathjax/latest/MathJax.js";
    static $MathJaxQRY = "config=TeX-AMS-MML_HTMLorMML";

    $default = [
        'title'    => 'How to Write a Scientific Paper',
        'subtitle' => 'A PHP Library',
        'author'   => 'Jonathan D. Lettvin',
        'tz'       => 'US/Eastern',
    ];

    extract($default);
    extract($data);

    date_default_timezone_set($tz);
    $date = date('M d Y');
    $return = <<< HEAD
<html>
 <head>
  <title>'.$title.'</title>
  <script type="text/javascript" src="{$MathJaxURL}?{$MathJaxQRY}">
  </script>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
 </head>
 <body>
  <h1 align="center">$title</h1>
  <h2 align="center">$subtitle</h2>
  <h3 align="center">$author</h3>
  <h3 align="center">$date</h3>
  <hr />
HEAD;
    return $return;
}

//22222222222222222222222222222222222222222222222222222222222222222222222222222
function X_tail() {
    $return = <<< TAIL
 </body>
</html>
TAIL;
    return $return;
}

//22222222222222222222222222222222222222222222222222222222222222222222222222222
function X_div($title, $level) {
    static $div = array('</div>', '<div>');

    assert($level > -3);
    
    $return = ($level < 0);
    if ($return) $return .= $div[$level == -1].PHP_EOL;
    // if ($level = -1) echo "<h3>".$title."</h3>".PHP_EOL;
    return $return;
}

//22222222222222222222222222222222222222222222222222222222222222222222222222222
function X_section($args) {
    static $h = array('h3', 'h4', 'h5');
    static $incr = false;
    static $last = 0;
    static $initialize = true;

    $default = [keyword=>'', title=>'', level=>0];
    extract(array_merge($default, $args));
    //extract($default);
    //extract($args);

    $number = ($initialize) ? update('Section', '_Er____') : update('Section', $level);
    $initialize =false;

    // Used to close a division at the end of file
    if (X_div($title, $level)) return '';

    $b = '<'.$h[$level].'>Section ';
    $a = '</'.$h[$level].'>';
    $c = X_EnglishChinese($keyword);
    if ($c) $c = "($c)";
    return "$b".$number.": $keyword$c $title$a".PHP_EOL;
}

//22222222222222222222222222222222222222222222222222222222222222222222222222222
function X_appendix($arg) {
    static $h = array('h3', 'h4', 'h5');
    static $incr = false;
    static $last = 0;
    static $initialize = true;

    $default = [keyword=>'', title=>'', level=>0];
    extract($default);
    extract($arg);

    $number = ($initialize) ? update('Appendix', 'E_r__') : update('Appendix', $level);
    $initialize =false;

    // Used to close a division at the end of file
    if (X_div($title, $level)) return '';

    $b = '<'.$h[$level].'>Appendix ';
    $a = '</'.$h[$level].'>';
    $c = X_EnglishChinese($keyword);
    if ($c) $c = "($c)";
    return "$b".$number.": $keyword$c $title$a".PHP_EOL;
}

//22222222222222222222222222222222222222222222222222222222222222222222222222222
function X_LiBox($title, $text) {
    $ret = '<li>'.$title.PHP_EOL;
    $ret .= '<table border="1" width="95%" align="center"><tr><td>'.PHP_EOL;
    $ret .= $text.PHP_EOL;
    $ret .= '</td></tr></table>'.PHP_EOL;
    $ret .= '</li>'.PHP_EOL;
    return $ret;
}

//22222222222222222222222222222222222222222222222222222222222222222222222222222
function X_table($parameters) {
    assert (is_array($parameters));
    $default = [
        text  => '',
        align => 'left',
        valign=> 'top',
        border=> 1,
        width => '100%'];
    extract($default);
    extract($parameters);
    $ret = ''.
        "<table ".
        "align=\"$align\" ".
        "valign=\"$valign\" ".
        "border=\"$border\"".
        "width=\"$width\"".
        ">".
        $text.
        '</table>'.
        PHP_EOL;
    return $ret;
}

//22222222222222222222222222222222222222222222222222222222222222222222222222222
function X_caption($text) {
    $ret = '<caption>'.$text.'</caption>'.PHP_EOL;
    return $ret;
}

//22222222222222222222222222222222222222222222222222222222222222222222222222222
function X_tr($text) {
    $ret = '<tr>'.$text.'</tr>'.PHP_EOL;
    return $ret;
}

//22222222222222222222222222222222222222222222222222222222222222222222222222222
function X_th($text) {
    $ret = '<th align="left" valign="top">'.$text.'</th>'.PHP_EOL;
    return $ret;
}

//22222222222222222222222222222222222222222222222222222222222222222222222222222
function X_td($text) {
    $ret = '<td align="left" valign="top">'.$text.'</td>'.PHP_EOL;
    return $ret;
}

//22222222222222222222222222222222222222222222222222222222222222222222222222222
function X_tex($text) {
    $ret = '\('.$text.'\)';
    //$ret = '$$'.$text.'$$';
    return $ret;
}

//22222222222222222222222222222222222222222222222222222222222222222222222222222
function update($name, $level='_e_e_') {
    static $keep = [];
    static $type = [];
    static $size = 5;
    if (!isset($keep[$name])) {
        assert(is_string($level));
        $keep[$name] = array_fill(0, $size, 0);
        $type[$name] = $level;
        $level = 0;
    }
    assert(is_int($level) and $level >= 0 and $level < $size);
    assert(is_array($keep[$name]));
    $number = $keep[$name][$level]++;
    $styled = $type[$name];
    foreach (range(0,$level) as $maybe) {
        if ($keep[$name][$maybe] == 0)
            $keep[$name][$maybe] = 1;
    }
    while($level++ < $size) $keep[$name][$level] = 0;
    $ret = []; #implode('.', $keep[$name])." {$styled} ";
    $tmp = [];
    //gossip($size, $keep, $name);
    foreach (range(0, $size) as $i) {
        if ($i >= count($keep)) break;
        $n = $keep[$name][$i];
        $s = $type[$name][$i];
        //assert(strlen($s) == 1);
        //echo "#$i ($s) ";
        $tmp[] = render_number($n, $s);
        if ($n != 0) {
            $ret = array_merge($ret, $tmp);
            $tmp = [];
        }
    }
    return implode('.', $ret);  #$ret.implode('.', $rep);
}

//TTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTT
function test_update() {
    $keep = '<div><hr><h3>test_increment</h3>'.PHP_EOL;

    $keep .= '<h4>_</h4>'.PHP_EOL;
    $_ = update('_','EEEEE'); $keep .= '<div>'.$_.'</div>';
    $_ = update('_',1); $keep .= '<div>'.$_.'</div>';
    $_ = update('_',2); $keep .= '<div>'.$_.'</div>';
    $_ = update('_',1); $keep .= '<div>'.$_.'</div>';
    $_ = update('_',0); $keep .= '<div>'.$_.'</div>';

    $keep .= '<h4>B</h4>'.PHP_EOL;
    $B = update('B','_____'); $keep .= '<div>'.$B.'</div>';
    $B = update('B',1); $keep .= '<div>'.$B.'</div>';
    $B = update('B',2); $keep .= '<div>'.$B.'</div>';
    $B = update('B',1); $keep .= '<div>'.$B.'</div>';
    $B = update('B',0); $keep .= '<div>'.$B.'</div>';

    $keep .= '<h4>C</h4>'.PHP_EOL;
    $C = update('C','GReCr'); $keep .= '<div>'.$C.'</div>';
    $C = update('C',1); $keep .= '<div>'.$C.'</div>';
    $C = update('C',2); $keep .= '<div>'.$C.'</div>';
    $C = update('C',3); $keep .= '<div>'.$C.'</div>';
    $C = update('C',4); $keep .= '<div>'.$C.'</div>';
    $C = update('C',4); $keep .= '<div>'.$C.'</div>';
    $C = update('C',3); $keep .= '<div>'.$C.'</div>';
    $C = update('C',2); $keep .= '<div>'.$C.'</div>';
    $C = update('C',1); $keep .= '<div>'.$C.'</div>';
    $C = update('C',0); $keep .= '<div>'.$C.'</div>';

    $keep .= '<h4>_B</h4>'.PHP_EOL;
    $_ = update('_',4); $keep .= '<div>'.$_.'</div>';
    $B = update('B',4); $keep .= '<div>'.$B.'</div>';
    $C = update('C',4); $keep .= '<div>'.$C.'</div>';
    $_ = update('_',3); $keep .= '<div>'.$_.'</div>';
    $B = update('B',3); $keep .= '<div>'.$B.'</div>';
    $C = update('C',3); $keep .= '<div>'.$C.'</div>';

    return $keep.'<hr></div>'.PHP_EOL;
}

//TTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTT
function test_numbering() {
    $_ = numbering(['label'=>'_', 'style'=>'_____']);
    $C = numbering(['label'=>'C', 'style'=>'CCCCC']);
    $M = numbering(['label'=>'M', 'style'=>'_Ee_r']);

    $show = function($s) { return '<div>'.$s.'</div>'.PHP_EOL; };

    $keep = '<div><hr><h3>test_numbering</h3>';
    $keep .= '<h4>_</h4>'.PHP_EOL;
    $keep .= $show($_); numbering(['label'=>'_']);
    $keep .= $show($_); numbering(['label'=>'_', 'level'=>1]);
    $keep .= $show($_); numbering(['label'=>'_', 'level'=>2]);
    $keep .= $show($_); numbering(['label'=>'_']);
    $keep .= $show($_);
    $keep .= '<h4>C</h4>'.PHP_EOL;
    $keep .= $show($C); numbering(['label'=>'C']);
    $keep .= $show($C); numbering(['label'=>'C', 'level'=>1]);
    $keep .= $show($C); numbering(['label'=>'C', 'level'=>2]);
    $keep .= $show($C); numbering(['label'=>'C']);
    $keep .= $show($C);
    $keep .= '<h4>M</h4>'.PHP_EOL;
    $keep .= $show($M); numbering(['label'=>'M']);
    $keep .= $show($M); numbering(['label'=>'M', 'level'=>1]);
    $keep .= $show($M); numbering(['label'=>'M', 'level'=>2]);
    $keep .= $show($M); numbering(['label'=>'M']);
    $keep .= $show($M);
    return $keep.'<hr></div>'.PHP_EOL;
}

//22222222222222222222222222222222222222222222222222222222222222222222222222222
function figure($id=null) {
    // Each figure is to be accompanied by an associative array:
    //     filename: where the actual figure is to be found
    //     caption: text to be displayed with the figure
    //     id: unique label for recovering figure numbers.
    // Figures may be in a grid, in which case numbers are supplemented
    // by subnumbers within the figure.

    // Keep the unique numbers used to identify figures as they are added.
    static $number = 0, $subnumber = 0;

    static $allNull = [     // Default values for figure parameters not given.
        filename  => null,
        caption   => null,
        id        => null,
        width     => 0,           // Default img dimensions are unspecified
        height    => 0];

    static $ids = array();  // Keep id=>number association for later reference.
    static $level = 0;      // Remember how deeply recursed this call is.
    static $tally = 0;      // How many figures are in this call.

    // array of parameters given, and its length.
    $len = count($sources = func_get_args());

    //$foo = rCount(func_get_args())[filename];
    //echo PHP_EOL."[[[[$foo]]]]".PHP_EOL;

    $result = '';

    if ($len == 0) {
        $result .= ''; // Do nothing
    } else if (is_string($id)) {
        // Section 1: if given ids, return a Figures label.
        X_labels($ids, $sources, 'Figure');
    } else {
        //_____________________________________________________________________
        // Section 2: Place Figures in a table.
        if ($level == 0) {
            $number++;
            $subnumber = 0;
            $tally = 0;
            $result .= "<table align=\"left\" border=\"1\"><tr>".PHP_EOL;
        }
        $tally += $len;
        $level++;
        $singles = 0;
        foreach ($sources as $source) {
            //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

            //gossip($source);
            //TODO handle nested arrays
            $specific = function($source) use($result, $singles, $tally) {
            };
            $F = $source['filename'];
            if (!$F) {
                //vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
                $singles++;
                if ($singles > 1) {
                    $result .= "</tr><tr>".PHP_EOL;
                }

                foreach ($source as $single) {
                    $result .= figure($single);
                    $tally++;
                }
                //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
            } else {
                //vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
                extract($allNull);          // Install defaults

                $N  = extract($source);      // Get the figure variables
                $width2 = ($width == 0) ? '' : $width + 2;

                $S  = " src=\"{$filename}\"";  // Prepare img tag attributes
                $W  = ($width ==0) ? "" : " width=\"{$width}\"";
                $W2 = ($width ==0) ? "" : " width=\"{$width2}\"";
                $H  = ($height==0) ? "" : " height=\"{$height}\"";

                $subnumber++;
                $figno = "$number";         // Generate the figure number
                if ($tally > 1) $figno .= ".$subnumber";
                $ids[$id] = $figno;

                // Generate the HTML
                $result .= "<td align=\"center\" valign=\"top\"{$W2}>".PHP_EOL.
                    "<div><img{$S}{$W}{$H}></div>".PHP_EOL.
                    "<div align=\"center\">Figure {$figno}: {$caption}</div>".PHP_EOL.
                    '</td>'.PHP_EOL;
                //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
            }
            //-----------------------------------------------------------------
        }
        $level--;
        if ($level == 0) {
            $result .= "</tr></table>".PHP_EOL;
        }
    }
    return $result;
}

//22222222222222222222222222222222222222222222222222222222222222222222222222222
function equation($the =
    [tex=>'e^{i\pi}+1 = 0', id=>'Euler', label=>'Euler\'s identity']
) {
    static $eqno = 0;
    static $eqarray = array();

    //gossip($the);

    $result = '';
    if (is_array($the)) {
        extract($the);
        $eqno++;
        $eqarray[$id] = $eqno;
        $result .= newline();
        $result .= '<table width="100%" align="right" style="border-spacing: 5px">';
        $result .= '<tr><td align="center" width="70%">'.PHP_EOL;
        $result .= X_tex($tex);
        if ($label != '') {
            $result .= '</td><td align="right"><i>'.$label.'</i>'.PHP_EOL;
        }
        $result .= '</td><td align="right">'."[{$eqno}]".PHP_EOL;
        $result .= '</td></tr></table>'.PHP_EOL;
    } else {
        $result .= X_labels($eqarray, func_get_args(), 'Equation');
    }
    return $result;
}

//22222222222222222222222222222222222222222222222222222222222222222222222222222
function listing($the =
    ['id'=>'none', 'label'=>'No content', 'listing'=>'This is an example']
) {
    static $preno = 0;
    static $prearray = array();

    $result = '';

    if (is_array($the)) {
        extract($the);
        $preno++;
        $prearray[$id] = $preno;
        $result .= X_table([
            text=>
            X_tr(X_td('<pre>'.$listing.'</pre>')).
            X_tr(X_td('Listing '.$preno.': '.$label))]
        ).PHP_EOL;
        //$result .= '<table border="1" align="left"><tr><td align="left">'.PHP_EOL;
        //$result .= '<pre>'.$listing.'</pre>'.PHP_EOL;
        //$result .= '</td></tr><tr><td align="center">'.PHP_EOL;
        //$result .= 'Listing '.$preno.': '.$label.PHP_EOL;
        //$result .= '</td></tr></table>'.PHP_EOL;
    } else {
        $result .= X_labels($prearray, func_get_args(), 'Listing');
    }
    return $result;
}


//22222222222222222222222222222222222222222222222222222222222222222222222222222
if (realpath($argv[0]) == __FILE__) { /* Beginning of MAIN */
    static $numbering_styles = [
        '_'=>'US',
        'A'=>'Arabic' ,
        'C'=>'Chinese',
        'e'=>'english', 'E'=>'English',
        'g'=>'greek'  , 'G'=>'Greek'  ,
        'H'=>'Hindi',
        'r'=>'roman'  , 'R'=>'Roman'  ,
        'T'=>'Tibetan',
    ];

    $number_style_names = implode('/', array_values($numbering_styles));

    function Title($args=[]) {
        $ret = X_head([
            'title'    => 'Write a Scientific Paper',
            'subtitle' => '写科学论文'
        ]);
        return $ret;
    }

    function Author($args=[]) {
        $ret = '';
        return $ret;
    }

    function Timestamp($args=[]) {
        $ret = '';
        return $ret;
    }

    function Summary($args=[]) {
        $ret = X_table([
            align  => "center",
            border => 0,
            width  => '80%',
            text   => X_tr(X_td(<<<ABSTRACT_TEXT
The <B>Xenofile</B> PHP library
simplifies rapid development of draft quality scientific papers.
A scientific paper is a standardized tool for
communicating ideas, methods, and observations.
This section, the abstract, summarizes major points.
In later sections, the order and content of sections is standard.
Citations, equations, figures, tables, listings, and appendices
are given sequential labels which are used in the text.
Standard visual cues are used to partition content effectively.
Xenofile automates many tasks necessary to meet the requirements.
Authors put less work into structure and focus more on content
when using Xenofile.
ABSTRACT_TEXT
    ))
]);
        return $ret;
    }

    function Introduction($args=[]) {
        $ret = X_section([
            keyword   => 'Introduction',
            title     => "computer aided scientific paper writing"]).
<<<INTRODUCTION_TEXT
<P>
Definition of Xenophile:
<i>One who has an interest in celebrating differences</i>.
The Xenofile library harmonizes many different technologies and
this paper is produced as a test of its features
when the library is run in standalone mode.
The goal is to simplify rapid development of scientific papers.
Methods are implemented, documented, and illustrated.
When included in another PHP script
Xenofile is used to generate a paper using that script's content.
</P><P>
Note the section title.
It has a sequence number, a keyword (with translation) and description.
In the following section, note that the first subsection has
a hierarchical sequence number.
</P>
INTRODUCTION_TEXT
;
        return $ret;
    }

    function Methods($args=[]) {
        $ret = X_section([
            keyword=>'Methods',
            title=>"used for authoring a paper"]);
        return $ret;
    }

    function Results($args=[]) {
        $ret = '';
        return $ret;
    }

    function Discussion($args=[]) {
        $ret = '';
        return $ret;
    }

    function Acknowledgements($args=[]) {
        $ret = '';
        return $ret;
    }

    function Citations($args=[]) {
        $ret = '';
        return $ret;
    }

    function Appendices($args=[]) {
        $ret = '';
        return $ret;
    }

    function Paper() {
        return
            Title().
            Author().
            Timestamp().
            Summary().
            Introduction().
            Methods().
            Results().
            Discussion().
            Acknowledgements().
            Citations().
            Appendices();
    }

    $there = "to the";

    $sequences = [
        '_'=>X_sequence('',''),

        'a'=>X_sequence('groupA','itemA', 0),
        'b'=>X_sequence('groupA','itemB', 0),
        'c'=>X_sequence('groupA','itemC', 1),
        'd'=>X_sequence('groupA','itemD', 1),
        'e'=>X_sequence('groupA','itemE', 0),

        'A'=>X_sequence('groupB','itemA', 0),
        'B'=>X_sequence('groupB','itemB', 0),
        'C'=>X_sequence('groupB','itemC', 1),
        'D'=>X_sequence('groupB','itemD', 1),
        'E'=>X_sequence('groupB','itemE', 0),
        ];

    $foobar = function($i) {
        switch ($i) {
            case 0: return $i; break;
            case 2: return "foo$i"; break;
            default: return "bar$i"; break;
        }
        return $i;
    };

    $testSequences = function($extended=false) {
        $testrow = function($number, &$thead, &$tbody) {
            static $times = false;
            global $numbering_styles;
            $tbody .= '<tr>'.PHP_EOL;
            foreach ($numbering_styles as $id => $label) {
                $tbody .= '<td align="right">'.
                    render_number($number, $id)."</td>".
                    PHP_EOL;
                if (!$times) $thead .= "<th>".$label.PHP_EOL;
            }
            $times = true;
            $tbody .= '</tr>'.PHP_EOL;
        };
    
        $tbody = '';
        $thead = '';
    
        $A = 1;
        $B = 1;
        $fib = 1;
        if ($extended == true) {
            while($fib <=10000000) {
                $testrow($fib, $thead, $tbody);
                $A = $B;
                $B = $fib;
                $fib = $A + $B;
            }
            $testrow(0, $thead, $tbody);
            foreach (range(1,11) as $exponent) {
                $testrow(+intval(pow(10,$exponent)-1), $thead, $tbody);
                $testrow(+intval(pow(10,$exponent)+0), $thead, $tbody);
                $testrow(+intval(pow(10,$exponent)+1), $thead, $tbody);
                $testrow(-intval(pow(10,$exponent)-1), $thead, $tbody);
                $testrow(-intval(pow(10,$exponent)+0), $thead, $tbody);
                $testrow(-intval(pow(10,$exponent)+1), $thead, $tbody);
            }
        } else {
            foreach (range(-10,24) as $number) {
                $testrow($number, $thead, $tbody);
                $A = $B;
                $B = $fib;
                $fib = $A + $B;
            }
        }
        return newline().
            '<table border="1">'.PHP_EOL.
            $thead.$tbody.'</table>'.PHP_EOL;
    };

    $testLanguages = function() {
        $return =
            '<caption>'.
            'Xenofile numbers '.
            '(square if browser render fails)'.
            '</caption>';
        foreach (NumberAs() as $language) {
            $return .= '<tr><td>'.$language.'</td>';
            foreach (range(0,10) as $number) {
                $return .= '<td>'.NumberAs($language, $number).'</td>';
            }
            $return .= '</tr>';
        }
        return '<table border="1" align="center">'.$return.'</table>';
    };
    
    $figures = ['gnuplot_Airy' => 'Airy.gnuplot.png'];
    extract($figures);

/*vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv*/
    HTML(
        Paper().

X_section([keyword=>'gnuplot', title=>'generates plots on-the-fly', level=>1]).
    "hello {$there} world".PHP_EOL.
    /* */
        newline().
        X_gnuplot([
            id        => 'AiryPlot',
            filename  => "{$gnuplot_Airy}",
            label     => ''.
            'Airy and sinc function comparison'.
            '\(\left(2\frac{J_1(u)}{u}\right)^2\)'.
            'and '.
            '\(\left(\frac{sin(u)}{u}\right)^2\)'
            ,
            text      => <<<AIRY
#!/usr/bin/env gnuplot
# Define functions
radius(a,x,y)=pi*sqrt((a*x)**2+(a*y)**2)
wave(a,x,y)=2*besj1(radius(a,x,y))/(radius(a,x,y))
Airy(a,x,y)=wave(a,x,y)**2
sinc(a,x,y)=sin(radius(a,x,y))/radius(a,x,y)

# Adjust display parameters
set term png size 1024,1024 crop
set samples 2999
set xtics border offset 0,-0.5 font \"Verdana,24\"
set ytics font \"Verdana,24\"
set key default
set key bottom right
set key font \"Verdana,24\"
set key spacing 3.0

# Generate images
set xrange[-4.22:4.22]
set yrange[-0.15:1]
set output '{$gnuplot_Airy}'
plot Airy(1,x,0) lw 5,sinc(1/1.2196698912665045,x,0)**2 lw 5
AIRY
    ]).<<<AiryVsSinc
<P>
To the left is a figure produced by gnuplot
(http://gnuplot.sourceforge.net) comparing
diffractive blur with  the sinc function
(sombrero function) with the same first zero.
</P><P>
Producing plots for use in a scientific paper
is simplified with functions designed to accept
gnuplot script language and then
place the produced image on the page.
</P><P>
Advice: generate large cropped images with thick lines
then specify smaller width and height for images in the paper
to improve appearance of resulting plots.
Also, note that legend (key) positioning and spacing
requires adjustment to avoid overlap with lines and curves.

</P>
AiryVsSinc
    .newline().
        X_section(
            [
                keyword   => 'Figures',
                title     => 'can be loaded from external files',
                level     => 1
            ]
        ).
        newline().
        //figure().
        figure(
            [
                filename  =>'NoisyGrayStep.png',
                caption   =>'me',
                id        =>'fig11',
            ], [
                filename  =>'Synchronic.png',
                caption   =>'it',
                id        =>'fig12',
                width     =>256,
                height    =>320,
            ]
        ).
        newline().
        figure(
            [
                filename  =>'NoisyGrayStep.png',
                caption   =>'me',
                id        =>'fig2',
            ]
        ).
        newline().
        /*
        figure(
            [
                [
                    filename  =>'NoisyGrayStep.png',
                    caption   =>'me',
                    id        =>'fig31'
                ], [
                    filename  =>'Synchronic.png',
                    caption   =>'it',
                    id        =>'fig32',
                    width     =>256,
                    height    =>320
                ]
            ], [
                [
                    filename  =>'NoisyGrayStep.png',
                    caption   =>'me',
                    id    =>'fig33'
                ], [
                    filename  =>'Synchronic.png',
                    caption   =>'it',
                    id    =>'fig34',
                    width =>256,
                    height    =>320,
                ]
            ]
        ).
         */
        newline().
        figure(
            [
                filename  =>'NoisyGrayStep.png',
                caption   =>'me',
                id    =>'fig4',
            ]
        ).
        newline().

        figure('fig11','fig12').PHP_EOL.
        figure('fig2').PHP_EOL.
        /*
         figure('fig31','fig32','fig33','fig34').PHP_EOL.
         */
        figure('fig4').PHP_EOL.

        newline().
        'rCount 2: '.PHP_EOL.
        rCount(
            [
                filename  =>'NoisyGrayStep.png',
                caption   =>'me',
                id    =>'step1'
            ], [
                filename  =>'Synchronic.png',
                caption   =>'syn1',
                id    =>'plot',
                width =>256,
                height    =>320,
            ]
        )[filename].
        newline().
        'rCount 4: '.PHP_EOL.
        rCount(
            [
                [
                    filename  =>'NoisyGrayStep.png',
                    caption   =>'me',
                    id    =>'step3'
                ], [
                    filename  =>'Synchronic.png',
                    caption   =>'syn2',
                    id    =>'plot',
                    width =>256,
                    height    =>320,
                ]
            ], [
                [
                    filename  =>'NoisyGrayStep.png',
                    caption   =>'me',
                    id    =>'step4'
                ], [
                    filename  =>'Synchronic.png',
                    caption   =>'syn3',
                    id    =>'plot',
                    width =>256,
                    height    =>320,
                ]
            ]
        )[filename].

        newline().
X_section([keyword=>'Equations', title=>'can be formatted with TeX', level=>1]).

        //equation().
        equation([tex=>'A=B', id=>'A:B', label=>'identity']).
        equation([tex=>'\frac{A}{B}', id=>'A/B', label=>'division']).
        /*
        equation('Euler').
        equation('Euler','A:B').
        equation('Euler','A:B','A/B').
         */

        newline().
X_section([keyword=>'Listings', title=>'can be loaded from external files', level=>1]).

        listing([id=>'A', label=>'A little', listing=>'This is an example.']).
        listing('A').
        newline().
        listing([id=>'B', label=>'B little', listing=>'This is another example.']).
        listing('A','B').

        $foobar(0).PHP_EOL.
        $foobar(2).PHP_EOL.
        $foobar(4).PHP_EOL.
        X_tex('I am boxed').
        /* */

/*---------------------------------------------------------------------------*/

        X_appendix([
            keyword   => 'Numbering',
            title     => "Equations, Figures, Listings, Sections"]).
        <<<TEXT_NUMBERING
<P>
Objects in a paper are numbered sequentially to identify
the order in which they appear in the paper.
They are numbered hierarchically to identify
the section, subsection, or grouping to which they belong.
Sections are typically identified by numbers such as "1. Introduction".
Subsections may be numbered or may use letters such as "1.a Field of inquiry".
Appendices are typically identified by letters such as "Appendix A".
When subsections are deeply nested, different sequence types are used.
For instance, subsubsections may use roman numerals.
</P><P>
This library provides resources for numbering in more than
the traditional Arabic/English/Roman styles.
The available styles are {$number_style_names}.
These styles are selectable for an object type.
For instance "[style='Aerg']" specifies that
Arabic, lowercase-English, lowercase-Roman, Greek, and Hindi (Devangari)
are used for hierarchy levels 1, 2, 3, and 4 respectively.
For instance a 4 level title example for
the seventh item at each level would be labelled "7.g.vii.&eta;".
A 5 level title of all sevens with "[style='GRCEH']"
(uppercase-Greek, uppercase-Roman, Chinese, uppercase-English, Hindi)
would be "&#0919;.VII.&#x4e03;.G.&#x096d;".
</P>
TEXT_NUMBERING
.
    X_table([width=>'50%', align=>'center', text=>
        X_caption("Examples of Numbering Styles").
        X_tr(
            X_th("STYLE").X_th("REPRESENTATION")
        ).X_tr(
            X_td("[style='AAAA']").X_td("7.7.7.7")
        ).X_tr(
            X_td("[style='Aerg']").X_td("7.g.vii.&eta;")
        ).X_tr(
            X_td("[style='GRCEH']").X_td("&#0919;.VII.&#x4e03;.G.&#x096d;")
        )
    ]).
        $testSequences().
        $testLanguages().
        "$$\boxed{Hello}$$\(\boxed{World}\)");
/*^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^*/

    foreach ($sequences as $key=>$txt)
        HTML("<P>{$key} {$txt}</P>");

    HTML(test_update());
    HTML('<hr>B');
    HTML(X_table4());
    HTML('<hr>C');
    HTML(X_table4(['level'=>0,'border'=>1,'align'=>'right','data'=>[
    [[         ],[    '_West'],['_Central'],[   '_East']],
    [[ '_North'],[  'Seattle'],[ 'Madison'],[ 'Augusta']],
    [['_Middle'],['Mendocino'],[  'Topeka'],['Richmond']],
    [[ '_South'],[ 'Pasadena'],[ 'Houston'],[   'Miami']],
]]));

    HTML(X_tail());

    echo X_store();
} /* End of MAIN */?>
