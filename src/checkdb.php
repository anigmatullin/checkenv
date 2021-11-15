<?php

require 'bootstrap.php';
require 'DatabaseCreator.php';

require 'CheckerDatabase.php';
require 'CheckerExtensions.php';
require 'CheckerOSPackages.php';
require 'CheckerDocker.php';
require 'CheckerDNS.php';
require 'CheckerTcpPort.php';

require 'EnablerDocker.php';


require 'sql.php';

//$db_checker = new CheckerDatabase();
//$db_checker->check();

$ext_checker = new CheckerExtensions();
$ext_checker->check();

$pkg_checker = new CheckerOSPackages();
$pkg_checker->check();

$dns_checker = new CheckerDNS();
$dns_checker->check();

$tcp_checker = new CheckerTcpPort();
$tcp_checker->check();

$docker_checker = new CheckerDocker();
$docker = $docker_checker->check();

$container_exists = false;
if ($docker) {
    $container_exists = $docker_checker->checkContainers(['sqlSRV']);
}

if ($docker && !$container_exists) {
    $enabler = new EnablerDocker();
    $enabler->enable();
}

echo "\n\n";
