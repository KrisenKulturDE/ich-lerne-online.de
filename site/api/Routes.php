<?php

namespace ProcessWire;

require_once wire('config')->paths->RestApi . 'vendor/autoload.php';
require_once wire('config')->paths->RestApi . 'classes/RestApiHelper.php';

require_once __DIR__ . '/TwackAccess.class.php';
require_once __DIR__ . '/LikeEndpoint.class.php';

$routes = [
    'like' => [
        ['OPTIONS', '{id:\d+}', ['GET', 'POST', 'DELETE']],
        ['GET', '{id:\d+}', LikeEndpoint::class, 'status'],
        ['POST', '{id:\d+}', LikeEndpoint::class, 'like'],
        ['DELETE', '{id:\d+}', LikeEndpoint::class, 'unlike']
    ],
    'unlike' => [
        ['OPTIONS', '{id:\d+}', ['POST']],
        ['POST', '{id:\d+}', LikeEndpoint::class, 'unlike']
    ],
    'page' => [
        ['OPTIONS', '{id:\d+}', ['GET']],
        ['OPTIONS', '{path:.+}', ['GET']],
        ['OPTIONS', '', ['GET']],
        ['GET', '{id:\d+}', TwackAccess::class, 'pageIDRequest'],
        ['GET', '{path:.+}', TwackAccess::class, 'pagePathRequest'],
        ['GET', '', TwackAccess::class, 'dashboardRequest'],
        // ['POST', '{id:\d+}', TwackAccess::class, 'pageIDRequest'],
        // ['POST', '{path:.+}', TwackAccess::class, 'pagePathRequest'],
        // ['POST', '', TwackAccess::class, 'dashboardRequest'],
        // ['UPDATE', '{id:\d+}', TwackAccess::class, 'pageIDRequest'],
        // ['UPDATE', '{path:.+}', TwackAccess::class, 'pagePathRequest'],
        // ['UPDATE', '', TwackAccess::class, 'dashboardRequest'],
        // ['DELETE', '{id:\d+}', TwackAccess::class, 'pageIDRequest'],
        // ['DELETE', '{path:.+}', TwackAccess::class, 'pagePathRequest'],
        // ['DELETE', '', TwackAccess::class, 'dashboardRequest'],
    ],
    // 'file' => [
    //     ['OPTIONS', '{id:\d+}', ['GET']],
    //     ['OPTIONS', '{path:.+}', ['GET']],
    //     ['OPTIONS', '', ['GET']],
    //     ['GET', '{id:\d+}', TwackAccess::class, 'pageIDFileRequest'],
    //     ['GET', '{path:.+}', TwackAccess::class, 'pagePathFileRequest'],
    //     ['GET', '', TwackAccess::class, 'dashboardFileRequest']
    // ]
];
