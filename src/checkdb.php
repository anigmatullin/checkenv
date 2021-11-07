<?php

require 'bootstrap.php';
require 'DatabaseCreator.php';
require 'CheckerDatabase.php';
require 'CheckerExtensions.php';
require 'sql.php';

$db_checker = new CheckerDatabase();
$db_checker->check();

$ext_checker = new CheckerExtensions();
$ext_checker->check();
