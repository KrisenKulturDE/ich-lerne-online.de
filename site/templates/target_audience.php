<?php
namespace ProcessWire;

$twack = wire('modules')->get('Twack');
$general = $twack->getNewComponent('General');
$content = $twack->getComponent('mainContent');
// $content->addComponent('ContentItems', ['directory' => 'contents_component', 'title' => '', 'type' => 'tiles']);
$content->addComponent('ItemsContainerPage', ['directory' => 'pages', 'selector' => ['target_audience' => wire('page')->id]]);
echo $general->render();
