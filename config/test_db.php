<?php
declare(strict_types = 1);
$db = require __DIR__ . '/db.php';
// test database! Important not to run tests on production or development databases
$db['db']['dsn'] = 'mysql:host=localhost;dbname=restaurant_tests';
$db['dbsu']['dsn'] = 'mysql:host=localhost;dbname=supports_tests';

return $db;
