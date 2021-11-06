<?php

use Illuminate\Database\Capsule\Manager as DB;

class DatabaseCreator
{
    public function __construct()
    {
//        echo "Creator created!\n";
    }

    public function create($name)
    {
        echo "Attempt to create database: $name\n";

        $sql = file_get_contents("CreateDatabaseTemplate.sql");
        $sql = str_replace("%DB_NAME%", $name, $sql);

        $res = false;
        try {
            $res = DB::statement($sql);
        }
        catch (Exception $e) {
            echo $e->getMessage();
            echo "\n\n";
            echo "Database creation failed\n";
            echo "SQL code below:\n";
            echo $sql;
        }

        if ($res) {
            echo "Database created successfully: $name\n";
        }

    }
}
