<?php 
namespace PhpDbdoc\lib;

class Table
{
    private $src_table = null;
    
    public function __construct($table)
    {
        $this->src_table = $table;
    }
    
    public function getTableInfo()
    {
        echo "- " . $this->src_table->getName()  . "\n";
    }
    
    public function getColumnsInfo()
    {

        $pkeys = [];

        if ($this->src_table->hasPrimaryKey())
        {
            $pkeys = array_flip($this->src_table->getPrimaryKeyColumns());
        }

        /* @var $column Column */
        $header = "| No | 名前 | 型 | 主キー | Null | 初期値 |\n";
        $header .= "|:---|:---|:---|:---:|:---|\n";
        $rows = '';
        $i = 1;
        foreach ($this->src_table->getColumns() as $column)
        {
            $name = $column->getName();
            $type = $column->getType();
            $length = $column->getLength();
            if (!empty($length)){
                $type .= sprintf("(%d)", $length);
            }
            $is_pkey = isset($pkeys[$name]) ? '○' : '';
            $is_notnull = !empty($column->getNotnull()) ? '○' : '';
            $default = $column->getDefault();
            $rows .= sprintf("| %d | %s | %s | %s | %s | %s |\n", $i, $name, $type, $is_pkey, $is_notnull, $default);
            $i++;
        }
        
        return $header . $rows;
    }
    
    public function getForignKeyInfo()
    {
        $header = "| No | 外部キー | 参照先 | 更新時 | 削除時 |\n";
        $header .= "|:---|:---|:---|:---|:---|\n";
            /* @var $fkey ForeignKeyConstraint */
        $rows = '';
        $i = 1;
        foreach ($this->src_table->getForeignKeys() as $fkey)
        {
            $cols = implode(", ", $fkey->getColumns());
            $name = $fkey->getForeignTableName();
            $refs = implode(', ', $fkey->getForeignColumns());
            $text_ref  = sprintf("%s ( %s )", $name, $refs);
            $text_fkey = $cols;
            $rows .= sprintf("| %d | %s | %s | %s | %s |\n", $i, $text_fkey, $text_ref, $fkey->onUpdate(), $fkey->onDelete() );
            $i++;
        }
        
        return $header . $rows;
    }
}