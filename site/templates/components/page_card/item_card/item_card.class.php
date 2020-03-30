<?php
namespace ProcessWire;

class ItemCard extends TwackComponent {

	public function __construct($args) {
        parent::__construct($args);

        $this->date = $this->page->created;
        if ($this->page->template->hasField('datetime_from')) {
            $this->date = $this->page->getUnformatted('datetime_from');
		}
		
		$this->mini = isset($args['mini']) && !!$args['mini'];

        $this->likeStatus = false;
        if (wire('modules')->isInstalled('LikesCounter')) {
            $module           = wire('modules')->get('LikesCounter');
            $this->likeStatus = $module->likeStatus($this->page);
        }
    }

	public function getAjax($ajaxArgs = []){
		$output = $this->getAjaxOf($this->page);
		$output['datetime_from'] = $this->date;
		$output['intro'] = $this->page->intro;

		if(wire('input')->get('htmlOutput')){
			$output['html'] = $this->renderView();
		}

		if($this->page->main_image){
			$output['main_image'] = $this->getAjaxOf($this->page->main_image->height(300));
		}

		return $output;
	}
}
