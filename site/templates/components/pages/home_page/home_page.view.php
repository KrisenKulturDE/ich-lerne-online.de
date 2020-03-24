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

<?php 
if ($this->page->block_form_submission !== true) {
    ?>
    <section class="section_item_submit container max-width-sm padding-md" id="formular">
        <form method="post" action="#formular">
            <div class="text-component text-center margin-bottom-md">
                <h2>Hilf unserer Redaktion</h2>
                <p>Du kennst Inhalte, die in unserer Wissensbasis fehlen? <br>
                Nutze einfach unser Kontaktformular oder tagge <a style="font-weight: bold;" target="_blank" rel="nofollow" href="https://twitter.com/ichlerneonline">@ichlerneonline</a> in Deinem Tweet.</p>
            </div>

            <div class="alerts margin-y-md">
                <?php
                    if(!empty($this->evaluationResponse['success']) && is_array($this->evaluationResponse['success'])){
                        foreach($this->evaluationResponse['success'] as $msg){
                            ?>
                            <div class="alert alert-success" role="alert">
                                <?= $msg; ?>
                            </div>
                            <?php
                        }
                    }
                    if(!empty($this->evaluationResponse['error']) && is_array($this->evaluationResponse['error'])){
                        foreach($this->evaluationResponse['error'] as $msg){
                            ?>
                            <div class="alert alert-danger" role="alert">
                                <?= $msg; ?>
                            </div>
                            <?php
                        }
                    }
                ?>
            </div>

            <div class="margin-bottom-sm">
                <label class="form-label margin-bottom-xxxs" for="inputEmail">Deine Email-Adresse</label>
                <input class="form-control width-100%" type="email" name="sender_email" id="inputEmail" <?= $this->component->getErrorMsg('sender_email') ? 'aria-invalid="true"' : ''; ?> value="<?= !$this->component->shouldClearFormFields() && $this->component->getCurrentValue('sender_email') ? $this->component->getCurrentValue('sender_email') : ''; ?>">
                <?= $this->component->getErrorMsg('sender_email') ? '<p class="text-xs color-danger margin-bottom-sm">' . $this->component->getErrorMsg('sender_email') . '</p>' : ''; ?>
                
            </div>

            <div class="margin-bottom-md">
                <label class="form-label margin-bottom-xxxs" for="inputTitle">Titel des Inhalts</label> 
                <input class="form-control width-100%" type="text" name="title" id="inputTitle" <?= $this->component->getErrorMsg('title') ? 'aria-invalid="true"' : ''; ?> value="<?= !$this->component->shouldClearFormFields() && $this->component->getCurrentValue('title') ? $this->component->getCurrentValue('title') : ''; ?>">
                <?= $this->component->getErrorMsg('title') ? '<p class="text-xs color-danger margin-bottom-sm">' . $this->component->getErrorMsg('title') . '</p>' : ''; ?>
            </div>

            <div class="margin-bottom-md">
                <label class="form-label margin-bottom-xxxs" for="inputLink">Link zum Inhalt</label> 
                <input class="form-control width-100%" type="text" name="link" id="inputLink" <?= $this->component->getErrorMsg('link') ? 'aria-invalid="true"' : ''; ?> value="<?= !$this->component->shouldClearFormFields() && $this->component->getCurrentValue('link') ? $this->component->getCurrentValue('link') : ''; ?>">
                <?= $this->component->getErrorMsg('link') ? '<p class="text-xs color-danger margin-bottom-sm">' . $this->component->getErrorMsg('link') . '</p>' : ''; ?>
            </div>

            <div class="margin-bottom-md">
                <fieldset>
                    <legend class="form-label">Für welche Zielgruppe(n) könnte der Inhalt relevant sein?</legend>

                    <div class="checkbox-list flex flex-wrap gap-xs <?= $this->component->getErrorMsg('target_audience') ? 'color-danger' : ''; ?>">
                        <?php
                        $selected = $this->component->getCurrentValue('target_audience');
                        foreach ($this->targetAudiences as $option) {
                            $checked = false;
                            if(!$this->component->shouldClearFormFields() && $selected instanceof \ProcessWire\PageArray){
                                $checked = $selected->has('id=' . $option->id);
                            }
                            ?>
                            <div>
                                <input class="checkbox" type="checkbox" id="inputTargetAudience<?= $option->id; ?>" name="target_audience[]" value="<?= $option->id; ?>" <?= $checked ? 'checked' : '' ?>>
                                <label for="inputTargetAudience<?= $option->id; ?>"><?= $option->title; ?></label>
                            </div>
                            <?php
                        } ?>
                    </div>
                </fieldset>
                <?= $this->component->getErrorMsg('target_audience') ? '<p class="text-xs color-danger margin-bottom-sm">' . $this->component->getErrorMsg('target_audience') . '</p>' : ''; ?>
            </div>

            <div class="margin-bottom-md">
                <fieldset>
                    <legend class="form-label">Art des Inhalts</legend>
                    <p class="text-xs color-contrast-medium margin-bottom-sm">Wählen Sie mindestens eine Kategorie</p>

                    <div class="checkbox-list flex flex-wrap gap-xs <?= $this->component->getErrorMsg('category') ? 'color-danger' : ''; ?>">
                        <?php
                        $selected = $this->component->getCurrentValue('category');
                        foreach ($this->categoryOptions as $option) {
                            $checked = false;
                            if(!$this->component->shouldClearFormFields() && $selected instanceof \ProcessWire\PageArray){
                                $checked = $selected->has('id=' . $option->id);
                            }
                            ?>
                            <div>
                                <input class="checkbox" type="checkbox" id="inputCategory<?= $option->id; ?>" name="category[]" value="<?= $option->id; ?>" <?= $checked ? 'checked' : '' ?>>
                                <label for="inputCategory<?= $option->id; ?>"><?= $option->title; ?></label>
                            </div>
                            <?php
                        } ?>
                    </div>
                </fieldset>
                <?= $this->component->getErrorMsg('category') ? '<p class="text-xs color-danger margin-bottom-sm">' . $this->component->getErrorMsg('category') . '</p>' : ''; ?>
            </div>

            <div class="margin-bottom-md">
                <label class="form-label margin-bottom-xxxs" for="inputReason">Warum soll genau dieser Eintrag in unsere Liste aufgenommen werden?</label> 
                <textarea class="form-control width-100%" name="sender_reason" id="inputReason" <?= $this->component->getErrorMsg('sender_reason') ? 'aria-invalid="true"' : ''; ?>><?= !$this->component->shouldClearFormFields() && $this->component->getCurrentValue('sender_reason') ? $this->component->getCurrentValue('sender_reason') : ''; ?></textarea>
                <?= $this->component->getErrorMsg('sender_reason') ? '<p class="text-xs color-danger margin-bottom-sm">' . $this->component->getErrorMsg('sender_reason') . '</p>' : ''; ?>
            </div>

            <div class="margin-bottom-md">
                <label class="form-label margin-bottom-xxs" for="inputAntispamCode">Antispam-Code:</label>
                <p class="text-xs color-contrast-medium margin-bottom-sm">Bitte geben Sie den folgenden Zahlencode in das nebenstehende Textfeld ein.</p>
                <div class="input-group">
                    <div class="input-group__tag"><?= $this->component->getAntispamCode(); ?></div>
                    <input class="form-control flex-grow" type="text" name="antispam_code" id="inputAntispamCode" <?= $this->component->getErrorMsg('antispam_code') ? 'aria-invalid="true"' : ''; ?>>
                </div>
                <?= $this->component->getErrorMsg('antispam_code') ? '<p class="text-xs color-danger margin-bottom-sm">' . $this->component->getErrorMsg('antispam_code') . '</p>' : ''; ?>
            </div>

            <?= $this->wire('session')->CSRF->renderInput($this->formOrigin); ?>

            <input type="hidden" name="action" value="submit-item">
            <input type="text" name="information" class="info-field"/>
            <input type="hidden" name="form-origin" value="<?= $this->formOrigin; ?>">

            <div class="alerts margin-y-md">
                <?php
                    if(!empty($this->evaluationResponse['success']) && is_array($this->evaluationResponse['success'])){
                        foreach($this->evaluationResponse['success'] as $msg){
                            ?>
                            <div class="alert alert-success" role="alert">
                                <?= $msg; ?>
                            </div>
                            <?php
                        }
                    }
                    if(!empty($this->evaluationResponse['error']) && is_array($this->evaluationResponse['error'])){
                        foreach($this->evaluationResponse['error'] as $msg){
                            ?>
                            <div class="alert alert-danger" role="alert">
                                <?= $msg; ?>
                            </div>
                            <?php
                        }
                    }
                ?>
            </div>

            <div class="margin-bottom-sm">
                <input type="submit" class="btn btn--primary btn--md width-100%" value="Senden">
            </div>
        </form>
    </section>
<?php
}
?>