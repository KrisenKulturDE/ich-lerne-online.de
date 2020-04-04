<?php
namespace ProcessWire;

?>
	
<nav class="pagination margin-top-md" aria-label="Pagination">
	<ol class="pagination__list flex flex-wrap gap-xxs justify-center">
		<?php
		// Previous-Page-Link
		if($this->currentPage > 1){
			$filters = [];
			if(!empty($this->paginationfilters) && is_array($this->paginationfilters)){
				$filters = $this->paginationfilters;
			}
			if($this->currentPage > 2){
				$filters['page'] = $this->currentPage - 1;
			}
			?>
			<li>
				<a href="<?= $this->paginationAction . (!empty($filters) ? '?' . http_build_query($filters) : ''); ?>" class="pagination__item" aria-label="<?= __('Go to previous page'); ?>">
					<svg class="icon margin-right-xxxs" viewBox="0 0 16 16"><title><?= __('Previous'); ?></title><g stroke-width="1" stroke="currentColor"><polyline fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" points="9.5,3.5 5,8 9.5,12.5 "></polyline></g></svg>
					<span><?= __('Prev'); ?></span>
				</a>
			</li>
			<?php
		}

		// Link to first page:
		if(1 === $this->currentPage){
			?>
			<li>
				<a href="<?= $this->paginationAction . (!empty($this->paginationfilters) ? '?' . http_build_query($this->paginationfilters) : ''); ?>" class="pagination__item pagination__item--selected" aria-label="<?= sprintf(__('Current page, page %1$s'), $this->pagesCount) ?>" aria-current="page">1</a>
			</li>
			<?php
		}else{
			?>
			<li class="display@sm">
				<a href="<?= $this->paginationAction . (!empty($this->paginationfilters) ? '?' . http_build_query($this->paginationfilters) : ''); ?>" class="pagination__item" aria-label="<?= sprintf(__('Go to page %1$s'), 1) ?>">1</a>
			</li>
			<?php
		}
					
		if($this->pagesCount > 1){
			$placeholderPrevShown = false;
			$placeholderNextShown = false;
			for($i = 2; $i < $this->pagesCount; $i++){
				$filters = [];
				if(!empty($this->paginationfilters) && is_array($this->paginationfilters)){
					$filters = $this->paginationfilters;
				}
				if($i > 1){
					$filters['page'] = $i;
				}

				if($i < $this->currentPage - 2){
					if(!$placeholderPrevShown){
						?>
						<li class="display@sm" aria-hidden="true">
							<span class="pagination__item pagination__item--ellipsis">...</span>
						</li>
						<?php
					}
					$placeholderPrevShown = true;
					continue;
				}

				if($i > $this->currentPage + 2 || ($this->currentPage === 2 && $i > $this->currentPage + 3) || ($this->currentPage === 1 && $i > $this->currentPage + 4)){
					if(!$placeholderNextShown){
						?>
						<li class="display@sm" aria-hidden="true">
							<span class="pagination__item pagination__item--ellipsis">...</span>
						</li>
						<?php
					}
					$placeholderNextShown = true;
					continue;
				}

				if($i === $this->currentPage){
					?>
					<li>
						<a href="<?= $this->paginationAction . (!empty($filters) ? '?' . http_build_query($filters) : ''); ?>" class="pagination__item pagination__item--selected" aria-label="<?= sprintf(__('Current page, page %1$s'), $this->pagesCount) ?>" aria-current="page"><?= $i; ?></a>
					</li>
					<?php
				}else{
					?>
					<li class="display@sm">
						<a href="<?= $this->paginationAction . (!empty($filters) ? '?' . http_build_query($filters) : ''); ?>" class="pagination__item" aria-label="<?= sprintf(__('Go to page %1$s'), $this->pagesCount) ?>"><?= $i; ?></a>
					</li>
					<?php
				}
			}
		}

		// Link to last page:
		if ($this->pagesCount > 1) {
			$filters = [];
			if(!empty($this->paginationfilters) && is_array($this->paginationfilters)){
				$filters = $this->paginationfilters;
			}
			$filters['page'] = $this->pagesCount;

			if ($this->pagesCount === $this->currentPage) {
				?>
				<li>
					<a href="<?= $this->paginationAction . (!empty($filters) ? '?' . http_build_query($filters) : ''); ?>" class="pagination__item pagination__item--selected" aria-label="<?= sprintf(__('Current page, page %1$s'), $this->pagesCount) ?>" aria-current="page"><?= $this->pagesCount; ?></a>
				</li>
			<?php
			} else {
				?>
				<li class="display@sm">
					<a href="<?= $this->paginationAction . (!empty($filters) ? '?' . http_build_query($filters) : ''); ?>" class="pagination__item" aria-label="<?= sprintf(__('Go to page %1$s'), $this->pagesCount) ?>"><?= $this->pagesCount; ?></a>
				</li>
			<?php
			}
		}
		
		// Next-Page-Link:
		if($this->currentPage < $this->pagesCount){
			$filters = [];
			if(!empty($this->paginationfilters) && is_array($this->paginationfilters)){
				$filters = $this->paginationfilters;
			}
			$filters['page'] = $this->currentPage + 1;
			?>
			<li>
				<a href="<?= $this->paginationAction . (!empty($filters) ? '?' . http_build_query($filters) : ''); ?>" class="pagination__item" aria-label="<?= __('Go to next page'); ?>">
				<span><?= __('Next'); ?></span>
				<svg class="icon margin-left-xxxs" aria-hidden="true" viewBox="0 0 16 16"><title><?= __('Next'); ?></title><g stroke-width="1" stroke="currentColor"><polyline fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" points="6.5,3.5 11,8 6.5,12.5 "></polyline></g></svg>
				</a>
			</li>
			<?php
		}
		?>
	</ol>

	<i class="total-number"><?= sprintf(_n("One result found", "%d results found", $this->totalNumber), $this->totalNumber); ?></i>
</nav>