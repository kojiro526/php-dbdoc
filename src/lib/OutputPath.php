<?php 
namespace PhpDbdoc\lib;

class OutputPath
{
    private $raw_path;
    private $dir_path;
    private $file_name;
    private $file_or_dir_name;
    
    public function __construct($path)
    {
        if (empty($path)) return;

        // 実在するパスの場合
        if ($output_path = realpath($path)) {
            if (is_dir($output_path)) $this->dir_path = $output_path;
            if (is_file($output_path)){
                $this->dir_path = pathinfo($output_path)['dirname'];
                $this->file_name = pathinfo($output_path)['basename'];
            }
            return;
        }

        $parent_dir_path = pathinfo($output_path)['dirname'];
        if ($parent_dir_path = realpath($parent_dir_path)){
            // 親がディレクトリとして実在する
            $this->dir_path = $parent_dir_path;
            $this->file_or_dir_name = pathinfo($output_path)['basename'];
        }
    }
    
    public function getRawPath()
    {
        return $this->raw_path;
    }
    
    public function isMissingPath()
    {
        return empty($this->dir_path);
    }
    
    public function getFileName()
    {
        return $this->file_name;
    }
    
    public function getDirPath()
    {
        return $this->dir_path;
    }
}
