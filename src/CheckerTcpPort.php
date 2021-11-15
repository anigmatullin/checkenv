<?php

class CheckerTcpPort
{
    public function __construct()
    {
        //
    }

    public function loadRequirements()
    {
        //
    }

    public function check()
    {
        //
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
}
