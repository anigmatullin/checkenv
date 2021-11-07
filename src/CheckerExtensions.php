<?php

//get_loaded_extensions();

class CheckerExtensions
{
    protected $modules_required = [];
    protected $modules_available = [];

    public function __construct($path = "modules.txt")
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

    public function check()
    {
        $this->modules_available = get_loaded_extensions();
        $unavailable = array_diff($this->modules_required, $this->modules_available);
        $count = count($unavailable);

        if ($count) {
            echo "\nCount of absent modules: $count\n";
            echo "The list of absent modules:\n";
            foreach ($unavailable as $absent) {
                echo "\t - ", $absent, "\n";
            }
            echo "\n";
            return false;
        }

        echo "\nSuccess: All required PHP modules are installed!\n\n";
        return true;
    }

    public function getHTMLReport()
    {
        //
    }
}
