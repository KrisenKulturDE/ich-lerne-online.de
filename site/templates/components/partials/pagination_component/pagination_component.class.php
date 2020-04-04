<?php

namespace ProcessWire;

class PaginationComponent extends TwackComponent {
    public function __construct($args) {
        parent::__construct($args);

        if(empty($args['results']) || !is_object($args['results'])){
            return false;
        }

        $this->moreAvailable        = $args['results']->moreAvailable;
        $this->lastElementIndex     = $args['results']->lastElementIndex;
        $this->totalNumber          = $args['results']->totalNumber;
        $this->limit                = $args['results']->limit;
        $this->pagesCount           = $args['results']->pagesCount;
        $this->currentPage          = $args['results']->currentPage;
    }
}
