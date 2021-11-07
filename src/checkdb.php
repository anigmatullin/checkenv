<?php

require 'bootstrap.php';
require 'DatabaseCreator.php';

require 'CheckerDatabase.php';
require 'CheckerExtensions.php';
require 'CheckerOSPackages.php';

require 'sql.php';

$db_checker = new CheckerDatabase();
$db_checker->check();

$ext_checker = new CheckerExtensions();
$ext_checker->check();

$pkg_checker = new CheckerOSPackages();
$pkg_checker->check();

echo "\n\n";