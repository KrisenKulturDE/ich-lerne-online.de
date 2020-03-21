<?php
namespace ProcessWire;

class FilterCategory extends TwackComponent {

	public function __construct($args) {
		parent::__construct($args);

		$filters = [];
		if(isset($args['active']) && is_array($args['active'])){
			$filters = $args['active'];
		}
		$this->filters = $filters;

		$activeCategories = [];
		if (isset($args['active']['category'])) {
			// Active keywords were transferred.
			if (is_string($args['active']['category'])) {
				$args['active']['category'] = explode(',', $args['active']['category']);
			}

			if (is_array($args['active']['category'])) {
				foreach (array_keys($args['active']['category'], "") as $k) {
					unset($args['active']['category'][$k]); // Remove empty elements
				}
				$activeCategories = $args['active']['category'];
			}
		}
		$this->activeCategories = $activeCategories;

		$options = $this->wire('pages')->find('template.name=category');
		foreach($options as &$option){
			$option->active = in_array($option->id, $this->activeCategories);

			if ($option->active) {
				// The selected keyword is active, so it should be removed when you click on it.
				$option->idsOnClick = array_diff($this->activeCategories, [$option->id]);
			} else {
				// The selected keyword is not active, but there are active keywords. When clicking, the keyword must be added.
				$active = $this->activeCategories;
				$active[] = $option->id;
				$option->idsOnClick = $active;
			}

			$optionFilters = $this->filters;
			$optionFilters['category'] = $option->idsOnClick;

			$option->urlWithParams = $this->page->url . '?' . http_build_query($optionFilters);
		}

		$this->options = $options;
	}

	public function getAjax($ajaxArgs = []) {
		return array(
			'category' => $this->options
		);
	}
}
