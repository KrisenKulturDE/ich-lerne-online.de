<?php

namespace ProcessWire;

class LikesCounter extends WireData implements Module {
    const fieldnames = array('likes_amount', 'views_amount', 'relevance_boost', 'relevance_factor');

    protected $_likes;

    public static function getModuleInfo() {
        return array(
            'title'    => __('Likes Counter'),
            'author'   => 'Sebastian Schendel',
            'version'  => '1.0.0',
            'summary'  => __('Tracks a counter of likes per page and offers functions to like/unlike'),
            'singular' => true,
            'autoload' => true,
            'icon'     => 'thumbs-o-up',
            'requires' => array('PHP>=5.5.3', 'ProcessWire>=3.0.0')
        );
    }

    public function ___install() {
        $flags = Field::flagSystem + Field::flagAccessAPI + Field::flagAutojoin + Field::flagAccess + Field::flagAccessEditor;

        $field               = new Field();
        $field->type         = $this->modules->get('FieldtypeInteger');
        $field->name         = 'likes_amount';
        $field->label        = $this->_('Likes');
        $field->flags        = $flags;
        $field->icon         = 'star-o';
        $field->collapsed    = Inputfield::collapsedNever;
        $field->defaultValue = 0;
        $field->initValue    = 0;
        $field->min          = 0;
        $field->columnWidth  = '25';
        $field->viewRoles    = ['beitrags-redakteur', 'redakteur', 'admin'];
        $field->editRoles    = [];
        $field->notes        = 'Steigt an, wenn jemand im Frontend diese Seite markiert.';
        $field->save();

        $field               = new Field();
        $field->type         = $this->modules->get('FieldtypeInteger');
        $field->name         = 'views_amount';
        $field->label        = $this->_('Views');
        $field->flags        = $flags;
        $field->icon         = 'eye';
        $field->collapsed    = Inputfield::collapsedNever;
        $field->defaultValue = 0;
        $field->initValue    = 0;
        $field->min          = 0;
        $field->columnWidth  = '25';
        $field->viewRoles    = ['beitrags-redakteur', 'redakteur', 'admin'];
        $field->editRoles    = [];
        $field->notes        = 'Steigt an, wenn jemand die Detailseite besucht.';
        $field->save();

        $field               = new Field();
        $field->type         = $this->modules->get('FieldtypeInteger');
        $field->name         = 'relevance_boost';
        $field->label        = $this->_('Relevance-Boost');
        $field->flags        = $flags;
        $field->icon         = 'rocket';
        $field->collapsed    = Inputfield::collapsedNever;
        $field->defaultValue = 0;
        $field->initValue    = 0;
        $field->columnWidth  = '25';
        $field->viewRoles    = ['beitrags-redakteur', 'redakteur', 'admin'];
        $field->editRoles    = ['redakteur', 'admin'];
        $field->notes        = 'Je höher der Wert, desto weiter vorne wird die Seite in der Listenansicht einsortiert';
        $field->save();

        $field               = new Field();
        $field->type         = $this->modules->get('FieldtypeInteger');
        $field->name         = 'relevance_factor';
        $field->label        = $this->_('Relevance-Factor');
        $field->flags        = $flags;
        $field->icon         = 'exclamation-circle';
        $field->collapsed    = Inputfield::collapsedNever;
        $field->defaultValue = 0;
        $field->initValue    = 0;
        $field->columnWidth  = '25';
        $field->viewRoles    = ['beitrags-redakteur', 'redakteur', 'admin'];
        $field->editRoles    = [];
        $field->notes        = 'Automatisch aufgrund von Likes, Views und Boost generiert. Je höher der Wert, desto weiter vorne wird die Seite in der Listenansicht einsortiert.';
        $field->save();
    }

    public function ___uninstall() {
        foreach (self::fieldnames as $fieldname) {
            $field = $this->wire('fields')->get($fieldname);
            if (!($field instanceof Field) || $field->name != $fieldname) {
                continue;
            }

            $field->flags = Field::flagSystemOverride;
            $field->flags = 0;
            $field->save();

            foreach ($this->wire('templates') as $template) {
                if (!$template->hasField($fieldname)) {
                    continue;
                }
                $template->fieldgroup->remove($field);
                $template->fieldgroup->save();
            }

            $this->wire('fields')->delete($field);
        }
    }

    public function init() {
        $this->addHook('LazyCron::everyHour', $this, 'recalculateRelevanceFactor');
        $this->pages->addHookAfter('saveReady', $this, 'hookPageSaveReady');
    }

    public function getLikesAmount(Page $page) {
        if (!$page->id) {
            return false;
        }

        if (!$page->template->hasField('likes_amount')) {
            return false;
        }

        $currentAmount = $page->likes_amount;
        if (is_object($currentAmount)) {
            return false;
        }

        if (!is_int($currentAmount)) {
            $currentAmount = intval($currentAmount);
        }

        return $currentAmount;
    }

