<?php

namespace ProcessWire;


if($this->page->portraits && $this->page->portraits->count > 0){
    ?>
    <div class="content_portraits <?= !empty($this->page->classes . '') ? $this->page->classes : ''; ?>" <?= $this->page->depth ? 'data-depth="' . $this->page->depth . '"' : ''; ?>>
        <?php
        if (!empty($this->page->title)) {
            $headingDepth = 2;
            if ($this->page->depth && intval($this->page->depth)) {
                $headingDepth = $headingDepth + intval($this->page->depth);
            } ?>
            <h<?= $headingDepth; ?> class="block-title <?= $this->page->hide_title ? 'sr-only sr-only-focusable' : ''; ?>">
                <?= $this->page->title; ?>
            </h<?= $headingDepth; ?>>
            <?php
        } 
        ?>

        <div class="grid gap-xs portraits-grid">
            <?php
            foreach($this->page->portraits->find('text!=""')->shuffle() as $portrait){
                ?>
                <div class="col col-6 col-4@sm col-3@xxl portrait-card">
                    <div class="aspect-ratio card-img-top portrait-card__img">
                        <?php
                        if ($portrait->main_image) {
                            echo $this->component->getService('ImageService')->getPictureHtml(array(
                                'image' => $portrait->main_image,
                                'alt' => sprintf(__('Portrait of %1$s'), $portrait->title),
                                'pictureclasses' => array('ar-content', 'portrait-image'),
                                'loadAsync' => true,
                                'default' => array(
                                    'width' => 400
                                )
                            ));
                        } else {
                            ?>
                            <div class="bg-image ar-content portrait-image" style="background-image: url('<?= wire('config')->urls->templates . 'assets/static/silhouette_einzel.png'; ?>');"> </div>
                            <?php
                        }
                        ?>
                    </div>
                    <div class="portrait-contents text-center padding-xs">
                        <div class="portrait-header margin-bottom-md">
                            <h3 class="portrait-title"><?= $portrait->title; ?></h3> 
                            <span class="portrait-role"><?= $portrait->short_text; ?></span>
                        </div>

                        <div class="portrait-text">
                            <?= $portrait->text; ?>
                        </div>
                    </div>
                </div>
                <?php
            }

            foreach($this->page->portraits->find('text=""')->shuffle() as $portrait){
                ?>
                <div class="col col-6 col-4@sm col-3@xxl portrait-card">
                    <div class="aspect-ratio card-img-top portrait-card__img">
                        <?php
                        if ($portrait->main_image) {
                            echo $this->component->getService('ImageService')->getPictureHtml(array(
                                'image' => $portrait->main_image,
                                'alt' => sprintf(__('Portrait of %1$s'), $portrait->title),
                                'pictureclasses' => array('ar-content', 'portrait-image'),
                                'loadAsync' => true,
                                'default' => array(
                                    'width' => 400
                                )
                            ));
                        } else {
                            ?>
                            <div class="bg-image ar-content portrait-image" style="background-image: url('<?= wire('config')->urls->templates . 'assets/static/silhouette_einzel.png'; ?>');"> </div>
                            <?php
                        }
                        ?>
                    </div>
                    <div class="portrait-contents text-center padding-xs">
                        <div class="portrait-header margin-bottom-md">
                            <h3 class="portrait-title"><?= $portrait->title; ?></h3> 
                            <span class="portrait-role"><?= $portrait->short_text; ?></span>
                        </div>

                        <div class="portrait-text">
                            <?= $portrait->text; ?>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
    <?php
}
?>
