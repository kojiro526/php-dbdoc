<?php
namespace PhpDbdoc\Libs;

/**
 * Output file writer
 *
 * @author sasaki
 */
class Template
{

    private $pattern = 'default';

    private $template = '';

    public function __construct($pattern = null)
    {
        if (! empty($pattern))
            $this->pattern = $pattern;
    }

    public function build()
    {
        $template = null;
        if (empty($this->template)) $template = $this->createDefaultTemplate($this->pattern);
        return new Content($template);
    }

    private function createDefaultTemplate($pattern_name)
    {
        $template = <<<EOF
## ${pattern_name}

### カラム定義

<!-- dbdoc_${pattern_name}-column-info Start -->

<!-- dbdoc_${pattern_name}-column-info End -->

### インデックス

<!-- dbdoc_${pattern_name}-index-info Start -->

<!-- dbdoc_${pattern_name}-index-info End -->

### 外部キー

<!-- dbdoc_${pattern_name}-fkey-info Start -->

<!-- dbdoc_${pattern_name}-fkey-info End -->

EOF;
        return $template;
    }
}