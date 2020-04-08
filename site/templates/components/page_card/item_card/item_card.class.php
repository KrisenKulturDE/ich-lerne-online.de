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
		$output['intro'] = $this->page->intro;
		$output['link'] = $this->page->link;

		$output['views_amount'] = $this->page->views_amount ? $this->page->views_amount : 0;
		$output['likes_amount'] = $this->page->likes_amount ? $this->page->likes_amount : 0;
		$output['relevance_boost'] = $this->page->relevance_boost ? $this->page->relevance_boost : 0;
		$output['relevance_factor'] = $this->page->relevance_factor ? $this->page->relevance_factor : 0;

		$output['target_audience'] = $this->getAjaxOf($this->page->target_audience);
		$output['school_types'] = $this->getAjaxOf($this->page->school_types);
		$output['subjects'] = $this->getAjaxOf($this->page->subjects);
		$output['category'] = $this->getAjaxOf($this->page->category);
		$output['tags'] = $this->getAjaxOf($this->page->tags);

		if(wire('input')->get('html_output')){
			$output['html'] = $this->renderView();
		}

		if(wire('input')->get('detail_contents')){
			if ($this->page->template->hasField('contents')) {
				$this->contents = $this->addComponent('ContentsComponent', ['directory' => '']);
			}
			if ($this->contents && $this->contents instanceof TwackComponent) {
				$output = array_merge($output, $this->contents->getAjax($ajaxArgs));
			}
		}

		if($this->page->main_image){
			$output['main_image'] = $this->getAjaxOf($this->page->main_image->height(300));
		}

		return $output;
	}
}
