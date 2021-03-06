<?php

namespace ProcessWire;

?>

<article class="default_page article text-component">
	<?php
	if ($this->mainImage) {
		?>
		<a class="main_image no-underline container max-width-adaptive-md margin-bottom-lg" data-open-imagelightbox="<?= $this->mainImage->url; ?>" href="<?= $this->mainImage->url; ?>" target="_blank">
			<?php
			if ($this->page->template->hasField('dont_crop_main_image') && $this->page->dont_crop_main_image) {
				// The image should not be cropped
				echo $this->imageService->getPictureHtml(array(
					'image'     => $this->mainImage,
					'alt'       => sprintf(__('Main-image of %1$s'), $this->title),
					'loadAsync' => true,
					'default'   => array(
						'width' => 800
					),
					'media' => array(
						'(max-width: 500px)' => array(
							'width' => 500
						)
					)
				));
			} else {
				// Bring the image to 2-to-1 aspect ratio:
				
				echo $this->imageService->getPictureHtml(array(
					'image'          => $this->mainImage,
					'alt'            => sprintf(__('Main-image of %1$s'), $this->title),
					'loadAsync'      => true,
					'default'        => array(
						'width'  => 800,
						'height' => 300
					),
					'media' => array(
						'(max-width: 500px)' => array(
							'width'  => 500,
							'height' => 250
						)
					)
				));
			}

			if ($this->mainImage->caption && !empty($this->mainImage->caption . '')) {
				echo '<div class="image-caption">' . $this->mainImage->caption . '</div>';
			} ?>
		</a>
		<?php
		} ?>

	<div class="contents-container container max-width-adaptive-sm">
		<?= $this->breadcrumbs; ?>

		<?php
		if ($this->publishTimeString && $this->page->template->name !== 'default_page') {
			if ($this->authors) {
				?>
				<div class="meta">
					<?= sprintf(__('Published on %1$s from %2$s%3$s%4$s'), $this->publishTimeString, '<strong>', implode(' & ', $this->authors), '</strong>'); ?>
				</div>
				<?php
			} else {
				?>
				<div class="meta">
					<?= sprintf(__('Published on %1$s'), $this->publishTimeString); ?>
				</div>
				<?php
			}
		} ?>

		<h1 class="title <?= $this->hideTitle ? 'sr-only' : ''; ?>">
			<?= $this->title; ?>
		</h1>

		<?= $this->intro ? '<div class="text-md color-contrast-medium">' . $this->intro . '</div>' : ''; ?>

		<?php
		if ($this->childComponents) {
			foreach ($this->childComponents as $component) {
				try {
					echo $component;
				} catch (\Exception $e) {
					Twack::devEcho($e->getMessage());
				}
			}
		} ?>

		<?= $this->tags; ?>

	</div>
</article>