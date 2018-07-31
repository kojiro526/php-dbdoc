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

<!-- php-dbdoc_${pattern_name}-column-info Start -->

<!-- php-dbdoc_${pattern_name}-column-info End -->

### インデックス

<!-- php-dbdoc_${pattern_name}-index-info Start -->

<!-- php-dbdoc_${pattern_name}-index-info End -->

### 外部キー

<!-- php-dbdoc_${pattern_name}-fkey-info Start -->

<!-- php-dbdoc_${pattern_name}-fkey-info End -->

EOF;
        return $template;
    }
}