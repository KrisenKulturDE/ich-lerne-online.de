<?php
namespace ProcessWire;

class FilterSubject extends TwackComponent {

	public function __construct($args) {
		parent::__construct($args);

		$filters = [];
		if(isset($args['active']) && is_array($args['active'])){
			$filters = $args['active'];
		}
		$this->filters = $filters;

		$activeSubjects = [];
		if (isset($args['active']['subjects'])) {
			// Active keywords were transferred.
			if (is_string($args['active']['subjects'])) {
				$args['active']['subjects'] = explode(',', $args['active']['subjects']);
			}

			if (is_array($args['active']['subjects'])) {
				foreach (array_keys($args['active']['subjects'], "") as $k) {
					unset($args['active']['subjects'][$k]); // Remove empty elements
				}
				$activeSubjects = $args['active']['subjects'];
			}
		}
		$this->activeSubjects = $activeSubjects;

		$options = $this->wire('pages')->find('template.name=subject');
		foreach($options as &$option){
			$option->active = in_array($option->id, $this->activeSubjects);

			if ($option->active) {
				// The selected keyword is active, so it should be removed when you click on it.
				$option->idsOnClick = array_diff($this->activeSubjects, [$option->id]);
			} else {
				// The selected keyword is not active, but there are active keywords. When clicking, the keyword must be added.
				$active = $this->activeSubjects;
				$active[] = $option->id;
				$option->idsOnClick = $active;
			}

			$optionFilters = $this->filters;
			$optionFilters['subjects'] = $option->idsOnClick;

			$option->urlWithParams = $this->page->url . '?' . http_build_query($optionFilters);
		}

		$this->options = $options;
	}

	public function getAjax($ajaxArgs = []) {
		return array(
			'subjects' => $this->options
		);
	}
}
