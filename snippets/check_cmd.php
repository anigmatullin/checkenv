<?php

require '../vendor/autoload.php';

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

$cmd = ["docker", "ps"];

$process = new Process($cmd);
$process->run();

// executes after the command finishes
if (!$process->isSuccessful()) {
    echo "The process failed\n";
}
else {
    echo "Success\n";
    $out = $process->getOutput();
    echo $out;
}
