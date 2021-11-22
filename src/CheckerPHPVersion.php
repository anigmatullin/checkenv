<?php

class CheckerPHPVersion
{
    protected $compliant = false;
    protected $required_version_major;
    protected $required_version_minor;

    public function __construct()
    {
        $this->loadRequirements();
    }

    public function parseVersion($version)
    {
        $arr = explode(".", $version);
        return [$arr[0], $arr[1]];
    }

    public function loadRequirements($path = "requirements/phpversion.txt")
    {
        $version = trim(file_get_contents($path));
        list($this->required_version_major, $this->required_version_minor) = $this->parseVersion($version);
    }

    public function check()
    {
        $version = phpversion();
        list($major, $minor) = $this->parseVersion($version);
//        echo $major, $minor;
        if ($major == $this->required_version_major && $minor == $this->required_version_minor) {
            $this->compliant = true;
            return true;
        }
        else {
            return false;
        }
    }

    public function getHTMLReport()
    {
        $res = "<div>";

        if ($this->compliant) {
            $res .= "Have required PHP Version\n<br>";
        }
        else {
            $res .= "Do not have required PHP Version\n<br>";
        }

        $res .= "</div>";
        return $res;
    }

}
