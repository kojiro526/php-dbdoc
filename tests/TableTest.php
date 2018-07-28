<?php
namespace PhpDbdoc\Test;

require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use PhpDbdoc\lib\Table;

class TableTest extends TestCase
{

    public function dataProvider()
    {
        return [
            [
                'paths' => [
                    '/foo/baa/my_table_name.md',
                ],
                'expects' => '/foo/baa/my_table_name.md'
            ],
            [
                'paths' => [
                    '/foo/baa/00_my_table_name.md',
                ],
                'expects' => '/foo/baa/00_my_table_name.md'
            ],
            [
                'paths' => [
                    '/foo/baa/00_missing_table_name1.md',
                    '/foo/baa/01_my_table_name.md',
                    '/foo/baa/03_missing_table_name2.md',
                ],
                'expects' => '/foo/baa/01_my_table_name.md'
            ],
            [
                'paths' => '/foo/baa/01_my_table_name.md',
                'expects' => '/foo/baa/01_my_table_name.md'
            ],
        ];

    }

    /**
     * @dataProvider dataProvider
     * @param unknown $paths
     * @param unknown $expects
     */
    public function testFindMyFile($paths, $expects)
    {
        $this->assertEquals($expects, Table::findMyFile('my_table_name', $paths));
    }
}