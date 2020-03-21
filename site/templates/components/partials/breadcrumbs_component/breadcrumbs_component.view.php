<?php
namespace ProcessWire;

?>

<nav class="breadcrumbs text-sm" aria-label="Breadcrumbs">
	<ol class="flex flex-wrap gap-xxs">
		<?php
		foreach ($this->breadcrumbs as $crumb) {
			?>
			<li class="breadcrumbs__item" <?= $crumb->active ? 'aria-current="page"' : ''; ?>>
				<?php
				if (!$crumb->active && $crumb->viewable()) {
					?>
					<a class="color-inherit" href="<?= $crumb->url; ?>">
						<?= $crumb->title_short; ?>
					</a>
					<span class="color-contrast-low margin-left-xxs" aria-hidden="true">/</span>
					<?php
				} else if(!$crumb->active){
					?>
					<?= $crumb->title_short; ?>
					<span class="color-contrast-low margin-left-xxs" aria-hidden="true">/</span>
					<?php
				} else{
					?>
					<?= $crumb->title_short; ?>
					<?php
				}
				?>
			</li>
			<?php
		}
		?>
	</ol>

	<script type="application/ld+json">
		{
			"@context": "http://schema.org",
			"@type": "BreadcrumbList",
			"itemListElement": [
			<?php
			$position = 1;
			foreach ($this->breadcrumbs as $crumb) {
				if ($position > 1) {
					echo ",";
				}
				?>
				{
					"@type": "ListItem",
					"position": <?= $position; ?>,
					"item": {
						"@id": "<?= $crumb->httpUrl; ?>",
						"name": "<?= $crumb->title; ?>"
						<?php
						if ($crumb->viewable()) {
							?>
							,"url": "<?= $crumb->httpUrl; ?>"
							<?php
						}

						if ($crumb->template->hasField('main_image') && $crumb->main_image && !empty($crumb->main_image)) {
							?>
							,"image": "<?= $crumb->main_image->httpUrl; ?>"
							<?php
						}
						?>
					}
				}
				<?php
				$position++;
			}
			?>
			]
		}
	</script>
</nav>