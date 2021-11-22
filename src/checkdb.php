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
require 'ReportEmail.php';

require 'sql.php';

$msg = [];

//$db_checker = new CheckerDatabase();
//$db_checker->check();

$ext_checker = new CheckerExtensions();
$ext_checker->check();
$msg['PHP_EXTENSIONS'] = $ext_checker->getHTMLReport();

$pkg_checker = new CheckerOSPackages();
$pkg_checker->check();
$msg['OS_PACKAGES'] = $pkg_checker->getHTMLReport();

$dns_checker = new CheckerDNS();
$dns_checker->check();
$msg['DNS_RECORDS'] = $dns_checker->getHTMLReport();

$tcp_checker = new CheckerTcpPort();
$tcp_checker->check();
$msg['TCP_PORTS'] = $tcp_checker->getHTMLReport();

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

$msg['DOCKER_INSTALLED'] = $docker_checker->getHTMLReport();


$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader, [
    'cache' => false,
]);

$template = $twig->load('results.html');
$html =  $template->render(['msg' => $msg]);

$reporter = new ReportEmail();
$reporter->send("EnvCheck Report", $html);

echo "\n\n";
