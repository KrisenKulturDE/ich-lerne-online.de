<?php
namespace ProcessWire;

if ($this->childComponents && count($this->childComponents) > 0) {
	?>
	<div class="items_carousel" data-tknname="<?= $this->csrfTkn['name']; ?>" data-tknvalue="<?= $this->csrfTkn['value']; ?>">
		<div class="swiper-container" data-align="<?= $this->sliderAlign; ?>">
			<div class="swiper-wrapper">
				<?php
				foreach ($this->childComponents as $article) {
					?>
					<div class="swiper-slide">
						<?= $article; ?>
					</div>
					<?php
				}
				?>
			</div>
			<!-- Add Pagination -->
			<div class="swiper-pagination"></div>

			<!-- Navigation -->
			<div class="swiper-button-next swiper-button-black"></div>
			<div class="swiper-button-prev swiper-button-black"></div>
		</div>
	</div>
	<?php
}
?>
