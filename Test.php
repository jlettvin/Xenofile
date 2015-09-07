<?php
namespace Publish;

require_once('Paper.php');

$curdir = getcwd();
?>

<? Paper::Head(array(
    'title'    => 'Paper Test',
    'subtitle' => 'Does it work?',
    'author'   => 'Jonathan D. Lettvin'
)); ?>

<?
Paper::Figures('images', 'A pair of images', array(
    array(
        'filename' => "file://$curdir/Synchronic.png",
        'id'       => 'FishEye',
        'label'    => 'Totally new perspective',
        'width'    => 256,
        'height'   => 256
    ),
    array(
        'filename' => "file://$curdir/NoisyGrayStep.png",
        'id'       => 'NGS',
        'label'    => 'Noisy Gray Step',
        'width'    => 256,
        'height'   => 256
    )
));

Paper::Listing(
    'this',
    basename(__FILE__),
    htmlspecialchars(file_get_contents(__FILE__)));
?>
<table align="center" border="1">
<caption align="bottom">How to run and view this script</caption>
<tr><td>
linux command</td><td>php -S localhost:8000 Test.php
</td></tr><tr><td>
browse location</td><td>http://localhost:8000/
</td></tr></table>
<?
Paper::Equation(idlabeltex('Fun:1', 'Euler\'s identity', 'e^{i\pi}+1 = 0'));
Paper::Equation(array(
    'id'    => 'Fun:2',
    'tex'   => '\nabla^2u = \frac{1}{c^2}\frac{\partial^2u}{\partial t^2}',
    'label' => 'Wave equation')
);
Paper::Equation(array(
    'id'    => 'Fun:3',
    'tex'   => 'F\'(x)=\lim_{\Delta x\to 0}\frac{F(x+\Delta x)-F(x)}{\Delta x}',
    'label' => 'Fundamental theorem of calculus')
);
?>

One fun equation is <? Paper::EqReference("Fun:1"); ?>.
Another is <? Paper::EqReference("Fun:2"); ?>.
For sure, <? Paper::EqReference("Fun:3"); ?> is fun too!
<? Paper::FigReference("FishEye"); ?> is a strange image but
<? Paper::FigReference("NGS"); ?> is more important.

<? Paper::Tail(); ?>
