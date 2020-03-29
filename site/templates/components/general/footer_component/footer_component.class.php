<?php
namespace ProcessWire;

class FooterComponent extends TwackComponent {

	public function __construct($args) {
		parent::__construct($args);

		$footerPage = wire('pages')->get('template=footer');
		if(!$footerPage->id){
			return false;
		}

		$this->navBlocks = $footerPage->link_blocks;
	}
}
