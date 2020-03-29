<?php

namespace ProcessWire;

?>

<div class="item-meta margin-top-md margin-bottom-sm">
	<a class="btn btn--subtle hvr-grow" href="<?= $this->page->link; ?>" target="_blank" rel=”nofollow”><?= __('Jump to page'); ?> &rarr;</a>

	<div class="margin-top-sm">
		<div class="flex flex-wrap gap-xxs">
			<?php
			// Kategorien:
			foreach($this->page->category as $badgeitem){
				?>
				<span class="badge badge--success"><?= $badgeitem->title; ?></span>
				<?php
			}

			// Zielgruppen:
			foreach($this->page->target_audience as $badgeitem){
				?>
				<span class="badge badge--primary"><?= $badgeitem->title; ?></span>
				<?php
			}

			// Schulformen
			foreach($this->page->school_types as $badgeitem){
				if($badgeitem->id === 1073){
					// "Alle" muss nicht angezeigt werden
					continue;
				}
				?>
				<span class="badge badge--error"><?= $badgeitem->title; ?></span>
				<?php
			}

			// Schulfächer
			foreach($this->page->subjects as $badgeitem){
				if($badgeitem->id === 1074){
					// "Alle" muss nicht angezeigt werden
					continue;
				}
				?>
				<span class="badge badge--warning"><?= $badgeitem->title; ?></span>
				<?php
			}

			// Schlagworte:
			foreach($this->page->tags as $badgeitem){
				?>
				<span class="badge"><?= $badgeitem->title; ?></span>
				<?php
			}
			?>
		</div>
	</div>
</div>