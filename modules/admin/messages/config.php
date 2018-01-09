<?php

return [
    'sourcePath' => __DIR__ . '/..',
    'messagePath' => __DIR__,
    'languages' => ['uk'],
    'translator' => 'Module::t',
    'sort' => false,
    'overwrite' => true,
    'removeUnused' => false,
    'markUnused' => true,
    'except' => [

    ],
    'only' => [
        '*.php',
    ],
];
