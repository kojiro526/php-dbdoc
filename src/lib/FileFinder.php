<?php
namespace PhpDbdoc\lib;

/**
 * 対象となるファイルを取得する
 *
 * @author sasaki
 */
class FileFinder
{

    private $allow_extentions = [];

    private $file_paths = [];

    function __construct()
    {}

    /**
     * 許可される拡張子の一覧を配列でセットする
     *
     * @param array $extensions            
     */
    public function setAllowExtensions(array $extensions)
    {
        $this->allow_extentions = $extensions;
    }
    
    /**
     * 対象となるファイルをスキャンする。
     *
     * 引数で指定したパスから再帰的にファイルを探索する。
     *
     * 返却されるデータは1次元の配列となる。
     *
     * @param string $path
     *            ファイルのフルパスが渡されることを想定する。
     * @param
     *            array
     * @return array
     */
    public function scan($path)
    {
        $this->get_paths($path);
        return $this->file_paths;
    }

    /**
     * ファイルパスを再帰的にスキャン
     * 
     * 与えられたパスがディレクトリであれば再帰的にファイルを取得する。
     * 
     * 与えられたパスがファイルであればそのファイルのパスのみを取得する。
     * 
     * @param string $path
     */
    private function get_paths($path = null)
    {
        if (! ($real_path = realpath($path))) {
            new \Exception(sprintf('Invalid file path %s was given.', $path));
        }
        
        if (is_dir($real_path)) {
            $files = scandir($real_path);
            foreach ($files as $file) {
                if (in_array($file, [
                    '.',
                    '..'
                ]))
                    continue;
                
                $file_path = realpath($real_path . '/' . $file);
                
                $this->get_paths($file_path);
            }
        } else {
            $exploded = explode('.', $real_path);
            $ext = array_pop($exploded);
            if (in_array($ext, $this->allow_extentions)) {
                $this->addFiles($real_path);
            }
        }
    }

    /**
     * ファイルリストにパスを追加する。
     *
     * @param
     *            array|string
     */
    private function addFiles($files)
    {
        if (is_array($files)) {
            foreach ($files as $file) {
                $this->addFiles($this->file_paths);
            }
        } else {
            array_push($this->file_paths, $files);
        }
    }
}