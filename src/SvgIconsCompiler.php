<?php

namespace CodeIU\LaravelBladeSvgIcons;

use Illuminate\View\Compilers\ComponentTagCompiler;

class SvgIconsCompiler extends ComponentTagCompiler
{
    use SvgIconsTrait;

    protected string $heroIconsDirective = 'heroicons';
    protected string $twbsIconsDirective = 'twbsicons';

    protected array $heroiconsDefaultClass = [];
    protected array $twbsiconsDefaultClass = [];

    public function compile(string $value)
    {
        $this->heroIconsDirective = config('codeiu-laravel-blade-svg-icon.heroicons-prefix');
        $this->twbsIconsDirective = config('codeiu-laravel-blade-svg-icon.twbsicons-prefix');

        $tmpClasses = config('codeiu-laravel-blade-svg-icon.heroicons-default-classes');
        if (!empty($tmpClasses)) {
            $tmpArr = explode(' ', $tmpClasses);
            foreach ($tmpArr as $val) {
                $tmpKey = preg_replace('/-[0-9.\/]+$/', '-', $val);
                $this->heroiconsDefaultClass[$tmpKey] = $val;
            }
        }

        $tmpClasses = config('codeiu-laravel-blade-svg-icon.twbsicons-default-classes');
        if (!empty($tmpClasses)) {
            $tmpArr = explode(' ', $tmpClasses);
            foreach ($tmpArr as $val) {
                $tmpKey = preg_replace('/-[0-9.\/]+$/', '-', $val);
                $this->twbsiconsDefaultClass[$tmpKey] = $val;
            }
        }

        return $this->compileTags($value);
    }

    public function compileTags(string $value)
    {
        $value = $this->compileSelfClosingTags($value);
        $value = $this->compileOpeningTags($value);
        $value = $this->compileClosingTags($value);

        $value = $this->compileStatements($value);

        return $value;
    }

    protected function compileSelfClosingTags(string $value)
    {
        $directives = $this->directives();
        $pattern = "/
            <
                \s*
                ({$directives}[-\:][\w\-\:\.]*)
                \s*
                (?<attributes>
                    (?:
                        \s+
                        (?:
                            (?:
                                \{\{\s*\\\$attributes(?:[^}]+?)?\s*\}\}
                            )
                            |
                            (?:
                                [\w\-:.@]+
                                (
                                    =
                                    (?:
                                        \\\"[^\\\"]*\\\"
                                        |
                                        \'[^\']*\'
                                        |
                                        [^\'\\\"=<>]+
                                    )
                                )?
                            )
                        )
                    )*
                    \s*
                )
            \/>
        /x";

        return preg_replace_callback($pattern, function (array $matches) {
            $this->boundAttributes = [];

            $attributes = $this->getAttributesFromAttributeString($matches['attributes']);

            $svg = $this->componentString($matches[1], $attributes);
            if (empty($svg)) {
                return e($matches[0]);
            }

            return $svg;
        }, $value);
    }

    /**
     * Compile the opening tags within the given string.
     *
     * @param  string  $value
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    protected function compileOpeningTags(string $value)
    {
        $directives = $this->directives();

        $pattern = "/
            <
                \s*
                ({$directives}[-\:][\w\-\:\.]*)
                (?<attributes>
                    (?:
                        \s+
                        (?:
                            (?:
                                \{\{\s*\\\$attributes(?:[^}]+?)?\s*\}\}
                            )
                            |
                            (?:
                                [\w\-:.@]+
                                (
                                    =
                                    (?:
                                        \\\"[^\\\"]*\\\"
                                        |
                                        \'[^\']*\'
                                        |
                                        [^\'\\\"=<>]+
                                    )
                                )?
                            )
                        )
                    )*
                    \s*
                )
                (?<![\/=\-])
            >
        /x";

        return preg_replace_callback($pattern, function (array $matches) {
            $this->boundAttributes = [];

            $attributes = $this->getAttributesFromAttributeString($matches['attributes']);

            $svg = $this->componentString($matches[1], $attributes);
            if (empty($svg)) {
                return e($matches[0]);
            }

            return $svg;
        }, $value);
    }

    protected function compileClosingTags(string $value)
    {
        $directives = $this->directives();

        return preg_replace_callback("/<\/\s*({$directives}-[\w\-\:\.]+)\s*>/", function (array $matches) {
            if (!empty($matches[2]) && !empty(static::$svgIconCache['cache-' . $matches[1] . '-' . $matches[2]])) {
                return '';
            }

            return e($matches[0]);
        }, $value);
    }

    protected function componentString(string $icon, array $attributes)
    {
        $directives = $this->directives(false);
        preg_match('/^' . $directives . '-(.+)/', $icon, $matches);
        $brand = $matches[1];
        $iconName = $matches[2];
        unset($matches);

        if ($brand === $this->twbsIconsDirective) {
            $svg = $this->getTwbsSvg($iconName);
            $replaceClass = $this->twbsiconsDefaultClass;
        } else {
            $svg = $this->getSvg($iconName);
            $replaceClass = $this->heroiconsDefaultClass;
        }

        if (empty($svg)) {
            return null;
        }

        $tmpClass = $attributes['class'] ?? '';
        $tmpClass = trim($tmpClass, '"\'');


        if (!empty($replaceClass)) {
            foreach ($replaceClass as $key => $val) {
                if (!preg_match('/\b' . $key . '/', $tmpClass)) {
                    $tmpClass .= ' ' . $val;
                }
            }
            $tmpClass = trim($tmpClass);
        }
        $attributes['class'] = $tmpClass;

        $attr = [];
        foreach ($attributes as $attribute => $value) {
            $value = trim($value, '"\'');
            $attr[] = $attribute . '="' . $value . '"';
        }
        $attr = implode(' ', $attr);

        if (!empty($attr)) {
            $svg = str_replace('<svg xmlns=', '<svg ' . $attr . ' xmlns=', $svg);
        }

        $svg .= '<?php /* heroicons: ' . $icon . ' */?>';

        return $svg;
    }


    protected function compileStatements($value)
    {
        $directives = $this->directives();

        return preg_replace_callback(
            '/\B@(@?' . $directives . '-[a-z-]+)(\( ( (?>[^()]+) | (?2) )* \))?/x', function ($match) {
            return $this->compileStatement($match);
        }, $value
        );
    }

    protected function compileStatement($match)
    {
        if (str_contains($match[1], '@')) {
            $match[0] = isset($match[2]) ? $match[1] . $match[2] : $match[1];
        } else {
            $directives = $this->directives();

            $icon = $match[1] ?? '';
            $class = $match[3] ?? '';
            $class = trim($class, '"\'');
            if (empty($class)) {
                $attr = [];
            } else {
                $attr = ['class' => $class];
            }

            $svg = $this->componentString($icon, $attr);

            if (!empty($svg)) {
                return $svg;
            }
        }

        return $match[0];
    }

    protected function directives($skip = true)
    {
        $group = $skip ? '?:' : '';
        return '(' . $group . $this->heroIconsDirective . '|' . $this->twbsIconsDirective . ')';
    }
}
