<?php

namespace ProcessWire;

if ($this->tabs && count($this->tabs) > 0) {
    ?>
	<div class="content_collapsible margin-y-md text-component__block <?= !empty($this->page->classes . '') ? $this->page->classes : ''; ?>" <?= $this->page->depth ? 'data-depth="' . $this->page->depth . '"' : ''; ?>>
		<?php
        if (!empty($this->title)) {
            $headingDepth = 2;
            if ($this->page->depth && intval($this->page->depth)) {
                $headingDepth = $headingDepth + intval($this->page->depth);
            } ?>
			<h<?= $headingDepth; ?> class="block-title <?= $this->page->hide_title ? 'sr-only sr-only-focusable' : ''; ?>">
				<?= $this->title; ?>
			</h<?= $headingDepth; ?>>
			<?php
		} ?>

		<ul class="accordion accordion--icon-plus js-accordion" data-animation="on" data-multi-items="on" id="<?= $this->id; ?>" style="list-style-type: none;">
			<?php
            foreach ($this->tabs as $tab) {
				?>
				  <li class="accordion__item js-accordion__item">
					<button class="reset accordion__header padding-y-sm padding-x-md js-tab-focus" type="button" id="heading-<?= $tab->id; ?>" aria-controls="<?= $tab->id; ?>">
						<span class="text-md"><?= $tab->title; ?></span>
						<em aria-hidden="true" class="accordion__icon-wrapper"><i></i></em>
					</button>

					<div id="<?= $tab->id; ?>" class="accordion__panel js-accordion__panel" aria-labelledby="heading-<?= $tab->id; ?>">
						<div class="text-component padding-top-xxxs padding-x-md padding-bottom-md">
							<?= $tab->content; ?>
						</div>
					</div>
				</li>
				<?php
            } ?>
		</ul>
	</div>
	<?php
}
?>