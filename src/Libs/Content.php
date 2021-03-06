<?php 
namespace PhpDbdoc\Libs;

class Content
{
    private $origin_content;
    private $pieces = [];

    public function __construct($content)
    {
        $this->origin_content = $content;
    }

    public function addPiece($pattern, $content)
    {
        if (is_null($pattern)) throw new \Exception(sprintf("Invalid argument. pattern=%s", $pattern));
        array_push($this->pieces, ['pattern' => $pattern, 'content' => $content]);
    }
    
    public function build()
    {
        $origin = $this->origin_content;

        foreach($this->pieces as $piece)
        {
            $pattern_start = Content::getPatternTag($piece['pattern'], 'start');
            $pattern_end = Content::getPatternTag($piece['pattern'], 'end');
            $content = $pattern_start . "\n\n" . $piece['content'] . "\n\n" . $pattern_end;

            // 置換パターンを生成
            $pattern = Content::getPatternTag($piece['pattern']);
            if(!is_null($result = preg_replace($pattern, $content, $origin))){
                $origin = $result;
            }
        }
        
        return $origin;
    }
    
    public static function getPatternTag($key, $place = 'all')
    {
        $pattern_start = sprintf('<!-- dbdoc-%s Start -->', $key);
        $pattern_end = sprintf('<!-- dbdoc-%s End -->', $key);
        $pattern = sprintf('/%s.*?%s/s', $pattern_start, $pattern_end);
        
        if($place == 'all') return $pattern;
        if($place == 'start') return $pattern_start;
        if($place == 'end') return $pattern_end;
        return false;
    }
}
