<?php
function show($O,$P=[]) {
    extract([N=>70,C=>'-',T=>PHP_EOL]);
    extract($P);
    // Show the object, then make a horizontal line.
    print_r($O);
    echo $T.str_repeat($C, $N).$T;
}

// Make an associative array.
$A = [a=>a,b=>b,c=>c]; // Note the lack of quotes.
// Extract a list of its keys.
$B = array_keys($A);
// Make a CSV string from the keys.
$C = join(',', array_keys($A));
// Turn the key/val pairs into local variables.
$N = extract($A);  // Extracts and returns how many.
// Make a string containing PHP code for creating list.
$E = '['.join(',',array_map(function($k){return "$k=>".'$'."$k"; }, $B)).']';
// Evaluate that string.
eval("\$F = $E;");
// Turn local variables into an associative array.
$G = compact(a,b,c);

show($A,[C=>'_']);
show($B);
show($C,[C=>'_',N=>20]);
show("a=$a,b=$b,c=$c");
show($N);
show($E);
show($F,[C=>'+',N=>50]);
show($G);
show('end');

?>
<!--EOF-->
