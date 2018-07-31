<?php 
namespace PhpDbdoc\Libs;

/**
 * Output file writer
 * 
 * @author sasaki
 */
class Writer
{
    private $tables = [];

    /* @var OutputPath $output_path */
    private $output_path;
    
    private $file_create = false;

    public function __construct($tables, $output_path, $file_create = false)
    {
        $this->tables = $tables;
        $this->output_path = $output_path;
        $this->file_create = $file_create;
    }
    
    public function save()
    {
        $finder = new FileFinder();
        $finder->setAllowExtensions(['md']);
        $file_paths = $finder->scan($this->output_path->getDirPath());
        
        /* @var Table $table */
        foreach($this->tables as $table)
        {
            if(empty($output_file_path = Table::findMyFile($table->getTableName(), $file_paths))){
                if($this->file_create) {
                    $output_file_path = $this->output_path->getDirPath() . DIRECTORY_SEPARATOR . $table->getTableName() . ".md";
                }
            }
            
            $output_file = new OutputFile($output_file_path);
            
            $content_source = $output_file->getOriginContent();
            if(empty($content_source)) {
                $content = (new Template($table->getTableName()))->build();
            }else{
                $content = new Content($content_source);
            }
            $content->addPiece($table->getTableName() . '-table-info', $table->getTableInfo());
            $content->addPiece($table->getTableName() . '-column-info', $table->getColumnsInfo());
            $content->addPiece($table->getTableName() . '-index-info', $table->getIndexInfo());
            $content->addPiece($table->getTableName() . '-fkey-info', $table->getForignKeyInfo());
            
            $output_file->save($content->build());
        }
    }
}
