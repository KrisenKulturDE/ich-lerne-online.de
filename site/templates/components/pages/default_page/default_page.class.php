<?php
namespace ProcessWire;

class DefaultPage extends TwackComponent {

	public function __construct($args) {
		parent::__construct($args);

		$this->imageService = $this->getService('ImageService');

		// Content can be added to the global component 'mainContent':
		$this->twack->makeComponentGlobal($this, 'mainContent');

		$this->singleimageModalId = $this->getGlobalParameter('singleimageModalId');

		$this->breadcrumbs = $this->addComponent('BreadcrumbsComponent', ['name' => 'breadcrumbs', 'directory' => 'partials']);

		$this->title = $this->page->title;
		if ($this->page->template->hasField('headline') && !empty((string) $this->page->headline)) {
			$this->title = $this->page->headline;
		}
		$this->hideTitle = false;

		if ($this->page->template->hasField('intro') && !empty((string) $this->page->intro)) {
			$this->intro = $this->page->intro;
		}

		if ($this->page->template->hasField('main_image') && $this->page->main_image) {
			$this->mainImage = $this->page->main_image;
		}

		if ($this->page->template->hasField('authors') && $this->page->authors instanceof PageArray) {
			$authors = array();
			foreach ($this->page->authors as $autor) {
				$authors[] = $autor->first_name . ' ' . $autor->surname;
			}
			if (count($authors) > 0) {
				$this->authors = $authors;
			}
		}

		$this->tags = $this->addComponent('tagsField', ['directory' => 'partials', 'name' => 'tags']);

		if ($this->page->template->hasField('contents')) {
			$this->contents = $this->addComponent('ContentsComponent', ['directory' => '']);
		}

		if (wire('modules')->isInstalled('LikesCounter')) {
            $module = wire('modules')->get('LikesCounter');
       		$module->trackView($this->page);
        }
        
	}

	public function getAjax($ajaxArgs = []) {
		$output = array(
			'title' => $this->title
		);

		if (!empty($this->datetime_unformatted)) {
			$output['datetime_from'] = $this->datetime_unformatted;
		}

		if (!empty($this->datetime_until_unformatted)) {
			$output['datetime_until'] = $this->datetime_until_unformatted;
		}

		if (!empty($this->intro)) {
			$output['intro'] = $this->intro;
		}

		if (!empty($this->page->main_image)) {
			$output['main_image'] = $this->getAjaxOf($this->page->main_image);
		}

		if (!empty($this->authors)) {
			$output['authors'] = $this->authors;
		}

		if ($this->tags && $this->tags instanceof TwackComponent) {
			$tagAjax = $this->tags->getAjax($ajaxArgs);
			if(!empty($tagAjax)){
				$output['tags'] = $tagAjax;
			}
		}

		if ($this->contents && $this->contents instanceof TwackComponent) {
			$output['contents'] = $this->contents->getAjax($ajaxArgs);
		}

		// The component is registered under the global name "mainContent". From the template files some components are added manually.
		if ($this->childComponents) {
			foreach ($this->childComponents as $component) {
				$ajax = $component->getAjax($ajaxArgs);
				if(empty($ajax)) continue;
				$output = array_merge($output, $ajax);
			}
		}

		return $output;
	}
}
