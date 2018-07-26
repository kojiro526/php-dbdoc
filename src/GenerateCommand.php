<?php
namespace PhpDbdoc;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Schema\ForeignKeyConstraint;

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
        $this->options = $parser->parse()->options;
    }

    public function execute()
    {

        $output_file = $this->options['output'];

        // 接続情報
        $cfg = array(
            'driver'   => 'pdo_mysql',
            'host'     => 'db',
            'dbname'   => 'sampledb',
            'user'     => 'root',
            'password' => 'hogehoge123',
        );

        $conn = DriverManager::getConnection($cfg);
        $tables = $conn->getSchemaManager()->listTables();

        /* @var $table Table */
        foreach ($tables as $table)
        {
            echo $table->getName() . PHP_EOL;

            $pkeys = [];

            if ($table->hasPrimaryKey())
            {
                $pkeys = array_flip($table->getPrimaryKeyColumns());
            }

            /* @var $column Column */
            foreach ($table->getColumns() as $column)
            {
                $name = $column->getName();

                if (isset($pkeys[$name]))
                {
                    echo " * " . $name . PHP_EOL;
                }
                else
                {
                    echo "   " . $name . PHP_EOL;
                }
            }

            /* @var $fkey ForeignKeyConstraint */
            foreach ($table->getForeignKeys() as $fkey)
            {
                $cols = implode(", ", $fkey->getColumns());
                $name = $fkey->getForeignTableName();
                $refs = implode(', ', $fkey->getForeignColumns());
                echo "     ... fkey ( $cols ) ref $name ( $refs )" . PHP_EOL;
            }

            echo PHP_EOL;
        }
    }
}