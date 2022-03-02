# Laravel Blade Svg Icons Directives

## install

```
composer require codeiucom/laravel-blade-svg-icons
```

## use directive

### - Heroicons

* ex) @heroicons-`iconName`-`style`("`classes`")

1. default
   ```html
   @heroicons-folder("w-6 h-6")
   <!-- or -->
   <heroicons-folder class="w-6 h-6"/>
   <!-- or -->
   <heroicons-folder class="w-6 h-6"></heroicons-folder>
   ```
1. outline
   ```html
   @heroicons-folder-o("w-6 h-6")
   <!-- or -->
   <heroicons-folder-o class="w-6 h-6"/>
   <!-- or -->
   <heroicons-folder-o class="w-6 h-6"></heroicons-folder-o>
   ```
1. solid
   ```html
   @heroicons-folder-s("w-6 h-6")
   <!-- or -->
   <heroicons-folder-s class="w-6 h-6"/>
   <!-- or -->
   <heroicons-folder-s class="w-6 h-6"></heroicons-folder-s>
   ```

### - Bootstrap Icons

* ex) @twbsicons-`iconName`("`classes`")
   ```html
   @twbsicons-github("w-5 h-5")
   <!-- or -->
   <twbsicons-github class="w-5 h-5"/>
    ```

## Heroicons options

1. change directive ex)
   ```dotenv  
   # default heroicons
   CODEIU_LARAVEL_BLADE_SVG_HEROICONS_PREFIX=hicon
   # default twbsicons
   CODEIU_LARAVEL_BLADE_SVG_TWBS_ICONS_PREFIX=bsicon
   ```

1. set default class ex)
   ```dotenv  
   # default empty
   CODEIU_LARAVEL_BLADE_SVG_HEROICONS_DEFAULT_CLASSES="w-6 h-6"
   # default empty
   CODEIU_LARAVEL_BLADE_SVG_TWBSICONS_DEFAULT_CLASSES="w-5 h-5"
   ```
   if you use default class with tailwindcss, add below in tailwind.config.js
   ```
   module.exports = {
      ...
      content: [
         './storage/framework/views/*.php',
      ],
      ...
   }
   ```

1. change heroicons default style (default: solid)  
   ```dotenv  
   CODEIU_LARAVEL_SVG_BLADE_HEROICONS_DEFAULT_STYLE=outline
   ```

## svg icons

heroicons - v1.0.5  
https://github.com/tailwindlabs/heroicons  
twbs icons - v1.8.1  
https://github.com/twbs/icons  
