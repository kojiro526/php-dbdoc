<?php 
namespace PhpDbdoc\lib;

class Table
{
    private $src_table = null;
    private $platform = null;
    
    public function __construct($table, $platform = null)
    {
        $this->src_table = $table;
        if (!empty($platform)) $this->platform = $platform;
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
        $header = "| No | 名前 | 型 | 主キー | 必須 | 初期値 | AI | US |\n";
        $header .= "|:---|:---|:---|:---:|:---|:---:|:---:|\n";
        $rows = '';
        $i = 1;
        foreach ($this->src_table->getColumns() as $column)
        {
            $name = $column->getName();
            $type = $column->getType()->getName();
            
            $length = $column->getLength();
            if (!empty($length)){
                $type .= sprintf("(%d)", $length);
            }
            $is_pkey = isset($pkeys[$name]) ? '○' : '';
            $is_notnull = !empty($column->getNotnull()) ? '○' : '';
            $default = $column->getDefault();
            $is_autoincrement = empty($column->getAutoincrement()) ? '' : '○';
            $is_unsigned = empty($column->getUnsigned()) ? '' : '○';
            $rows .= sprintf("| %d | %s | %s | %s | %s | %s | %s | %s |\n", $i, $name, $type, $is_pkey, $is_notnull, $default, $is_autoincrement, $is_unsigned);
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
            $text_fkey = sprintf("%s ( %s )", $fkey->getName(), $cols);
            $rows .= sprintf("| %d | %s | %s | %s | %s |\n", $i, $text_fkey, $text_ref, $fkey->onUpdate(), $fkey->onDelete() );
            $i++;
        }
        
        if ($rows == '') return '';
        return $header . $rows;
    }
    
    public function getIndexInfo()
    {
        $header = "| No | インデックス | 主キー | ユニーク |\n";
        $header .= "|:---|:---|:---:|:---:|\n";
        $rows = '';
        $i = 1;
        foreach ($this->src_table->getIndexes() as $index)
        {
            $text_index = sprintf("%s ( %s )", $index->getName(), implode(", ", $index->getColumns()));
            $is_pkey = empty($index->isPrimary()) ? '' : '○';
            $is_unique = empty($index->isUnique()) ? '' : '○';
            $rows .= sprintf("| %d | %s | %s | %s |\n", $i, $text_index, $is_pkey, $is_unique);
            $i++;
        }

        if ($rows == '') return '';
        return $header . $rows;
    }
}
