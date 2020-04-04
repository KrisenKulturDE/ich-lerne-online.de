<?php
namespace ProcessWire;

?>
	
<div class="filters_component max-width-xxl">
	<div class="parent grid gap-xs">
		<?php
		if (!empty((string)$this->schoolTypes)) {
			?>
			<div class="col col-12 col-6@xs col-4@md margin-bottom-md">
				<?= $this->schoolTypes; ?>
			</div>
			<?php
		}
		?>

		<?php
		if (!empty((string)$this->subjects)) {
			?>
			<div class="col col-12 col-6@xs col-4@md margin-bottom-md">
				<?= $this->subjects; ?>
			</div>
			<?php
		}
		?>

		<?php
		if (!empty((string)$this->category)) {
			?>
			<div class="col col-12 col-6@xs col-4@md margin-bottom-md">
				<?= $this->category; ?>
			</div>
			<?php
		}
		?>

		<?php
		if (!empty((string)$this->tags)) {
			?>
			<div class="col col-12 col-6@xs col-4@md margin-bottom-md">
				<?= $this->tags; ?>
			</div>
			<?php
		}
		?>

		<div class="col col-12 col-6@md margin-bottom-md">
			<form id="epkb_search_form margin-auto" class="epkb-search epkb-search-form-1" method="get" action="<?= $this->searchAction; ?>">
				<?php
                if (!empty($this->searchfilters) && is_array($this->searchfilters)) {
                    foreach ($this->searchfilters as $key => $value) {
                        if (is_array($value)) {
                            foreach ($value as $subvalue) {
                                ?>
							<input type="hidden" name="<?= $key; ?>[]" value="<?= $subvalue; ?>">
							<?php
                            }
                        } else {
                            ?>
						<input type="hidden" name="<?= $key; ?>" value="<?= $value; ?>">
						<?php
                        }
                    }
                }
				?>
				<div class="search-input search-input--icon-right">
					<input class="form-control width-100%" type="search" name="q" id="searchInputX" placeholder="<?= __('Enter your query'); ?>" aria-label="<?= __('Search'); ?>" value="<?= $this->q; ?>">
					<button class="search-input__btn">
						<svg class="icon" viewBox="0 0 24 24"><title><?= __('Search'); ?></title><g stroke-linecap="square" stroke-linejoin="miter" stroke-width="2" stroke="currentColor" fill="none" stroke-miterlimit="10"><line x1="22" y1="22" x2="15.656" y2="15.656"></line><circle cx="10" cy="10" r="8"></circle></g></svg>
					</button>
				</div>
			</form>
		</div>
	</div>
</div>