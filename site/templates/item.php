<?php
namespace ProcessWire;

$twack = wire('modules')->get('Twack');
$general = $twack->getNewComponent('General');

$content = $twack->getComponent('mainContent');
if ($content) {
	$content->addComponent('ItemPage', ['directory' => 'pages', 'prepend' => true]);
} else {
	$general->addComponent('ItemPage', ['directory' => 'pages']);
}

echo $general->render();
