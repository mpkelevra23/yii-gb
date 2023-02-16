<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost:' . env('MYSQL_PORT') . ';dbname=' . env('PROJECT_NAME'),
    'username' => env('MYSQL_USERNAME', 'root'),
    'password' => env('MYSQL_PASSWORD'),
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
