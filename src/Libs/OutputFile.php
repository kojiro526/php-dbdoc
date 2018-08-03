<?php 
namespace PhpDbdoc\Libs;

class OutputFile
{
    private $path;
    
    public function __construct($path = null)
    {
        $this->path = $path;
    }
    
    public function setFilePath($path)
    {
        $this->path = $path;
    }
    
    function getOriginContent()
    {
        $contents = '';
        if (!file_exists($this->path)) return '';

        $contents = file_get_contents($this->path);
        if($contents == false) return '';
        return $contents;
    }
    
    public function save($content)
    {
        file_put_contents($this->path, $content);
    }
    
    public function isExists()
    {
        return file_exists($this->path);
    }
}
