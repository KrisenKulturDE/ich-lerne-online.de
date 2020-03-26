<?php
namespace ProcessWire;

if ($this->options->count > 0) {
    ?>
	<div class="box filter_categories">
		<details class="details js-details">
			<summary class="details__summary js-details__summary" role="button">
				<span class="flex items-center">
					<svg class="icon icon--xxs margin-right-xxs" aria-hidden="true" viewBox="0 0 12 12"><path d="M2.783.088A.5.5,0,0,0,2,.5v11a.5.5,0,0,0,.268.442A.49.49,0,0,0,2.5,12a.5.5,0,0,0,.283-.088l8-5.5a.5.5,0,0,0,0-.824Z"></path></svg>
					<span style="font-weight: bold;"><?= __('Filter Categories'); ?></span>
				</span>
			</summary>
			<div class="inactive-filters details__content text-component margin-top-xs js-details__content flex flex-wrap gap-xxs">
				<?php
				foreach ($this->options->find('active=0, sort=title') as $option) {
					?>
					<a data-id="<?= $option->id; ?>" href="<?= $option->urlWithParams; ?>" class="tag badge badge--success">
						<?= $option->title; ?> <span class="close-indicator">&times;</span>
					</a>
				<?php
				} ?>
			</div>
		</details>

		<div class="active-filters margin-top-xs">
			<label style="display: block; font-size: var(--text-sm);"><?= __('Selected Categories:'); ?></label>
			<?php
			if (count($this->options->find('active=1')) > 0) {
				foreach ($this->options->find('active=1, sort=title') as $option) {
					?>
					<a data-id="<?= $option->id; ?>" href="<?= $option->urlWithParams; ?>"
					class="active tag badge badge--success">
						<?= $option->title; ?><span class="close-indicator">&times;</span>
					</a>
				<?php
				}
			} else {
				?>
					<i><?= __('No categories selected'); ?></i>
				<?php
			} ?>
		</div>
	</div>
	<?php
}
?>