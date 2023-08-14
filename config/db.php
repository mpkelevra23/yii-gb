<?php

use yii\db\Connection;

return [
    'class' => Connection::class,
    'dsn' => 'mysql:host=' . env('PROJECT_NAME') . '_db;dbname=' . env('PROJECT_NAME'),
    'username' => env('MYSQL_USERNAME', 'root'),
    'password' => env('MYSQL_PASSWORD'),
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
