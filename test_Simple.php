<?php

namespace Publish;

require_once('../Paper/Simple.php');

if (realpath($argv[0]) == __FILE__) {
?><?
    echo figure(
        [filename=>'NoisyGrayStep.png',caption=>'me',id=>'fig11'],
        [filename=>'Synchronic.png',caption=>'it',id=>'fig12']
    );
    echo under();
?><?
    echo figure(
        [filename=>'NoisyGrayStep.png',caption=>'NB',id=>'fig2']
    );
    echo under();
?><?
    echo PHP_EOL.str_repeat('+', 70).PHP_EOL;
?><?
    echo figure(
        [
            [filename=>'NoisyGrayStep.png',caption=>'me',id=>'fig31'],
            [filename=>'Synchronic.png',caption=>'it',id=>'fig32']
        ], [
            [filename=>'NoisyGrayStep.png',caption=>'me',id=>'fig33'],
            [filename=>'Synchronic.png',caption=>'it',id=>'fig34']
        ]
    );
    echo under();
?><?
    echo figure(
        [filename=>'NoisyGrayStep.png',caption=>'NB',id=>'fig4']
    );
    echo under();
?>
    <? echo figure(fig11,fig12).PHP_EOL; ?>
    <? echo figure(fig2).PHP_EOL; ?>
    <? echo figure(fig31,fig32,fig33,fig34).PHP_EOL; ?>
    <? echo figure(fig4).PHP_EOL; ?>
<?
}
?>
