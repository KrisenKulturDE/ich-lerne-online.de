<?php

namespace ProcessWire;

class FiltersComponent extends TwackComponent {
    public function __construct($args) {
        parent::__construct($args);

        $this->targetAudiences = $this->wire('pages')->find('template.name=target_audience');

        $this->q = '';

        if (!empty($args['filters']['q']) && is_string($args['filters']['q'])) {
            $this->q = $args['filters']['q'];
        }

        $filters = [];
        if(!empty($args['filters']) && is_array($args['filters'])){
            $filters = $args['filters'];
        }

        $searchfilters = $filters;
        if(isset($searchfilters['q'])){
            unset($searchfilters['q']);
        }

        $this->searchAction = $this->page->url;
        $this->searchfilters = $searchfilters;

        // Kategorien-Filter
        $this->addComponent('FilterCategory', [
            'directory' => 'partials', 
            'name' => 'category', 
            'active' => $filters
        ]);

        // Schulformen-Filter
        $this->addComponent('FilterSchoolType', [
            'directory' => 'partials', 
            'name' => 'schoolTypes', 
            'active' => $filters
        ]);

        // Fächer-Filter
        $this->addComponent('FilterSubject', [
            'directory' => 'partials', 
            'name' => 'subjects', 
            'active' => $filters
        ]);

        // Schlagwörter-Filter
        $this->addComponent('FilterTags', [
            'directory' => 'partials', 
            'name' => 'tags', 
            'active' => $filters
        ]);
    }
}
