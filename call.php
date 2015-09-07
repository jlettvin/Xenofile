<?php

namespace Publish;

function doIt ($fn) {
  echo "doIt\n";
  return $fn();
}

function doMe () {
  echo "doMe\n";
}

// I am using a closure here.
// There may be a more clever way to "get the function-object" representing a given
// named function, but I do not know what it is. Again, I *don't use PHP* :-)
echo doIt(function () { doMe(); });

function funcRef($func){
  return create_function('', "return call_user_func_array('{$func}', func_get_args());");
}

function foo($a, $b, $c){
    return sprintf("A:%s B:%s C:%s", $a, $b, $c);
}

$b = funcRef("foo");

echo $b("hello", "world", 123);

//=> A:hello B:world C:123

class Odd {
    function __construct() {
        echo 'constructing'.PHP_EOL;
    }

    function __call($fun, $arg) {
        echo "__call '$fun' with '$arg'.".PHP_EOL;
    }

    function call($arg) {
        echo "call '$arg'.".PHP_EOL;
    }
}

function call($instance, $arg) {
    return $instance->call($arg);
}

$odd = new Odd();

$odd->even();
call($odd, 'unknown');
$even = $odd->call;
echo 'is_callable: "'.is_callable($even).'"'.PHP_EOL;
call_user_func(array($odd, 'call'), 'me');
// $even('this');

$fun4all = function($all) {
    echo "fun4$all".PHP_EOL;
};

$fun4all('me');

?>
