<?php
namespace ProcessWire;

class HomePage extends TwackComponent {
    public function __construct($args) {
        parent::__construct($args);

        $this->targetAudiences = $this->wire('pages')->find('template.name=target_audience');
        $this->textteaserSectionPage = $this->wire('pages')->get(1175);
        $this->searchAction = $this->wire('pages')->find('template.name=items_container')->first()->url;
    }
}