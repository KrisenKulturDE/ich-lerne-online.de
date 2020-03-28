<?php
namespace ProcessWire;
?>
<div class="search_page results-wrapper margin-bottom-lg max-width-xxl">

	<?= !empty((string) $this->filters) ? $this->filters : ''; ?> 
	
	<div class="results-container padding-bottom-md" data-tknname="<?= $this->csrfTkn['name']; ?>" data-tknvalue="<?= $this->csrfTkn['value']; ?>">
		<div class="sort-wrapper margin-bottom-sm">
			<!-- <div class="alert alert--warning alert--is-visible js-alert" role="alert">
				<div class="flex items-center">
					<svg aria-hidden="true" class="icon margin-right-xxxs" fill="currentColor" viewBox="0 0 50 50" xmlns="http://www.w3.org/2000/svg"><title>star icon</title><path d="m10.2 48.6c-.2 0-.4-.1-.6-.2-.3-.2-.5-.7-.4-1.1l4.4-16.4-13.2-10.7c-.4-.2-.5-.7-.4-1.1s.5-.7.9-.7l17-.9 6.1-15.9c.2-.3.6-.6 1-.6s.8.3.9.6l6.1 15.9 17 .9c.4 0 .8.3.9.7s0 .8-.3 1.1l-13.2 10.7 4.4 16.4c.1.4 0 .8-.4 1.1-.3.2-.8.3-1.1 0l-14.3-9.2-14.3 9.2c-.2.2-.3.2-.5.2z" fill="currentColor"/></svg>
					<p>Helfen Sie uns dabei, die Qualität der Ergebnisse zu verbessern! Markieren Sie alle Beiträge, die Ihnen geholfen haben, mit einem Stern!</p>
				</div>
			</div> -->
			
			<nav class="dropdown js-dropdown">
				<ul>
					<li class="dropdown__wrapper">
						<button type="button" class="btn btn--subtle btn--sm dropdown__trigger inline-flex items-center">
							<span><?= __('Sort by'); ?><?= !empty($this->activeSort['label']) ? ': ' . $this->activeSort['label'] :'' ; ?></span>
							<svg aria-hidden="true" class="icon margin-left-xxxs" viewBox="0 0 16 16"><polyline fill="none" stroke-width="1" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" points="3.5,6.5 8,11 12.5,6.5 "></polyline></svg>
						</button>

						<ul class="dropdown__menu" aria-label="submenu">
							<?php
							foreach($this->sortOptions as $option){
								?>
								<li><a href="<?= $option['url']; ?>" class="dropdown__item"><?= $option['label']; ?></a></li>
								<?php
							}
							?>
						</ul>
					</li>
				</ul>
			</nav>
		</div>
	
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