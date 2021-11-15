<?php


function checkport($host, $port, $timeout=10)
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

echo checkport("google.com", 443), "\n";
