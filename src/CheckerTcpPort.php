<?php

class CheckerTcpPort
{
    protected $msg;
    protected $required = [
    ];

    public function __construct()
    {
        $this->loadRequirements();
    }

    public function loadRequirements($path = "requirements/tcpports.txt")
    {
        $content = file_get_contents($path);
        $content = explode("\n", $content);

        foreach ($content as $line) {
            $line = trim($line);
            $row = preg_split("/[\s:]/", $line);

            if (count($row) != 2) {
                continue;
            }

            $host = $row[0];
            $port = $row[1];

            $this->required[] = [$host, $port];
        }
    }

    public function check()
    {
        $result = true;

        foreach ($this->required as $row) {
            list($host, $port) = $row;
            $res = $this->checkport($host, $port);

            if ($res) {
                $this->msg .= "Success tcp connection: $host $port\n";
            }
            else {
                $this->msg .= "Failed tcp connection: $host $port\n";
                $result = false;
            }
        }

        return $result;
    }

    public function checkport($host, $port, $timeout=10)
    {
        $op = fsockopen($host, $port, $errno, $errstr, $timeout);

        if (!$op) {
            return false; //DC is N/A
        }
        else {
            fclose($op); //explicitly close open socket connection
            return true; //DC is up & running, we can safely connect with ldap_connect
        }
    }

    public function getHTMLReport()
    {
        return nl2br($this->msg);
    }
}
