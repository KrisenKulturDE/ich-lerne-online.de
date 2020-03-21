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
        if(isset($filters['q'])){
            unset($filters['q']);
        }

        $this->searchAction = $this->page->url;
        $this->searchfilters = $filters;

        // Kategorien-Filter
        $this->addComponent('FilterCategory', [
            'directory' => 'partials', 
            'name' => 'category', 
            'active' => (!empty($args['filters']) && is_array($args['filters']) ? $args['filters'] : [])
        ]);

        // Schulformen-Filter
        $this->addComponent('FilterSchoolType', [
            'directory' => 'partials', 
            'name' => 'schoolTypes', 
            'active' => (!empty($args['filters']) && is_array($args['filters']) ? $args['filters'] : [])
        ]);

        // Fächer-Filter
        $this->addComponent('FilterSubject', [
            'directory' => 'partials', 
            'name' => 'subjects', 
            'active' => (!empty($args['filters']) && is_array($args['filters']) ? $args['filters'] : [])
        ]);

        // Schlagwörter-Filter
        $this->addComponent('FilterTags', [
            'directory' => 'partials', 
            'name' => 'tags', 
            'active' => (!empty($args['filters']) && is_array($args['filters']) ? $args['filters'] : [])
        ]);
    }
}
