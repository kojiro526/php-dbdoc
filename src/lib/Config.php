<?php 
namespace PhpDbdoc\lib;

class Config
{
    private $driver = 'pdo_mysql';
    private $host = 'localhost';
    private $dbname = 'dbname';
    private $user = 'root';
    private $password = '';
    private $port = 3306;

    public function __construct($config)
    {
        if (!empty($config['host'])){
            $this->host = $config['host'];
        }
        if (!empty($config['dbname'])){
            $this->dbname = $config['dbname'];
        }
        if (!empty($config['user'])){
            $this->user = $config['user'];
        }
        if (!empty($config['password'])){
            $this->password = $config['password'];
        }
        if (!empty($config['port'])){
            $this->port = $config['port'];
        }
    }
    
    public function getConfig()
    {
        return [
            'driver' => $this->driver,
            'host' => $this->host,
            'dbname' => $this->dbname,
            'user' => $this->user,
            'password' => $this->password,
            'port' => $this->port
        ];
    }
    
}