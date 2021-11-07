<?php

use Illuminate\Database\Capsule\Manager as DB;

class CheckerDatabase
{

    public function __construct()
    {
        //
    }

    public function check()
    {
        global $sql_dbs, $sql_db_size;

        try {
            $res = DB::select($sql_dbs);
            $sizes = DB::select($sql_db_size);
            $dbs = [];
            $arr_sizes = [];
        }
        catch (Exception $e) {
            echo $e->getMessage();
            echo "\n\n";
            exit(1);
        }

        $tbl = new Console_Table();
        $tbl->setHeaders(
            array('ID', 'Name', 'Created', 'Data (GB)', 'Log (GB)', 'Total (GB)', 'State', 'Recovery Model', 'RCSI', 'Delayed Durability')
        );

        foreach($sizes as $row) {
            $id = $row->database_id;
            $row_size_gb = $row->row_size_gb;
            $log_size_gb = $row->log_size_gb;
            $total_size_gb = $row->total_size_gb;

            $row = [$row_size_gb, $log_size_gb, $total_size_gb];
            $arr_sizes[$id] = $row;
        }

        foreach($res as $db) {
            $id = $db->database_id;
            $name = $db->name;
            $created = $db->create_date;

            $state = $db->state_desc;
            $recovery_model = $db->recovery_model_desc;

            $read_commited_snapshot = $db->is_read_committed_snapshot_on;
            $delayed_durability = $db->delayed_durability_desc;

            $row_size_gb = $arr_sizes[$id][0];
            $log_size_gb = $arr_sizes[$id][1];
            $total_size_gb = $arr_sizes[$id][2];

            $row = [$id, $name, $created, $row_size_gb, $log_size_gb, $total_size_gb, $state, $recovery_model, $read_commited_snapshot, $delayed_durability];

            $dbs[$name] = $db;
            $tbl->addRow($row);
        }

# Show List of Databases
        echo "\n";
        echo $tbl->getTable();
        echo "\n";

# Check the Required Database
        $dbname = $_ENV['DB_REQUIRED'];

# Create Database, if it does not exist
        $exists = isset($dbs[$dbname]);
        if (!$exists) {
            echo "The Database $dbname does not exist!\n";
            $creator = new DatabaseCreator();
            $creator->create($dbname);
        }
        else {
            echo "Success: The Database $dbname exists!\n";
        }

    }
}
