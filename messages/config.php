<?php

return [
    'sourcePath' => __DIR__ . '/..',
    'messagePath' => __DIR__,
    'languages' => ['uk'],
    'translator' => 'Yii::t',
    'sort' => false,
    'overwrite' => true,
    'removeUnused' => false,
    'markUnused' => true,
    'except' => [
        '/messages',
        '/vendor',
        '/config',
        '/mail',
        '/modules',
        '/runtime',
        '/tests',
    ],
    'only' => [
        '*.php',
    ],
];
