<?php

//get_loaded_extensions();

class CheckerExtensions
{
    protected $msg;
    protected $modules_required = [];
    protected $modules_available = [];

    public function __construct($path = "requirements/phpmodules.txt")
    {
        $def = file_get_contents($path);
        $modules = explode("\n", $def);

        foreach ($modules as $module) {
            $module = trim($module);
            $comment = str_starts_with($module, '#');

            if ($comment) {
                continue;
            }

            if (strlen($module) < 2) {
                continue;
            }

            $this->modules_required[] = $module;
        }
    }

    public function getMessage()
    {
        return $this->msg;
    }

    public function check()
    {
        $this->modules_available = get_loaded_extensions();
        $unavailable = array_diff($this->modules_required, $this->modules_available);
        $count = count($unavailable);

        if ($count) {
            $this->msg = "\nCount of absent modules: $count\n";
            $this->msg .= "The list of absent modules:\n";
            foreach ($unavailable as $absent) {
                $this->msg .= "\t - " . $absent . "\n";
            }
            $this->msg .= "\n";
            return false;
        }

        $this->msg .= "Success: All required PHP modules are installed!\n";
        return true;
    }


    public function getHTMLReport()
    {
        return nl2br($this->msg);
    }
}
