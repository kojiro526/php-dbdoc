<?php
namespace PhpDbdoc;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Schema\ForeignKeyConstraint;
use PhpDbdoc\lib\Config;

class GenerateCommand
{

    private $options;

    public function __construct()
    {
        $parser = new \Console_CommandLine();
        $parser->description = 'Database docments generator';
        $parser->addOption('config', [
            'short_name' => '-c',
            'long_name' => '--config',
            'description' => 'Indicate config file',
            'action' => 'StoreString'
        ]);
        $parser->addOption('output', [
            'short_name' => '-o',
            'long_name' => '--output',
            'description' => 'Indicate output file or directory',
            'action' => 'StoreString'
        ]);
        $parser->addOption('separate', [
            'short_name' => '-s',
            'long_name' => '--split',
            'description' => 'Separate files one by one',
            'action' => 'StoreTrue'
        ]);
        $parser->addOption('host', [
            'short_name' => '-h',
            'long_name' => '--host',
            'description' => 'Indicate database host',
            'action' => 'StoreString'
        ]);
        $parser->addOption('dbname', [
            'short_name' => '-d',
            'long_name' => '--dbname',
            'description' => 'Indicate schema name',
            'action' => 'StoreString'
        ]);
        $parser->addOption('user', [
            'short_name' => '-u',
            'long_name' => '--user',
            'description' => 'Indicate database user name',
            'action' => 'StoreString'
        ]);
        $parser->addOption('password', [
            'short_name' => '-p',
            'long_name' => '--password',
            'description' => 'Indicate database password',
            'action' => 'StoreString'
        ]);
        $parser->addOption('port', [
            'short_name' => '-P',
            'long_name' => '--port',
            'description' => 'Indicate database port',
            'action' => 'StoreString'
        ]);
        $this->options = $parser->parse()->options;
    }

    public function execute()
    {

        $output_file = $this->options['output'];

        // 接続情報
        $option_config = array(
            'driver'   => 'pdo_mysql',
            'host'     => $this->options['host'],
            'dbname'   => $this->options['dbname'],
            'user'     => $this->options['user'],
            'password' => $this->options['password'],
            'port'     => $this->options['port']
        );
        
        $config = new Config($option_config);
        $conn = DriverManager::getConnection($config->getConfig());
        $src_tables = $conn->getSchemaManager()->listTables();
        $tables = [];

        /* @var $table Table */
        foreach ($src_tables as $i => $table)
        {
            array_push($tables, new \PhpDbdoc\lib\Table($table, $conn->getDatabasePlatform()));
        }
        
        foreach ($tables as $table)
        {
            echo $table->getTableInfo() . "\n";
            echo $table->getColumnsInfo() . "\n";
            echo $table->getIndexInfo() . "\n";
            echo $table->getForignKeyInfo() . "\n";
        }
        echo PHP_EOL;
    }
}
