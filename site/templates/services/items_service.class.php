<?php

namespace ProcessWire;

/**
 * Provides methods for reading knowledge-items
 */
class ItemsService extends TwackComponent {

    public function __construct($args) {
        parent::__construct($args);
    }

    public function getContainerPage() {
        return wire('pages')->get('/')->children('template.name=items_container')->first();;
    }

    /**
     * Returns all knowledge-items that can be output on this page.
     * @return PageArray
     */
    public function getResults($args = array()) {
        return $this->getService('PagesService')->getResults($args, [['template', 'item']]);
    }

    public function getAjax($ajaxArgs = []) {
        return $this->getService('PagesService')->getAjax(['selector' => [['template', 'item']]]);
    }
}
