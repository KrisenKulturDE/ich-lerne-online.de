<?php
namespace ProcessWire;

if ($this->page->main_image) {
    ?>
	<div class="aspect-ratio ar-2-1 card__img darken">
		<?php
			echo $this->component->getService('ImageService')->getPictureHtml(array(
				'image'          => $this->page->main_image,
				'alt'            => sprintf(__('Main-image of %1$s'), $this->page->title),
				'classes'        => array('article-image'),
				'pictureclasses' => array('ar-content'),
				'loadAsync'      => true,
				'default'        => array(
					'width'  => 320,
					'height' => 160
				),
			)); ?>
	</div>
	<?php
}
?>

<div class="card__content">
	<div class="text-component">
		<h4 class="card-title"><?= $this->page->title; ?></h4>

		<div class="margin-y-sm">
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
			<!-- <span class="badge badge--primary">Primary</span>
			<span class="badge badge--primary-light">Primary</span>
			<span class="badge badge--accent">Accent</span>
			<span class="badge badge--accent-light">Accent</span>
			<span class="badge badge--error">Error</span>
			<span class="badge badge--error-light">Error</span>
			<span class="badge badge--success">Success</span>
			<span class="badge badge--success-light">Success</span> -->
		</div>
	</div>

		<p class="card-text color-contrast-medium" style="font-size: var(--text-sm);">
			<?= $this->page->intro; ?>
		</p>
	</div>

	<div class="margin-top-sm">
		<span class="price-info"><?= !empty($this->page->price_info) ? $this->page->price_info : 'kostenloses Angebot'; ?></span>
		<?php
        if (false && !empty($this->page->contents)) {
            ?>
		  	<a class="btn btn--subtle btn--sm hvr-grow" href="<?= $this->page->url; ?>"><?= __('More...'); ?></a>
		  	<?php
		} else{
			?>
			<a class="btn btn--subtle btn--sm hvr-grow" href="<?= $this->page->link; ?>" target="_blank" rel=”nofollow”><?= __('Jump to page'); ?> &rarr;</a>
			<?php
		}
		?>
	</div>
</div>
