<?php

require 'bootstrap.php';
require 'DatabaseCreator.php';

require 'CheckerDatabase.php';
require 'CheckerExtensions.php';
require 'CheckerOSPackages.php';
require 'CheckerDocker.php';
require 'EnablerDocker.php';


require 'sql.php';

//$db_checker = new CheckerDatabase();
//$db_checker->check();

$ext_checker = new CheckerExtensions();
$ext_checker->check();

$pkg_checker = new CheckerOSPackages();
$pkg_checker->check();

$docker_checker = new CheckerDocker();
$docker = $docker_checker->check();

if ($docker) {
    $enabler = new EnablerDocker();
    $enabler->enable();
}

echo "\n\n";
