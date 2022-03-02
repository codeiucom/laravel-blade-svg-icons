<?php

namespace CodeIU\LaravelBladeSvgIcons;

trait SvgIconsTrait
{
    protected static array $svgIconCache = [];

    public function getSvg(string $icon)
    {
        $originStr = $icon;
        $style = config('codeiu-laravel-blade-svg-icon.heroicons-default-style');

        if (preg_match('/(.+)-(o|s)$/', $icon, $m)) {
            $icon = $m[1];

            $tmpStyle = $m[2] ?? null;
            if ($tmpStyle === 'o') {
                $style = 'outline';
            } elseif($tmpStyle === 's') {
                $style = 'solid';
            }
        }

        $cacheKey = 'cache-' . $originStr;
        if (isset(static::$svgIconCache[$cacheKey])) {
            $content = static::$svgIconCache[$cacheKey];
        } else {
            $iconFile = __DIR__ . '/resources/svg/heroicons/' . $style . '/' . $icon . '.svg';
            $content = '';

            if (is_file($iconFile)) {
                $content = file_get_contents($iconFile);

                $content = preg_replace("/[\r\n]/", " ", $content);
                $content = preg_replace("/[\s]{2,}/", " ", $content);
                $content = preg_replace("/>[\s]+(<[a-z\/])/", ">$1", $content);
                $content = trim($content);

                static::$svgIconCache[$cacheKey] = $content;
            }
        }

        return $content;
    }

    public function getTwbsSvg(string $icon)
    {
        $cacheKey = 'cache-twbs-' . $icon;
        if (isset(static::$svgIconCache[$cacheKey])) {
            $content = static::$svgIconCache[$cacheKey];
        } else {
            $iconFile = __DIR__ . '/resources/svg/twbs-icons/' . $icon . '.svg';
            $content = '';

            if (is_file($iconFile)) {
                $content = file_get_contents($iconFile);

                $content = preg_replace('/(width|height)="[0-9]+"/', '', $content);
                $content = preg_replace('/class="[^\"]+"/', '', $content);

                $content = preg_replace("/[\r\n]/", " ", $content);
                $content = preg_replace("/[\s]{2,}/", " ", $content);
                $content = preg_replace("/>[\s]+(<[a-z\/])/", ">$1", $content);
                $content = trim($content);

                static::$svgIconCache[$cacheKey] = $content;
            }
        }

        return $content;
    }
}
