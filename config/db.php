<?php
declare(strict_types = 1);

use yii\db\Connection;

$db = [
    'class' => Connection::class,
    'dsn' => 'mysql:host=localhost;dbname=testnsign;unix_socket=/opt/lampp/var/mysql/mysql.sock',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];

return [
    'db' => $db,
    'dbsu' => array_merge(
        $db,
        ['dsn' => 'mysql:host=localhost;dbname=supports;unix_socket=/opt/lampp/var/mysql/mysql.sock']
    )
];
