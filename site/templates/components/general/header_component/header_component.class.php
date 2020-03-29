<?php
namespace ProcessWire;

class HeaderComponent extends TwackComponent {

	public function __construct($args) {
		parent::__construct($args);

		$headerPage = wire('pages')->get('template=header');
	}
}
