<?php
namespace ProcessWire;


?>
<div class="aspect-ratio ar-2-1 card__img darken">
	<?php
        if ($this->page->main_image) {
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
            ));
        }else{
			?>
			<div class="ar-content placeholder"></div>
			<?php
		}
		?>
</div>

<div class="card__content">
	<div class="text-component">
		<h4 class="card-title"><?= $this->page->title; ?></h4>

		<?php
		if(!$this->mini){
			?>
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
				</div>
			</div>
			<?php
		}
		?>

		<p class="card-text color-contrast-medium" style="font-size: var(--text-sm);">
			<?= $this->page->intro; ?>

			<?php
        if ($this->page->contents->count() > 0) {
            ?>
		  	<br/><a class="btn btn--subtle hvr-grow margin-top-xs margin-bottom-sm" href="<?= $this->page->url; ?>"><?= __('More...'); ?></a>
		  	<?php
		} 
		?>
		</p>
	</div>

	<div class="margin-top-sm card-footer">
		<span class="price-info"><?= !empty($this->page->price_info) ? $this->page->price_info : 'kostenloses Angebot'; ?></span>
		
		<a class="btn btn--primary btn--sm hvr-grow" href="<?= $this->page->link; ?>" target="_blank" rel=”nofollow”><?= __('Jump to page'); ?> &rarr;</a>

		<button class="like-button <?= $this->likeStatus ? 'active': ''; ?>" type="button" data-id="<?= $this->page->id; ?>">
			<svg class="icon" viewBox="0 0 50 50" xmlns="http://www.w3.org/2000/svg"><title>Star empty</title><path d="m25 1a1.0001 1.0001 0 0 0 -.931641.6386719l-6.166015 15.8964841-16.95312525.865235a1.0001 1.0001 0 0 0 -.58203125 1.773437l13.2011715 10.792969-4.3359371 16.376953a1.0001 1.0001 0 0 0 1.5078121 1.097656l14.259766-9.152344 14.259766 9.152344a1.0001 1.0001 0 0 0 1.507812-1.097656l-4.335937-16.376953 13.201171-10.792969a1.0001 1.0001 0 0 0 -.582031-1.773437l-16.953125-.865235-6.166015-15.8964841a1.0001 1.0001 0 0 0 -.931641-.6386719zm0 3.7636719 5.466797 14.0976561a1.0001 1.0001 0 0 0 .882812.636719l15.009766.767578-11.691406 9.560547a1.0001 1.0001 0 0 0 -.333985 1.029297l3.841797 14.513672-12.634765-8.111329a1.0001 1.0001 0 0 0 -1.082032 0l-12.634765 8.111329 3.841797-14.513672a1.0001 1.0001 0 0 0 -.333985-1.029297l-11.691406-9.560547 15.009766-.767578a1.0001 1.0001 0 0 0 .882812-.636719z"/></svg>
			<span class="message"><?= __('Like!'); ?></span>
		</button>
	</div>
</div>
