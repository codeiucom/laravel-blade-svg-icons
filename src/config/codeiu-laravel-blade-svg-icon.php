<?php

$tmpClass = trim(env('CODEIU_LARAVEL_BLADE_SVG_HEROICONS_DEFAULT_CLASSES', ''));
$tmpClass = preg_replace('/[\s]{2,}/', ' ', $tmpClass);

$tmpTwbsClass = trim(env('CODEIU_LARAVEL_BLADE_SVG_TWBSICONS_DEFAULT_CLASSES', ''));
$tmpTwbsClass = preg_replace('/[\s]{2,}/', ' ', $tmpTwbsClass);

return [
    'heroicons-prefix' => env('CODEIU_LARAVEL_BLADE_SVG_HEROICONS_PREFIX', 'heroicons'),
    'heroicons-default-style' => env('CODEIU_LARAVEL_SVG_BLADE_HEROICONS_DEFAULT_STYLE', 'solid'),
    'heroicons-default-classes' => $tmpClass,

    'twbsicons-prefix' => env('CODEIU_LARAVEL_BLADE_SVG_TWBS_ICONS_PREFIX', 'twbsicons'),
    'twbsicons-default-classes' => $tmpTwbsClass,
];
