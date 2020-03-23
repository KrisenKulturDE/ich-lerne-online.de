<?php

namespace ProcessWire;

// Add the brand name after the title.
$wire->addHookAfter('SeoMaestro::renderSeoDataValue', function (HookEvent $event) {
    $group = $event->arguments(0);
    $name = $event->arguments(1);
    $value = $event->arguments(2);

    // Insert default values if empty:
    if (empty($value)) {
        if ($name === 'image') {
            $value = '/site/templates/assets/static/ich-lerne-online-logo.jpg';
            $event->return = $value;
        } elseif ($group === 'meta' && $name === 'description') {
            $value = 'Sammlung von Links, Tools, Tutorials, Tipps und Tricks, um ins Online-Lehren und Lernen einzusteigen und einen Überblick über E-Learning Angebote zu bekommen.';
            $event->return = $value;
        }
    }

    if ($group === 'meta' && $name === 'title') {
        if($value === 'ich-lerne-online.org | ich-lerne-online.org'){
            $value = 'ich-lerne-online.org';
        }
        $event->return = htmlspecialchars(strip_tags($value));
    } elseif ($name === 'description') {
        $event->return = htmlspecialchars(trim(str_replace('&nbsp;', ' ', strip_tags($value))));
    }
});