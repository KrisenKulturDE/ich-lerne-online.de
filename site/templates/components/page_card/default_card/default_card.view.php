<?php
namespace ProcessWire;

if ($this->page->main_image) {
    ?>
	<div class="aspect-ratio ar-2-1 card__img">
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
		<p class="card-text color-contrast-medium" style="font-size: var(--text-sm);">
			<?= $this->page->intro; ?>
		</p>
	</div>

	<div class="margin-top-sm">
		<?php
        if (false && !empty($this->page->contents)) {
            ?>
		  	<a class="btn btn--subtle btn--sm hvr-grow" href="<?= $this->page->url; ?>"><?= __('More...'); ?></a>
		  	<?php
		} else{
			?>
			<a class="btn btn--subtle btn--sm hvr-grow" href="<?= $this->page->link; ?>" target="_blank" rel=”nofollow”><?= __('Jump to page'); ?></a>
			<?php
		}
		?>
    </div>
</div>
