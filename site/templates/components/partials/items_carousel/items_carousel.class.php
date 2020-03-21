<?php
namespace ProcessWire;

class ItemsCarousel extends TwackComponent {

	public function __construct($args) {
		parent::__construct($args);

		$itemsService = $this->getService('ItemsService');
		$news = $itemsService->getArticles(['charLimit' => 150, 'limit' => 15]);
		$itemPages = $news->items;

		$parameters = [];
        if(!empty($args['cardClasses'])){
            $parameters['classes'] = $args['cardClasses'];
        }

		foreach ($itemPages as $page) {
			$this->addComponent('PageCard', ['directory' => '', 'page' => $page, 'parameters' => $parameters]);
		}

		$this->itemsPage = $itemsService->getContainerPage();

		$this->addScript('swiper.js', array(
            'path'     => wire('config')->urls->templates . 'assets/js/',
			'absolute' => true,
			'inline' => true
        ));
        $this->addScript('legacy/swiper.js', array(
            'path'     => wire('config')->urls->templates . 'assets/js/',
			'absolute' => true,
			'inline' => true
        ));
	}
}
