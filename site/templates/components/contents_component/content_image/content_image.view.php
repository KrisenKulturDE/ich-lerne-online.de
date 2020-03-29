<?php

namespace ProcessWire;

if ($this->page->image) {
    ?>
	<div class="content_image single_image text-component__block" <?= $this->page->depth ? 'data-depth="' . $this->page->depth . '"' : ''; ?>>
		<?php
        if (!empty($this->page->title)) {
            $headingDepth = 2;
            if ($this->page->depth && intval($this->page->depth)) {
                $headingDepth = $headingDepth + intval($this->page->depth);
            } ?>
			<h<?= $headingDepth; ?> class="block-title <?= $this->page->hide_title ? 'sr-only sr-only-focusable' : ''; ?>">
				<?= $this->page->title; ?>
			</h<?= $headingDepth; ?>>
			<?php
        } ?>

        <a class="image-wrapper no-underline <?= !empty($this->page->classes . '') ? $this->page->classes : ''; ?>" data-open-imagelightbox="<?= $this->page->image->url; ?>" href="<?= $this->page->image->url; ?>" target="_blank">
            <figure class="text-component__block">
                <?php
                echo $this->component->getService('ImageService')->getPictureHtml(array(
                    'image' => $this->page->image,
                    'loadAsync' => true,
                    'default' => array(
                        'width' => 800
                    ),
                    'media' => array(
                        '(max-width: 500px)' => array(
                            'width' => 500
                        )
                    )
                ));

                if ($this->page->image->caption && !empty($this->page->image->caption . '')) {
                    echo '<figcaption>' . $this->page->image->caption . '</figcaption>';
                } 
                ?>
            </figure>
		</div>
    </a>
	<?php
}
?>
