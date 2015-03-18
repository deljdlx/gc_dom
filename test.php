<?php

ini_set('display_errors', 'on');
error_reporting(E_ALL);

include('xml.php');
include('element.php');
include('collection.php');




$domBuffer='
<div>
<div id="test">
	<div class="content"><span>first child 1</span></div>
	<div class="content">test <span>first child 2</span></div>
	<div class="content">test <div>non </div><span>first child</span></div>
</div>
out of select
</div>
';

$document=new \GC\DOM\Element($domBuffer);

$document->find('#test .content span:first-child')->each(function($index, $node) {
	$node->style->backgroundColor='#0F0';
	echo $node->render();
	echo '<br />';
});



//=====================================================================================================
echo '<hr />';
echo '<hr />';




$domBuffer='
<div>
<div id="test">
	<div class="content"><span>first child 1</span></div>
	<div class="content">test <span>first child 2</span></div>
	<div class="content">test <div>non </div><span>first child</span></div>
</div>
out of select
</div>
';

$document=new \GC\DOM\Element($domBuffer);

$document->find('#test .content:first-child')->each(function($index, $node) {
	$node->style->backgroundColor='#0F0';
	echo $node->render();
	echo '<br />';
});



//=====================================================================================================
echo '<hr />';
echo '<hr />';



$domBuffer='
<div>
<div id="test">
	<div class="content">first child</div>
	<div class="content2">test <span>span 2</span></div>
	<div class="content">test <span>span 3</span></div>
</div>
out of select
</div>
';

$document=new \GC\DOM\Element($domBuffer);

$document->find('#test:first-child')->each(function($index, $node) {
	$node->style->backgroundColor='#FF0';
	echo $node->render();
	echo '<br />';
});



//=====================================================================================================
echo '<hr />';
echo '<hr />';



$domBuffer='
<div>
<div id="test">
	<div class="content">test <span>span 1</span></div>
	<div class="content2">test <span>span 2</span></div>
	<div class="content">test <span>span 3</span></div>
</div>
out of select
</div>
';

$document=new \GC\DOM\Element($domBuffer);

$document->find('#test .content')->each(function($index, $node) {
	$node->style->backgroundColor='#0FF';
	echo $node->render();
	echo '<br />';
});



//=====================================================================================================
echo '<hr />';
echo '<hr />';


$domBuffer='
<div id="test">
	<div class="content">test <span>span 1</span></div>
	<div class="content2">test <span>span 2</span></div>
	<div class="content">test <span>span 3</span></div>
</div>
';

$document=new \GC\DOM\Element($domBuffer);

$document->find('.content')->find('span')->each(function($index, $node) {
	echo $node->render();
	echo '<br />';
});



//=====================================================================================================
echo '<hr />';
echo '<hr />';

$domBuffer='
<div id="test">
	<div class="content">test 1</div>
	<div class="content2">test 2</div>
	<div class="content">test 3</div>
</div>
';

$document=new \GC\DOM\Element($domBuffer);


$document->each('div.content', function($index, $node) {
	$node->style->backgroundColor='#CCC';
	$node->style->border='solid 2px #000';
	echo $node->render();
});

//=====================================================================================================
echo '<hr />';
echo '<hr />';

		$domBuffer='
		<div id="test">
			<div class="content"><div test="changeMe"></div></div>
			<div class="content2">test 2</div>
			<div class="content"><div test="changeMe"></div></div>
		</div>
		';
		
$document=new \GC\DOM\Element($domBuffer);

$document->each('div.content div[test="changeMe"]', function($index, $node) {
	$node->html('hello world');
	$node->style->backgroundColor='#CCC';
	$node->style->border='solid 2px #000';
	
	$node['onclick']='alert(1)';
	
});

echo $document->render();

//=====================================================================================================
echo '<hr />';
echo '<hr />';
		$node=new \GC\DOM\Element('<div>content</div>');
		$node->html('<div>hello<hr/><div>world <br/> <input value="!!!"/></div></div>');
		$node->style->border='solid 5px #F00';
		$node->style->backgroundColor='rgba(255,255,200,0.5)';
		$node->style->padding='20px';

		
		$node->div[0]->style->fontSize='40px';
		$node->div[0]->div[0]->style->fontWeight='bold';

		echo $node->render();



//=====================================================================================================
echo '<hr />';
echo '<hr />';

		$domBuffer='
		<div id="test">
			<div class="content"><div test="changeMe">injection 1 </div></div>
			<div class="content2">test 2</div>
			<div class="content"><div test="changeMe">injection 2 </div></div>
		</div>
		';
		
$document=new \GC\DOM\Element($domBuffer);

$document->each('div.content div[test="changeMe"]', function($index, $node) {
	$child=new \GC\DOM\Element('<input value="hello world"/>');
	
	$child->style->backgroundColor='#FF0';
	
	$node->appendChild($child);
});

echo $document->render();





