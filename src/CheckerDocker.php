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

    public function cmdAvailable($cmd)
    {
        $process = new Process($cmd);
        $process->run();
        return $process->isSuccessful();
    }
}
