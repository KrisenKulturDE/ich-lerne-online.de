<?php

namespace ProcessWire;

class ItemsContainerPage extends TwackComponent {
    public function __construct($args) {
        parent::__construct($args);

        $containerPage = wire('pages')->get('template.name=items_container');
        if (!($containerPage instanceof Page) || !$containerPage->id) {
            return false;
        }

        $selector = [];
        if (!empty($args['selector']) && is_array($args['selector'])) {
            $selector = $args['selector'];
        }
        $selector[] = ['template', ['item']];

        $this->selector = $selector;

        $filters = array();

        if (wire('input')->get('category')) {
            $filters['category'] = wire('input')->get('category');
        }

        if (wire('input')->get('school_types')) {
            $filters['school_types'] = wire('input')->get('school_types');
        }

        if (wire('input')->get('subjects')) {
            $filters['subjects'] = wire('input')->get('subjects');
        }

        if (wire('input')->get('target_audience')) {
            $filters['target_audience'] = wire('input')->get('target_audience');
        }

        // Is a keyword filter set?
        if (wire('input')->get('tags')) {
            $filters['tags'] = wire('input')->get('tags');
        }

        // Is something entered in the free text search?
        if (wire('input')->get->selectorValue('q')) {
            $filters['q'] = wire('input')->get->selectorValue('q');
        }

        $this->paginationfilters = $filters;

        if (wire('input')->get->int('page')) {
            $filters['page'] = wire('input')->get->int('page');
        }

        $this->addComponent('FiltersComponent', [
            'directory' => 'partials',
            'name'      => 'filters',
            'filters'   => $this->paginationfilters
        ]);

        $this->pagesService         = $this->getService('PagesService');
        $results                    = $this->pagesService->getResults($filters, $this->selector);
        
        $resultPages                = $results->items;

        $parameters = [];
        if (!empty($args['cardClasses'])) {
            $parameters['classes'] = $args['cardClasses'];
        }

        foreach ($resultPages as $page) {
            $attributes = array(
                'data-id' => $page->id
            );
            $this->addComponent('PageCard', ['directory' => '', 'page' => $page, 'parameters' => $parameters, 'attributes' => $attributes]);
        }

        $this->addComponent('PaginationComponent', [
            'directory' => 'partials',
            'name'      => 'pagination',
            'results'   => $results,
            'parameters' => [
                'paginationfilters' => $this->paginationfilters,
                'paginationAction' => $this->page->url
            ]
        ]);
    }

    public function getAjax($ajaxArgs = []) {
        return $this->pagesService->getAjax(['selector' => $this->selector]);
    }
}
