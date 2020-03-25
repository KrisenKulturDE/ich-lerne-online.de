<?php

namespace ProcessWire;

class PagesService extends TwackComponent {
    public function __construct($args) {
        parent::__construct($args);
    }

    /**
     * Returns all pages that can be output on this page.
     * @return PageArray
     */
    public function getResults($args = array(), $selector = array()) {
        $output   = new \StdClass();
        $results  = new PageArray();

        if (isset($args['sort'])) {
            $selector[] = ['sort', $args['sort']];
        } else {
            $selector[] = ['sort', '-datetime_from'];
        }

        // Filtering by keywords:
        $keywordFieldnames = ['tags', 'target_audience', 'category', 'school_types', 'subjects'];
        foreach($keywordFieldnames as $fieldname){
            if (isset($args[$fieldname])) {
                if (is_string($args[$fieldname])) {
                    $args[$fieldname] = explode(',', $args[$fieldname]);
                }
    
                if (is_array($args[$fieldname])) {
                    $selector[] = [$fieldname, $args[$fieldname]];
                }
            }
        }

        // Filtering by free text:
        $results = new PageArray();
        if (isset($args['q']) && is_string($args['q'])) {
            // Sort query-matches in title and name higher than matches in other fields:
            $titleSelector = $selector;
            $titleSelector[] = ['title|name', '%=', $args['q'], 'text'];
            $results = $this->wire('pages')->find($titleSelector);

            $selector[] = ['title|name|intro|contents.text', '%=', $args['q'], 'text'];
            $secondaryResults = $this->wire('pages')->find($selector);
            $secondaryResults->removeItems($results);
            $results->add($secondaryResults);
        }else{
            $results = $this->wire('pages')->find($selector);
        }

        // Store original number of articles without limit:
        $output->totalNumber = $results->count;

        // The index of the last element:
        $output->lastElementIndex = 0;

        $sortSelector = [];
        if (!isset($args['limit']) || !is_int($args['limit']) || $args['limit'] < 0) {
            $args['limit'] = 12;
        }
        $sortSelector[]                  = ['limit', '=', $args['limit'], 'int'];
        $output->limit = $args['limit'];

        if(isset($args['page']) && is_int($args['page']) && $args['page'] > 1){
            $args['start'] = ($args['page'] - 1) * $args['limit'];
        }

        if (isset($args['start'])) {
            $sortSelector[]                  = ['start', '=', $args['start'], 'int'];
            $output->lastElementIndex    = intval($args['start']);
            $output->currentPage = (int) ceil((intval($args['start']) + 1) / $output->limit);
        } elseif (isset($args['offset'])) {
            $sortSelector[]                  = ['start', '=', $args['offset'], 'int'];
            $output->lastElementIndex    = intval($args['offset']);
            $output->currentPage = (int) ceil((intval($args['offset']) + 1) / $output->limit);
        } else {
            $sortSelector[] = ['start', 0];
            $output->currentPage = 1;
        }

        $output->lastElementIndex    = (int) $output->lastElementIndex + intval($args['limit']);
        $output->pagesCount = (int) ceil($output->totalNumber / $output->limit);

        // Twack::devEcho($output);

        $results = $results->find($sortSelector);

        // Are there any more posts that can be downloaded?
        $output->moreAvailable = $output->lastElementIndex + 1 < $output->totalNumber;

        // Prepare args for the overview pages service:
        if (isset($args['charLimit'])) {
            $args['limit'] = $args['charLimit'];
        } else {
            unset($args['limit']);
        }
        $results = $this->format($results, $args);

        $output->items = $results;

        return $output;
    }

    public function getAjax($ajaxArgs = array()) {
        $ajaxOutput = array();

        $args = wire('input')->post('args');
        if (!is_array($args)) {
            $args = array();
        }

        // Is a tag filter set?
        if (wire('input')->get('tags')) {
            $args['tags'] = wire('input')->get('tags');
        }

        // Is something entered in the free text search?
        if (wire('input')->get('q')) {
            $args['query'] = wire('input')->get('q');
        }

        if (wire('input')->get('limit')) {
            $args['limit'] = wire('input')->get('limit');
        }

        if (wire('input')->get('start')) {
            $args['start'] = wire('input')->get('start');
        } elseif (wire('input')->get('offset')) {
            $args['start'] = wire('input')->get('offset');
        }

        $selector = [];
        if(isset($ajaxArgs['selector']) && is_array($ajaxArgs['selector'])){
            $selector = $ajaxArgs['selector'];
        }

        $args['charLimit']                       = 150;
        $result                                  = $this->getResults($args, $selector);
        $ajaxOutput['totalNumber']               = $result->totalNumber;
        $ajaxOutput['moreAvailable']             = $result->moreAvailable;
        $ajaxOutput['lastElementIndex']          = $result->lastElementIndex;

        // Deliver HTML card for each post:
        $ajaxOutput['items'] = array();
        foreach ($result->items as $item) {
            $component = $this->addComponent('PageCard', ['directory' => '', 'page' => $item]);
            if ($component instanceof TwackNullComponent) {
                continue;
            }

            $ajaxOutput['items'][] = $component->getAjax($ajaxArgs);
        }

        return $ajaxOutput;
    }

    public function format(PageArray $pages, $args = array()) {
        foreach ($pages as &$page) {
            // Check whether the post is visible to the user:
            if (!$page->viewable()) {
                $pages->remove($page);
            }

            if (isset($args['limit']) && $page->template->hasField('intro')) {
                $limit  = $args['limit'];
                $endstr = ' …';
                if (isset($args['endstr'])) {
                    $endstr = $args['endstr'];
                }
                $page->intro = Twack::wordLimiter($page->intro, $limit, $endstr);
            }
        }
        return $pages;
    }

    public function formatPage(Page $page, $args = array()) {
        // Check whether the post is visible to the user:
        if (!$page->viewable()) {
            return false;
        }

        if (isset($args['limit']) && $page->template->hasField('intro')) {
            $limit  = $args['limit'];
            $endstr = ' …';
            if (isset($args['endstr'])) {
                $endstr = $args['endstr'];
            }
            $page->intro = Twack::wordLimiter($page->intro, $limit, $endstr);
        }

        return $page;
    }
}
