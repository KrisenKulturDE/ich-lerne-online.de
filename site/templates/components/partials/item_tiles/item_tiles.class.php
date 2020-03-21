<?php

namespace ProcessWire;

class ItemTiles extends TwackComponent {
    public function __construct($args) {
        parent::__construct($args);

        $filters = array(
            'charLimit' => 150
        );

        $this->itemsService      = $this->getService('ItemsService');
        $items                   = $this->itemsService->getItems($filters);
        $this->moreAvailable        = $items->moreAvailable;
        $this->lastElementIndex     = $items->lastElementIndex;
        $this->totalNumber          = $items->totalNumber;
        $itemPages              = $items->items;

        $parameters = [];
        if(!empty($args['cardClasses'])){
            $parameters['classes'] = $args['cardClasses'];
        }

        foreach ($itemPages as $page) {
            $this->addComponent('PageCard', ['directory' => '', 'page' => $page, 'parameters' => $parameters]);
        }

        $this->itemsPage = $this->itemsService->getContainerPage();
        $this->requestUrl = '/api/page' . $this->itemsPage->url;

        $this->addScript('masonry.js', array(
            'path'     => wire('config')->urls->templates . 'assets/js/',
            'absolute' => true
        ));
        $this->addScript('legacy/masonry.js', array(
            'path'     => wire('config')->urls->templates . 'assets/js/',
            'absolute' => true
        ));
    }

    public function getAjax($ajaxArgs = []) {
        return $this->itemsService->getAjax($ajaxArgs);
    }
}