    /**
     * Returns true, if currentUser has already liked the page. If guest, look at the session
     */
    public function likeStatus(Page $page, $refresh = false) {
        if (!$page->id) {
            return false;
        }

        return in_array($page->id, $this->getLikes($refresh));
    }

    /**
     * Returns the ids of all pages that the user already liked
     */
    public function getLikes($refresh = false) {
        if (is_array($this->_likes) && !$refresh) {
            return $this->_likes;
        }

        $this->_likes = wire('session')->getValFor($this, 'likes', []);
        if (!is_array($this->_likes)) {
            $this->_likes = [];
        }

        return $this->_likes;
    }

    public function like(Page $page) {
        if(!$this->isCSRFValid()){
            return false;
        }

        $currentAmount = $this->getLikesAmount($page);
        if (!is_int($currentAmount)) {
            return false;
        }

        if ($this->likeStatus($page)) {
            // Page was already liked
            return true;
        }

        $of = $page->of();
        $page->of(false);
        $page->likes_amount = $currentAmount + 1;
        $success            = $page->save('likes_amount', ['quiet' => true]);
        $page->of($of);

        // Save Like to session:
        $likes   = $this->getLikes(true);
        $likes[] = $page->id;
        wire('session')->setFor($this, 'likes', $likes);

        return $success;
    }

    public function unlike(Page $page) {
        if(!$this->isCSRFValid()){
            return false;
        }

        $currentAmount = $this->getLikesAmount($page);
        if (!is_int($currentAmount) || $currentAmount < 1) {
            return false;
        }

        if (!$this->likeStatus($page)) {
            // Page is not liked
            return true;
        }

        $of = $page->of();
        $page->of(false);
        $page->likes_amount = $currentAmount - 1;
        $success            = $page->save('likes_amount', ['quiet' => true]);
        $page->of($of);

        $likes = $this->getLikes(true);
        $index = array_search($page->id, $likes);
        if ($index === false) {
            return false;
        }
        unset($likes[$index]);
        wire('session')->setFor($this, 'likes', $likes);

        return $success;
    }

    public function getViewsAmount(Page $page) {
        if (!$page->id) {
            return false;
        }

        if (!$page->template->hasField('views_amount')) {
            return false;
        }

        $currentAmount = $page->views_amount;
        if (is_object($currentAmount)) {
            return false;
        }

        if (!is_int($currentAmount)) {
            $currentAmount = intval($currentAmount);
        }

        return $currentAmount;
    }

    public function trackView(Page $page) {
        $currentAmount = $this->getViewsAmount($page);
        if (!is_int($currentAmount)) {
            return false;
        }

        $of = $page->of();
        $page->of(false);
        $page->views_amount = $currentAmount + 1;
        $success            = $page->save('views_amount', ['quiet' => true]);
        $page->of($of);

        return $success;
    }

    public function recalculateRelevanceFactor(HookEvent $event) {
        $pages = wire('pages')->find('template.name=item');
        foreach ($pages as $page) {
            $this->calculateRelevance($page);
        }
    }

    public function hookPageSaveReady(HookEvent $event) {
        $page = $event->arguments[0];

        // Fill in values before saving the page:
        if ($page->template->hasField('relevance_factor')) {
            $factor = $this->calculateRelevance($page, false);
            if (is_integer($factor)) {
                $page->relevance_factor = $factor;
            }
        }
    }

    public function calculateRelevance(Page $page, $save = true) {
        if (!$page->id) {
            return false;
        }

        if (!$page->template->hasField('relevance_boost')) {
            return false;
        }

        $views = $this->getViewsAmount($page);
        $likes = $this->getLikesAmount($page);
        if (!is_int($views) || !is_int($likes)) {
            return false;
        }

        $boost = $page->relevance_boost;

        $relevanceFactor = (int) $boost + $views;
        $relevanceFactor += $likes * 2;
        if (is_integer($boost) && $boost !== 0) {
            $relevanceFactor *= $boost;
        }

        if (!$save) {
            return $relevanceFactor;
        }

        $of = $page->of();
        $page->of(false);
        $page->relevance_factor = $relevanceFactor;
        $page->save('relevance_factor', ['quiet' => true]);
        $page->of($of);

        return $relevanceFactor;
    }

    public function isCSRFValid(){
        // So gehts weiter CSrf wird nicht richtig validiert, success wird nicht behandelt
        return $this->wire('session')->CSRF->hasValidToken('likes_counter', false);
    }

    public function getCSRFToken(){
        return $this->wire('session')->CSRF->getToken('likes_counter');
    }
}
