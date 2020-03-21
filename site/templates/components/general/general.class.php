<?php

namespace ProcessWire;

class General extends TwackComponent {
    public function __construct($args) {
        parent::__construct($args);

        // Additional meta data is collected here:
        $this->metas = new WireData();

        // general should be globally available
        $this->twack->makeComponentGlobal($this, 'general');

        $this->addStyle('main.css', array(
            'path'     => wire('config')->urls->templates . 'assets/css/',
            'absolute' => true
        ));

        $this->addScript('general.js', array(
            'path'     => wire('config')->urls->templates . 'assets/js/',
            'absolute' => true
        ));
        $this->addScript('legacy/general.js', array(
            'path'     => wire('config')->urls->templates . 'assets/js/',
            'absolute' => true
        ));

        // Add cookie scripts:
        $this->addScript('cookies.js', array(
            'path'     => wire('config')->urls->templates . 'assets/js/',
            'absolute' => true
        ));
        $this->addScript('legacy/cookies.js', array(
            'path'     => wire('config')->urls->templates . 'assets/js/',
            'absolute' => true
        ));

        // Custom Dev output
        $devOutput = $this->addComponent('DevOutput', ['globalName' => 'dev_output']);
        $this->twack->registerDevEchoComponent($devOutput);

        // Create Layout Components:
        $this->addComponent('HeaderComponent', ['globalName' => 'header']);
        $this->addComponent('FooterComponent', ['globalName' => 'footer']);

        $this->addComponent('FormsComponent', ['globalName' => 'forms', 'directory' => '']);

        $this->addComponent('DefaultPage', ['directory' => 'pages']);
        
        if($this->wire('config')->noindex === true && $this->page->template->hasField('seo')){
            $this->page->seo->robots_noIndex = true;
            $this->page->seo->robots_noFollow = true;

            $field = $this->wire('fields')->get('name=seo');
            $field->robots_noIndex = 1;
            $field->robots_noFollow = 1;
            $field->sitemap_include = 0;
            $field->save();
        }
    }

    /**
     * Adds an additional meta tag
     * @param string $metatag  	Metatag string (including html) 
     */
    public function addMeta($metaname, $metatag) {
        if (is_string($metaname) && !empty($metaname) && is_string($metatag) && !empty($metatag)) {
            $this->metas->{$metaname} = $metatag;
        }
    }

    public function getAjax($ajaxArgs = []) {

        if(!empty($this->wire('input')->get->text('showOnly'))){
            $ajaxArgs['showOnly'] = $this->wire('input')->text('showOnly');
        }

        $output = $this->getAjaxOf($this->page);

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
