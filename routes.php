<?php

return [
    [
        'route' => '/upload',
        'target' => [\Modules\Files\Controllers\UploadController::class, 'upload'],
        'name' => 'upload'
    ],
    [
        'route' => '/sort',
        'target' => [\Modules\Files\Controllers\UploadController::class, 'sort'],
        'name' => 'sort'
    ],
    [
        'route' => '/delete',
        'target' => [\Modules\Files\Controllers\UploadController::class, 'delete'],
        'name' => 'delete'
    ],
    [
        'route' => '/large/upload',
        'target' => [\Modules\Files\Controllers\LargeUploadController::class, 'upload'],
        'name' => 'large_upload'
    ],
    [
        'route' => '/large/delete',
        'target' => [\Modules\Files\Controllers\LargeUploadController::class, 'delete'],
        'name' => 'large_delete'
    ],
];