<?php

namespace ProcessWire;

class LikeEndpoint {
    public static function status($data) {
        $data = RestApiHelper::checkAndSanitizeRequiredParameters($data, ['id|int']);
        $page = wire('pages')->get('id=' . $data->id);

        if (!wire('modules')->isInstalled('LikesCounter')) {
            throw new InternalServererrorException('LikesCounter module not found.');
        }
        $module = wire('modules')->get('LikesCounter');
        $success = $module->status($page);
        
        return [
            'success' => $success
        ];
    }

    public static function like($data) {
        $data = RestApiHelper::checkAndSanitizeRequiredParameters($data, ['id|int']);
        $page = wire('pages')->get('id=' . $data->id);

        if (!wire('modules')->isInstalled('LikesCounter')) {
            throw new InternalServererrorException('LikesCounter module not found.');
        }
        $module = wire('modules')->get('LikesCounter');
        $success = $module->like($page);

        $likeTexts = ['Toll!', 'Super!', 'Brilliant!', 'Mag ich!', 'Schön!', 'Gefällt mir!', 'Ein toller Beitrag.', 'Danke!'];
        $key = random_int(0, count($likeTexts));
        
        return [
            'success' => $success,
            'message' => $likeTexts[$key]
        ];
    }

    public static function unlike($data) {
        $data = RestApiHelper::checkAndSanitizeRequiredParameters($data, ['id|int']);
        $page = wire('pages')->get('id=' . $data->id);

        if (!wire('modules')->isInstalled('LikesCounter')) {
            throw new InternalServererrorException('LikesCounter module not found.');
        }
        $module = wire('modules')->get('LikesCounter');
        $success = $module->unlike($page);
        
        return ['success' => $success];
    }
}
