<?php
namespace ProcessWire;
?>
<div class="search_page results-wrapper margin-bottom-lg max-width-xxl">

	<?= !empty((string) $this->filters) ? $this->filters : ''; ?> 
	
	<div class="results-container padding-y-md">
		<?php
		if ($this->totalNumber) {
			?>
			<i class="total-number"><?= sprintf(_n("One result found", "%d results found", $this->totalNumber), $this->totalNumber); ?></i>
			<?php
		}
		?>
	
		<?php
		if ($this->childComponents && count($this->childComponents) > 0) {
			?>
			<div class="grid gap-xs gap-sm@md items-grid">
				<?php
				foreach ($this->childComponents as $result) {
					?>
					<div class="col col-12 col-6@sm col-4@md col-3@lg">
						<?= $result; ?>
					</div>
					<?php
				} ?>
			</div>
	
			<?php
		} else {
			?>
			<div class="alert alert-info no-results" role="alert">
				<strong><?= __('No results found'); ?></strong><br/>
				<?= __('Expand the filter settings to get more results.'); ?>
			</div>
			<?php
		}
		?>
	</div>

	<?= !empty((string) $this->pagination) ? $this->pagination : ''; ?> 
</div>