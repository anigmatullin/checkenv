<?php

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class CheckerOSPackages
{
    protected $msg;
    protected $os;
    protected $pkg_required = [];
    protected $pkg_available = [];

    //brew list -1 --formula
    protected $cmd_macos = ["brew", "list", "-1", "--formula"];
    protected $cmd_ubuntu = ["apt", "list", "--installed"];

    public function __construct()
    {
        $this->os =  strtolower( $this->detectOS() );
        $path = "requirements/packages/$this->os.txt";
        $this->loadRequirements($path);

    }

    public function loadRequirements($path)
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
            return "Macos";
        }
        elseif (PHP_OS == "Linux") {

            if (is_readable("/etc/os-release")) {
                //might be Ubuntu...
                $vals = [];
                $c = file_get_contents("/etc/os-release");
                $c = explode("\n", $c);
                foreach ($c as $line) {
                    if (strlen($line)) {
                        list($key, $val) = explode("=", $line);
                        $vals[$key] = trim($val, "\ \t\n\r\0\x0B\"");
                    }
                }
                return $vals['NAME'];
            }
            else {
                return "Linux";
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

    public function getUbuntuPackages()
    {
        $process = new Process($this->cmd_ubuntu);
        $process->run();

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $out = $process->getOutput();
        $arr = explode("\n", $out);
        $res = [];

        foreach($arr as $line) {
            $tmp = explode("/", $line);
            $item = $tmp[0];

            if (strlen($item)) {
                $res[] = $item;
            }
        }

        return $res;
    }

    public function check()
    {
        $os = $this->detectOS();
        $this->msg .=  "Success: Detected OS: $os\n";

        if ($os == "Macos") {
            $this->pkg_available = $this->getMacosPackages();
        }
        elseif ($os == "Ubuntu") {
            $this->pkg_available = $this->getUbuntuPackages();
        }
        else {
            return false;
        }

        $unavailable = array_diff($this->pkg_required, $this->pkg_available);
        $count = count($unavailable);

        if ($count) {
            $this->msg .=  "\nCount of absent packages: $count\n";
            $this->msg .=  "The list of absent packages:\n";
            foreach ($unavailable as $absent) {
                $this->msg .=  "\t - " . $absent . "\n";
            }
            $this->msg .=  "\n";
            return false;
        }

        $this->msg .=  "Success: All required OS packages are installed!\n";
        return true;
    }

    public function getHTMLReport()
    {
        return nl2br($this->msg);
    }
}
