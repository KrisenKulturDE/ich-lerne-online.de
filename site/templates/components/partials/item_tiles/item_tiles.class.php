<?php

namespace ProcessWire;

class ItemTiles extends TwackComponent {
    public function __construct($args) {
        parent::__construct($args);

        if(!$this->page->template->hasField('items') || $this->page->items->count <= 0){
            throw new ComponentNotInitializedException('ItemsCarousel', 'No items found');
        }

        $this->csrfTkn = [];
		if (wire('modules')->isInstalled('LikesCounter')) {
            $module = wire('modules')->get('LikesCounter');
        	$this->csrfTkn = $module->getCSRFToken();
        }else{
            $this->csrfTkn = $this->wire('session')->CSRF->getToken();
        }

        $parameters = [];
        if(!empty($args['cardClasses'])){
            $parameters['classes'] = $args['cardClasses'];
        }

        foreach ($this->page->items as $page) {
            $this->addComponent('PageCard', [
                'directory' => '', 
                'page' => $page, 
                'parameters' => $parameters,
                'mini' => (isset($args['miniCards']) && !!$args['miniCards'])
            ]);
        }
    }
}
