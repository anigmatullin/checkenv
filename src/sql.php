<?php

$sql_dbs = "SELECT * FROM sys.databases";

$sql_db_size = "
SELECT 
	database_id
    , log_size_gb = CAST(SUM(CASE WHEN type_desc = 'LOG' THEN size END) * 8. / (1024*1024) AS DECIMAL(8,2))
    , row_size_gb = CAST(SUM(CASE WHEN type_desc = 'ROWS' THEN size END) * 8. / (1024*1024) AS DECIMAL(8,2))
    , total_size_gb = CAST(SUM(size) * 8. / (1024*1024) AS DECIMAL(8,2))
FROM sys.master_files WITH(NOWAIT)
GROUP BY database_id
";
