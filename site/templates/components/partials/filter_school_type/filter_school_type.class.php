<?php
namespace ProcessWire;

class FilterSchoolType extends TwackComponent {

	public function __construct($args) {
		parent::__construct($args);

		$filters = [];
		if(isset($args['active']) && is_array($args['active'])){
			$filters = $args['active'];
		}
		$this->filters = $filters;

		$activeCategories = [];
		if (isset($args['active']['school_types'])) {
			// Active keywords were transferred.
			if (is_string($args['active']['school_types'])) {
				$args['active']['school_types'] = explode(',', $args['active']['school_types']);
			}

			if (is_array($args['active']['school_types'])) {
				foreach (array_keys($args['active']['school_types'], "") as $k) {
					unset($args['active']['school_types'][$k]); // Remove empty elements
				}
				$activeCategories = $args['active']['school_types'];
			}
		}
		$this->activeCategories = $activeCategories;

		$options = $this->wire('pages')->find('template.name=school_type');
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
			$optionFilters['school_types'] = $option->idsOnClick;

			$option->urlWithParams = $this->page->url . '?' . http_build_query($optionFilters);
		}

		$this->options = $options;
	}

	public function getAjax($ajaxArgs = []) {
		return array(
			'school_types' => $this->options
		);
	}
}
