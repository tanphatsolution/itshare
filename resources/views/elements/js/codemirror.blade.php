{{ HTML::script('js/codemirror/lib/markdown-it.js') }}
{{ HTML::script('js/codemirror/lib/markdown-it-footnote.js') }}
{{ HTML::script('js/codemirror/lib/highlight.pack.js') }}
{{ HTML::script('js/codemirror/lib/emojify.js') }}
{{ HTML::script('js/codemirror/lib/codemirror.js') }}
{{ HTML::script('js/codemirror/lib/overlay.js') }}
{{ HTML::script('js/codemirror/lib/xml.js') }}
{{ HTML::script('js/codemirror/lib/markdown.js') }}
{{ HTML::script('js/codemirror/lib/gfm.js') }}
{{ HTML::script('js/codemirror/lib/gfm.js') }}
{{ HTML::script('js/codemirror/lib/javascript.js') }}
{{ HTML::script('js/codemirror/lib/css.js') }}
{{ HTML::script('js/codemirror/lib/htmlmixed.js') }}
{{ HTML::script('js/codemirror/lib/rawinflate.js') }}
{{ HTML::script('js/codemirror/lib/rawdeflate.js') }}

{{ HTML::script('js/codemirror/addon/edit/continuelist.js') }}
{{ HTML::script('js/codemirror/addon/fold/foldcode.js') }}
{{ HTML::script('js/codemirror/addon/fold/foldgutter.js') }}
{{ HTML::script('js/codemirror/addon/fold/brace-fold.js') }}
{{ HTML::script('js/codemirror/addon/fold/xml-fold.js') }}
{{ HTML::script('js/codemirror/addon/fold/markdown-fold.js') }}
{{ HTML::script('js/codemirror/addon/selection/active-line.js') }}
{{ HTML::script('js/codemirror/addon/edit/matchbrackets.js') }}


@foreach (\App\Data\Blog\Setting::getCodeMirrorLanguages() as $editorMode)
        <?php
           $editorModeName = strtolower($editorMode);
        ?>
        {{ HTML::script("js/codemirror/mode/$editorModeName/$editorModeName.js") }}
@endforeach

{{ HTML::script('js/textile.min.js') }}