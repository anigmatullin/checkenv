<?php

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class CheckerDocker
{
    protected $msg;

    protected $cmd = ["docker", "ps"];
    protected $cmd_compose = ["docker-compose", "help"];

    public function __construct()
    {

    }

    public function check()
    {
        $success = true;

        if ($this->cmdAvailable($this->cmd)) {
            $this->msg .=  "Success: Docker is available\n";
        }
        else {
            $this->msg .= "Fail: Cannot run docker\n";
            $success = false;
        }

        if ($this->cmdAvailable($this->cmd_compose)) {
            $this->msg .=  "Success: Docker Compose is available\n";
        }
        else {
            $this->msg .=  "Fail: Cannot run docker-compose\n";
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
            $this->msg .=  "Fail: Some containers are unavailable:\n";
            $this->msg .= "The list of absent containers:\n";

            foreach ($unavailable as $absent) {
                echo "\t - ", $absent, "\n";
            }

            $this->msg .= "\n";
            return false;
        }
        else {
            $this->msg .=  "Success: all container are available\n";
            return true;
        }
    }

    public function cmdAvailable($cmd)
    {
        $process = new Process($cmd);
        $process->run();
        return $process->isSuccessful();
    }

    public function getHTMLReport()
    {
        return nl2br($this->msg);
    }
}
