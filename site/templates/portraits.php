<?php
namespace ProcessWire;

$twack = wire('modules')->get('Twack');
$general = $twack->getNewComponent('General');

$content = $twack->getComponent('mainContent');
if ($content) {
	$content->addComponent('PortraitsPage', ['directory' => 'pages']);
} else {
	$general->addComponent('PortraitsPage', ['directory' => 'pages']);
}

echo $general->render();
