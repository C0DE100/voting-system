<?php

require_once __DIR__ . '/Database/Database.php';
require_once __DIR__ . '/Migrations/CreateTables.php';
// Include the migration class
use Src\Migrations\CreateTables;


// Instantiate and run the migration
$migration = new CreateTables();
$migration->run();
