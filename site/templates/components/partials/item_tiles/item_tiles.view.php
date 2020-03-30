<?php

namespace ProcessWire;

?>
<div class="item_tiles results-container margin-top-sm" data-request-url="<?= $this->requestUrl; ?>" data-tknname="<?= $this->csrfTkn['name']; ?>" data-tknvalue="<?= $this->csrfTkn['value']; ?>">
	<?php
    if ($this->childComponents && count($this->childComponents) > 0) {
        ?>
		<div class="grid gap-xs gap-sm@md items-grid">
			<?php
			foreach ($this->childComponents as $result) {
				?>
				<div class="col col-12 col-6@sm col-4@md col-3@xxl">
					<?= $result; ?>
				</div>
				<?php
			} ?>
		</div>
		<?php
    }
	?>
</div>