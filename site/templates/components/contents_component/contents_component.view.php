<?php
namespace ProcessWire;

if (wireCount($this->childComponents) > 0) {
	?>
	<div class="contents_component margin-y-lg">
		<div class="content-block max-width-adaptive-md">
			<?php
			$firstFlag = true;
			$subelementActiveFlag = false;
			foreach ($this->childComponents as $contentComponent) {
				$page = $contentComponent->getPage();
				if ($page->depth == 0) {
					if ($subelementActiveFlag) {
						echo "</div>";
					}
					$subelementActiveFlag = false;

					if (!$firstFlag) {
						// Each top level element gets its own content block.
						echo "</div>";
						echo "<div class=\"content-block max-width-adaptive-sm\">";
					}
				}
				$firstFlag = false;


				if ($page->depth > 0) {
					if (!$subelementActiveFlag) {
						echo "<div class=\"grid gap-sm\">";
					}
					$subelementActiveFlag = true;

					// The width of the bootstrap columns is determined from the grid_width field at the RepeaterMatrix element:
					$gridClasses = 'col col-12 col-12 col-6@md';
					if ($page->template->hasField('grid_width') && $page->grid_width && is_object($page->grid_width->first()) && $page->grid_width->first()->id) {
						$id = $page->grid_width->first()->id;
						if ($id == 2) {
							// half
							$gridClasses = 'col col-12 col-6@md';
						} elseif ($id == 3) {
							// One third
							$gridClasses = 'col col-12 col-6@md col-4@lg';
						} elseif ($id == 4) {
							// Two thirds
							$gridClasses = 'col col-12 col-6@md col-8@lg';
						} elseif ($id == 5) {
							// A quarter
							$gridClasses = 'col col-12 col-6@md col-3@lg';
						} elseif ($id == 6) {
							// Two quarters
							$gridClasses = 'col col-12 col-6@md col-6@lg';
						} elseif ($id == 7) {
							// Three quarters
							$gridClasses = 'col col-12 col-6@md col-9@lg';
						}
					}

					echo "<div class=\"content-sub-block {$gridClasses}\">";
					echo $contentComponent;
					echo "</div>";
				} else {
					echo $contentComponent;
				}
			}

			if ($subelementActiveFlag) {
				echo "</div>";
			}
			?>
		</div>
	</div>
<?php
}
