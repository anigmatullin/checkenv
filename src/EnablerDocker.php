<?php

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class EnablerDocker
{
    //docker run -e "ACCEPT_EULA=Y" -e "SA_PASSWORD=yourStrong(!)Password" -p 1433:1433 -d mcr.microsoft.com/mssql/server:2019-latest

    protected $pass = "P@ssw0rd";
    protected $name = "sqlSRV";

    protected $env = [
        'ACCEPT_EULA' => 'Y',
        'SA_PASSWORD' => 'password',
    ];

//    protected $cmd = ["docker", "run", "-e", 'ACCEPT_EULA=Y', "-e", '"SA_PASSWORD=password"', "-p", "1433:1433", "-d", "--name", "sqlsrv", "mcr.microsoft.com/mssql/server:2019-latest"];

    protected $cmd = ["docker", "run", "--env-file", "/tmp/sqlsrv.env", "-p", "1433:1433", "-d", "--name", "sqlsrv", "mcr.microsoft.com/mssql/server:2019-latest"];

    public function __construct()
    {
        $this->env['SA_PASSWORD'] = '"' . $this->pass . '"';
        $this->cmd[8] = $this->name;

        $content = "";
        foreach ($this->env as $key=>$value) {
            $content .= "$key=$value\n";
        }
        file_put_contents("/tmp/sqlsrv.env", $content);
    }

    public function enable()
    {
        $process = new Process($this->cmd, null, $this->env);
        $process->run();
        $res =  $process->isSuccessful();

        if ($res) {
            echo "Container enabled successfully\n";
            echo $process->getOutput();
            echo "\n\n";
        }
        else {
            echo "Fail: unable to start container\n\n";
            throw new ProcessFailedException($process);
        }

        return $res;
    }
}
