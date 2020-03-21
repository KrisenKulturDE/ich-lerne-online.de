<section class="bg-contrast-lower article text-component">
    <?php
    if ($this->page->text) {
        ?>
        <div class="container max-width-adaptive-lg padding-y-md content_text">
            <?= $this->page->text; ?>
        </div>
    <?php
    } 
    ?>
</section>

<section class="secion_target_audiences container max-width-sm padding-md">
    <div class="epkb-doc-search-container padding-sm text-center" >
        <h2 class="epkb-doc-search-container__title" style="color: #686862; font-size: 36px;"> Suche</h2>

        <form id="epkb_search_form margin-auto" class="epkb-search epkb-search-form-1" method="get" action="<?= $this->searchAction; ?>">
            <div class="search-input search-input--icon-right" style="width: 80%; margin: 16px auto;">
                <input class="form-control width-100%" type="search" name="q" placeholder="Suchen..." aria-label="Search">
                <button class="search-input__btn">
                    <svg class="icon" viewBox="0 0 24 24"><title>Submit</title><g stroke-linecap="square" stroke-linejoin="miter" stroke-width="2" stroke="currentColor" fill="none" stroke-miterlimit="10"><line x1="22" y1="22" x2="15.656" y2="15.656"></line><circle cx="10" cy="10" r="8"></circle></g></svg>
                </button>
            </div>
        </form>

    </div>
    <div class="parent grid gap-md margin-bottom-md">
        <?php
        foreach ($this->targetAudiences as $audience) {
            ?>
            <a class="card col-12 col-6@xs col-4@sm audience-card text-center" href="<?= $audience->url; ?>">
                <figure class="card__img padding-md padding-bottom-xs bg-contrast-lower">
                    <?php
                    echo $this->component->getService('ImageService')->getPictureHtml(array(
                        'image'     => $audience->tile_icon,
                        'loadAsync' => true,
                        'default'   => array(
                            'width' => 400
                        )
                    )); ?>
                </figure>

                <div class="card__content bg-contrast-lower">
                    <div class="text-component">
                        <h4><?= $audience->title; ?></h4>
                        <?php if(!empty($audience->intro)){ ?>
                            <p class="text-sm color-contrast-medium"><?= $audience->intro; ?></p>
                        <?php } ?>
                    </div>
                </div>
            </a>
            <?php
        }
        ?>
    </div>
</section>

<?php
if ($this->page->text2 && !empty((string) $this->page->text2)) {
    ?>
    <section class="bg-contrast-lower article text-component">
        <div class="container max-width-adaptive-lg padding-y-md content_text">
            <?= $this->page->text2; ?>
        </div>
    </section>
<?php
} 
?>