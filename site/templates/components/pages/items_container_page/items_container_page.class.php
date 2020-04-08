<?php

namespace ProcessWire;

class ItemsContainerPage extends TwackComponent {
    public function __construct($args) {
        parent::__construct($args);

        $containerPage = wire('pages')->get('template.name=items_container');
        if (!($containerPage instanceof Page) || !$containerPage->id) {
            return false;
        }

        $this->breadcrumbs = $this->addComponent('BreadcrumbsComponent', ['name' => 'breadcrumbs', 'directory' => 'partials']);

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

        if (wire('input')->get->int('limit')) {
            $filters['limit'] = wire('input')->get->int('limit');
        }

        $this->addComponent('FiltersComponent', [
            'directory' => 'partials',
            'name'      => 'filters',
            'filters'   => $this->paginationfilters
        ]);

        $this->activeSort = 'relevance';
        if (wire('input')->get('sort')) {
            $this->activeSort = wire('input')->get('sort');
        }

        $sortfilters = $filters;
        if(isset($sortfilters['sort'])){
            unset($sortfilters['sort']);
        }
        $this->sortfilters = $sortfilters;

        $sortOptions = [
            [
                'label' => $this->_('Relevance'),
                'value' => '-relevance_factor',
                'key' => 'relevance'
            ],
            // [
            //     'label' => $this->_('Popularity'),
            //     'value' => '-likes_amount, -views_amount',
            //     'key' => 'popularity'
            // ],
            [
                'label' => $this->_('Newest first'),
                'value' => '-created',
                'key' => 'newest'
            ],
            [
                'label' => $this->_('Oldest first'),
                'value' => 'created',
                'key' => 'oldest'
            ],
            [
                'label' => $this->_('Alphabetical A-Z'),
                'value' => 'title',
                'key' => 'a-z'
            ],
            [
                'label' => $this->_('Alphabetical Z-A'),
                'value' => '-title',
                'key' => 'z-a'
            ]
        ];
        
        foreach($sortOptions as &$option){
            $optionFilters = $this->sortfilters;
            $optionFilters['sort'] = $option['key'];
            $option['url'] = $this->page->url . '?' . http_build_query($optionFilters);  
            $option['active'] = false; 

            if(is_string($this->activeSort) && $this->activeSort === $option['key']){
                $option['active'] = true;
                $this->activeSort = $option;
            }
        }
        if(empty($this->activeSort) || !is_array($this->activeSort)){
            $this->activeSort = [
                'label' => $this->_('Relevance'),
                'value' => '-relevance_factor',
                'key' => 'relevance'
            ];
        }
        $filters['sort'] = $this->activeSort['value'];
        
        $this->sortOptions = $sortOptions;

        $this->itemsService         = $this->getService('ItemsService');
        $results                    = $this->itemsService->getResults($filters);
        
        $resultPages                = $results->items;

        $parameters = [];
        if (!empty($args['cardClasses'])) {
            $parameters['classes'] = $args['cardClasses'];
        }

        $this->csrfTkn = [];
		if (wire('modules')->isInstalled('LikesCounter')) {
            $module = wire('modules')->get('LikesCounter');
        	$this->csrfTkn = $module->getCSRFToken();
        }else{
            $this->csrfTkn = $this->wire('session')->CSRF->getToken();
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
        $output = array(
			'title' => $this->title
		);

		if (!empty($this->datetime_unformatted)) {
			$output['datetime_from'] = $this->datetime_unformatted;
		}

		if (!empty($this->datetime_until_unformatted)) {
			$output['datetime_until'] = $this->datetime_until_unformatted;
		}

		if (!empty($this->intro)) {
			$output['intro'] = $this->intro;
		}

		if (!empty($this->page->main_image)) {
			$output['main_image'] = $this->getAjaxOf($this->page->main_image);
		}

		if (!empty($this->authors)) {
			$output['authors'] = $this->authors;
		}

		if ($this->tags && $this->tags instanceof TwackComponent) {
			$tagAjax = $this->tags->getAjax($ajaxArgs);
			if(!empty($tagAjax)){
				$output['tags'] = $tagAjax;
			}
		}

		if ($this->contents && $this->contents instanceof TwackComponent) {
			$output['contents'] = $this->contents->getAjax($ajaxArgs);
        }
        
        $pagination = $this->getComponent('pagination');
        if($pagination && !($pagination instanceof TwackNullComponent)){
            $output = array_merge($output, $pagination->getAjax($ajaxArgs));
        }

		// The component is registered under the global name "mainContent". From the template files some components are added manually.
        $output['items'] = [];
        if ($this->childComponents) {
			foreach ($this->childComponents as $component) {
				$ajax = $component->getAjax($ajaxArgs);
				if(empty($ajax)) continue;
                $output['items'][] = $ajax;
			}
        }

		return $output;
    }
}
