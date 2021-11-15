<?php

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class CheckerDocker
{
    protected $cmd = ["docker", "ps"];
    protected $cmd_compose = ["docker-compose", "help"];

    public function __construct()
    {

    }

    public function check()
    {
        $success = false;

        if ($this->cmdAvailable($this->cmd)) {
            echo "Success: Docker is available\n";
            $success = true;
        }
        else {
            echo "Fail: Cannot run docker\n";
            $success = false;
        }

        if ($this->cmdAvailable($this->cmd_compose)) {
            echo "Success: Docker Compose is available\n";
            $success = true;
        }
        else {
            echo "Fail: Cannot run docker-compose\n";
            $success = false;
        }

        return $success;
    }

    public function checkContainers($required)
    {
        $containers = [];
        $process = new Process($this->cmd);
        $process->run();

        if (!$process->isSuccessful()) {
            return false;
        }

        $out = $process->getOutput();
        $out = explode("\n", $out);
        array_shift($out);
        array_pop($out);

        foreach ($out as $line) {
            $items = preg_split("/\s/", $line);
            $name = array_pop($items);
            $containers[] = $name;
        }

        $unavailable = array_diff($required, $containers);
        $count = count($unavailable);

        if ($count) {
            echo "Fail: Some containers are unavailable:\n";
            echo "The list of absent containers:\n";

            foreach ($unavailable as $absent) {
                echo "\t - ", $absent, "\n";
            }

            echo "\n";
            return false;
        }
        else {
            echo "Success: all container are available\n";
            return true;
        }
    }

    public function cmdAvailable($cmd)
    {
        $process = new Process($cmd);
        $process->run();
        return $process->isSuccessful();
    }
}
