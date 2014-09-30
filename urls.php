<?php

return [
    '/checker/{id:\d+}' => [
        'name' => 'checker',
        'callback' => '\Modules\Mail\Controllers\MailCheckerController::index'
    ]
];
