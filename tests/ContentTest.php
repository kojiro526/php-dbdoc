<?php
namespace PhpDbdoc\Test;

require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use PhpDbdoc\lib\Content;

class ContentTest extends TestCase
{

    public function dataProvider()
    {
$origin_1 =<<< EOF1
<!-- php-dbdoc_my_table Start -->
aaaaa
<!-- php-dbdoc_my_table End -->
EOF1;

$expects_1 =<<< EOF1
<!-- php-dbdoc_my_table Start -->
bbbbb
<!-- php-dbdoc_my_table End -->
EOF1;
        return [
            [
                'origin' => $origin_1,
                'pieces' => [
                    [
                        'pattern' => my_table,
                        'content' => 'bbbbb'
                    ]
                ],
                'expects' => $expects_1
            ]
        ];
    }


    /**
     * @dataProvider dataProvider
     * @param unknown $data
     * @param unknown $expects
     */
    public function testReplaceContent($origin, $pieces, $expects)
    {
        $content = new Content($origin);
        foreach ($pieces as $piece)
        {
            $content->addPiece($piece['pattern'], $piece['content']);
        }

        $this->assertEquals($expects, $content->build());
    }
    
    public function testPatternTag()
    {
        $this->assertEquals('<!-- php-dbdoc_my_table_name Start -->', Content::getPatternTag('my_table_name', 'start'));
        $this->assertEquals('<!-- php-dbdoc_my_table_name End -->', Content::getPatternTag('my_table_name', 'end'));
        $this->assertEquals('/<!-- php-dbdoc_my_table_name Start -->.*?<!-- php-dbdoc_my_table_name End -->/s', Content::getPatternTag('my_table_name'));
    }
}
    