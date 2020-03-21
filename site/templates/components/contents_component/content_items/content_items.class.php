<?php

namespace ProcessWire;

class ContentItems extends TwackComponent {
    public function __construct($args) {
        parent::__construct($args);

        $contents = '';
        if (isset($args['type']) && $args['type'] == 'tiles') {
            $contents = $this->addComponent('ItemTiles', ['directory' => 'partials', 'cardClasses' => 'result-card']);
        } else {
            $contents = $this->addComponent('ItemsCarousel', ['directory' => 'partials', 'cardClasses' => 'result-card']);
        }

        // Check if there is really outputable content available (HTML string not empty):
        $this->contentAvailable = false;
        if (!empty((string) $contents)) {
            $this->contentAvailable = true;
        }

        // The title can be set by $args or by field "title":
        $this->title = $this->_('Knowledge Items');
        if (isset($args['title'])) {
            $this->title = $args['title'];
        } elseif ($this->page->template->hasField('title') && !empty($this->page->title)) {
            $this->title = $this->page->title;
        }
    }

    public function getAjax($ajaxArgs = []) {
        $output = array();

        if ($this->childComponents) {
            foreach ($this->childComponents as $component) {
                $ajax = $component->getAjax($ajaxArgs);
                if (empty($ajax)) {
                    continue;
                }
                $output = array_merge($output, $ajax);
            }
        }

        return $output;
    }
}
