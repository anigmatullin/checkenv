<?php

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class CheckerOSPackages
{
    protected $pkg_required = [];
    protected $pkg_available = [];

    //brew list -1 --formula
    protected $cmd_macos = ["brew", "list", "-1", "--formula"];

    public function __construct($path = "requirements/packages/macos.txt")
    {
        $reqs = file_get_contents($path);
        $items = explode("\n", $reqs);

        foreach ($items as $item) {
            $item = trim($item);
            $comment = str_starts_with($item, '#');

            if ($comment) {
                continue;
            }

            if (strlen($item) < 2) {
                continue;
            }

            $this->pkg_required[] = $item;
        }
    }

    public function detectOS()
    {
        if (PHP_OS == "Darwin") {
            return "macos";
        }
        elseif (PHP_OS == "Linux") {

            if (is_readable("/etc/os-release")) {
                //might be Ubuntu...
                $c = file_get_contents("/etc/os-release");
                echo $c;
            }
        }

    }

    public function getMacosPackages()
    {
        $process = new Process($this->cmd_macos);
        $process->run();

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $out = $process->getOutput();
        return explode("\n", $out);
    }

    public function check()
    {
        $os = $this->detectOS();
        echo $os, "\n";

        if ($os == "macos") {
            $this->pkg_available = $this->getMacosPackages();
        }
        else {
            return;
        }

        $unavailable = array_diff($this->pkg_required, $this->pkg_available);
        $count = count($unavailable);

        if ($count) {
            echo "\nCount of absent packages: $count\n";
            echo "The list of absent packages:\n";
            foreach ($unavailable as $absent) {
                echo "\t - ", $absent, "\n";
            }
            echo "\n";
            return false;
        }

        echo "Success: All required OS packages are installed!\n";
        return true;
    }

    public function getHTMLReport()
    {
        //
    }
}