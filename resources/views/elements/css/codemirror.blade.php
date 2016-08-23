{{ HTML::style('js/codemirror/lib/codemirror.css') }}
{{ HTML::style('js/codemirror/addon/fold/foldgutter.css') }}

@foreach(\App\Data\Blog\Setting::getThemeSettingFields() as $editorTheme)
         <?php
             $themeNameExplodeArr = explode(' ', $editorTheme);
             $themeFileName = strtolower(implode('-', $themeNameExplodeArr));
         ?>
        {{ HTML::style("js/codemirror/theme/$themeFileName.css") }}
@endforeach
