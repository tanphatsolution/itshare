<?php
// app/config/markdown.php

return [
    'html_allowed_tags' => ['br', 'hr'],
    'language_code_surport' => [
        'Batchfile',
        'C_Cpp',
        'Clojure',
        'Coffee',
        'Cobol',
        'Csharp',
        'CSS',
        'Golang',
        'Groovy',
        'HAML',
        'Haskell',
        'Haxe',
        'HTML',
        'Jade',
        'Java',
        'JavaScript',
        'JSON',
        'LaTeX',
        'LESS',
        'Lua',
        'Markdown',
        'Mysql',
        'ObjectiveC',
        'OCaml',
        'Pascal',
        'Perl', 'pgSQL',
        'PHP', 'PHP_HTML',
        'Powershell',
        'Python',
        'Ruby',
        'SASS',
        'Scala',
        'SCSS',
        'SH',
        'Smarty',
        'SQL',
        'Swift',
        'Textile',
        'TextileToHtml',
        'XML',
        'XQuery',
        'YAML'
    ],

    /*
    |--------------------------------------------------------------------------
    | Enable View Integration
    |--------------------------------------------------------------------------
    |
    | This option specifies if the view integration is enabled so you can write
    | markdown views and have them rendered as html. The following extensions
    | are currently supported: ".md", ".md.php", and ".md.blade.php". You may
    | disable this integration if it is conflicting with another package.
    |
    | Default: true
    |
    */

    'views' => true,

    /*
    |--------------------------------------------------------------------------
    | CommonMark Extenstions
    |--------------------------------------------------------------------------
    |
    | This option specifies what extensions will be automatically enabled.
    | Simply provide your extension class names here.
    |
    | Default: []
    |
    */

    'extensions' => ['Webuni\CommonMark\TableExtension\TableExtension'],

    /*
    |--------------------------------------------------------------------------
    | Renderer Configuration
    |--------------------------------------------------------------------------
    |
    | This option specifies an array of options for rendering HTML.
    |
    */

    'renderer' => [
        'block_separator' => "\n",
        'inner_separator' => "\n",
        'soft_break' => "\n",
    ],

    /*
    |--------------------------------------------------------------------------
    | Enable Em Tag Parsing
    |--------------------------------------------------------------------------
    |
    | This option specifies if `<em>` parsing is enabled.
    |
    | Default: true
    |
    */

    'enable_em' => true,

    /*
    |--------------------------------------------------------------------------
    | Enable Strong Tag Parsing
    |--------------------------------------------------------------------------
    |
    | This option specifies if `<strong>` parsing is enabled.
    |
    | Default: true
    |
    */

    'enable_strong' => true,

    /*
    |--------------------------------------------------------------------------
    | Enable Asterisk Parsing
    |--------------------------------------------------------------------------
    |
    | This option specifies if `*` should be parsed for emphasis.
    |
    | Default: true
    |
    */

    'use_asterisk' => true,

    /*
    |--------------------------------------------------------------------------
    | Enable Underscore Parsing
    |--------------------------------------------------------------------------
    |
    | This option specifies if `_` should be parsed for emphasis.
    |
    | Default: true
    |
    */

    'use_underscore' => true,

    /*
    |--------------------------------------------------------------------------
    | Safe Mode
    |--------------------------------------------------------------------------
    |
    | This option specifies if raw HTML is rendered in the document. Setting
    | this to true will not render HTML, and false will.
    |
    | Default: false
    |
    */

    'safe' => false,
];