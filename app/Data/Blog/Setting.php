<?php namespace App\Data\Blog;
/**
 * @author Tran Duc Thang
 */

use Illuminate\Database\Eloquent\SoftDeletes;

class Setting extends BaseModel
{

    CONST DEFAULT_POST_LANG_SET = 1;

    use SoftDeletes;

    // The database table used by the model.
    protected $table = 'settings';

    // The attributes which prevents the listed columns from mass assignment
    protected $guarded = ['id'];

    protected $fillable = [
        'lang',
        'default_post_language',
        'post_language_setting_flag',
        'top_page_language',
        'toppage_language_setting_flag',
        'display_email',
        'display_username_info',
        'display_phone_info',
        'display_social_accounts',
        'display_basic_profile',
        'display_occupation_info',
        'display_organization_info',
        'display_description_info',
        'display_location_info',
        'display_url_info',
        'display_github_info',
        'display_google_info',
        'display_facebook_info',
        'display_work_email',
        'receive_newsletter',
        'receive_comment_notification',
        'receive_mention_notification',
        'receive_follow_notification',
        'receive_stock_notification',
        'receive_mail_notification',
        'receive_monthly_magazine',
        'receive_weekly_magazine',
        'receive_other_mail',
        'viblo_theme',
    ];

    public function user()
    {
        return $this->belongsTo('App\Data\System\User');
    }

    public static function getNotificationSettingFields() {
        return [
            'receive_mention_notification',
            'receive_follow_notification',
            'receive_stock_notification',
            'receive_comment_notification',
            'receive_newsletter',
            'receive_mail_notification',
            'receive_monthly_magazine',
            'receive_weekly_magazine',
            'receive_other_mail',
        ];
    }

    public static function getNotificationSetting($user) {
        return Setting::where('user_id', $user->id)->get(self::getNotificationSettingFields())->first();
    }

    public static function getThemeSettingFields($theme = null) {
        $themes = [
            '3024 Day',
            '3024 Night',
            'Ambiance',
            'Ambiance Mobile',
            'Base16 Dark',
            'Base16 Light',
            'Blackboard',
            'Cobalt',
            'Colorforth',
            'Eclipse',
            'Elegant',
            'Erlang Dark',
            'Lesser Dark',
            'Liquibyte',
            'Mbo',
            'Mdn Like',
            'Midnight',
            'Monokai',
            'Neat',
            'Neo',
            'Night',
            'Paraiso Dark',
            'Paraiso Light',
            'Pastel On Dark',
            'Rubyblue',
            'Solarized',
            'The Matrix',
            'Tomorrow Night Bright',
            'Tomorrow Night Eighties',
            'Twilight',
            'Vibrant Ink',
            'Xq Dark',
            'Xq Light',
            'Zenburn'
        ];

        if (isset($theme)) {
            return $themes[$theme];
        }
        return $themes;
    }

    public static function getCodeMirrorLanguages() {
        return [
            'APL',
            'ASCIIArmor',
            'Asterisk',
            'Clike',
            'Clojure',
            'CMake',
            'COBOL',
            'CoffeeScript',
            'CommonLisp',
            'CSS',
            'Cypher',
            'D',
            'Dart',
            'Diff',
            'Django',
            // 'Dockerfile',
            'DTD',
            'Dylan',
            'EBNF',
            'ECL',
            'Eiffel',
            'Erlang',
            'Forth',
            'Fortran',
            'Gas',
            'GFM',
            'Gherkin',
            'Go',
            'Groovy',
            'HAML',
            // 'Handlebars',
            'Haskell',
            'Haxe',
            // 'HtmlEmbedded',
            'HTMLmixed',
            'HTTP',
            'IDL',
            'Jade',
            'JavaScript',
            'Jinja2',
            'Julia',
            'Kotlin',
            'LiveScript',
            'Lua',
            'Markdown',
            'mIRC',
            'MLLike',
            'Modelica',
            'MUMPS',
            'NGINX',
            'NTriples',
            'Octave',
            'Pascal',
            'PEGjs',
            'Perl',
            'PHP',
            'Pig',
            'Properties',
            'Puppet',
            'Python',
            'Q',
            'R',
            'RPM',
            'rST',
            'Ruby',
            'Rust',
            'Sass',
            'Scheme',
            'Shell',
            'Sieve',
            'SLIM',
            'Smalltalk',
            'Smarty',
            'Solr',
            'Soy',
            'SPARQL',
            'Spreadsheet',
            'SQL',
            'sTeX',
            'Stylus',
            'Swift',
            'Tcl',
            'Textile',
            'TiddlyWiki',
            'Tiki',
            'TOML',
            'Tornado',
            'Troff',
            'Turtle',
            'VB',
            'VBScript',
            'Velocity',
            'Verilog',
            'XML',
            'XQuery',
            'YAML',
            'Z80'
        ];
    }

    public static function getSupportedLanguages() {
        return [
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
            'Perl',
            'pgSQL',
            'PHP',
            'PHP_HTML',
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
            'YAML',
        ];
    }

    public static function getSupportedLanguageModes() {
        return [
            'batchfile' => 'text/x-sh',
            'c_cpp' => 'text/x-c++src',
            'clojure' => 'text/x-clojure',
            'coffee' => 'text/x-coffeescript',
            'cobol' => 'text/x-cobol',
            'csharp' => 'text/x-csharp',
            'css' => 'text/css',
            'golang' => 'text/x-go',
            'groovy' => 'text/x-groovy',
            'haml' => 'text/x-haml',
            'haskell' => 'text/x-haskell',
            'haxe' => 'text/x-haxe',
            'html' => 'text/html',
            'jade' => 'text/x-jade',
            'java' => 'text/x-java',
            'javascript' => 'text/javascript',
            'json' => 'application/ld+json',
            'latex' => 'text/x-stex',
            'less' => 'text/x-less',
            'lua' => 'text/x-lua',
            'markdown' => 'text/x-markdown',
            'mysql' => 'text/x-mysql',
            'objectivec' => 'text/x-objectivec',
            'ocaml' => 'text/x-ocaml',
            'pascal' => 'text/x-pascal',
            'perl' => 'text/x-perl',
            'pgsql' => 'text/x-sql',
            'php' => 'text/x-php',
            'php_html' => 'application/x-httpd-php',
            'powershell' => 'text/x-sh',
            'python' => 'text/x-python',
            'ruby' => 'text/x-ruby',
            'sass' => 'text/x-sass',
            'scala' => 'text/x-scala',
            'scss' => 'text/x-scss',
            'sh' => 'text/x-sh',
            'smarty' => 'text/x-smarty',
            'sql' => 'text/x-sql',
            'swift' => 'swift',
            'textile' => 'text/x-textile',
            'textiletohtml' => 'text/x-textile',
            'xml' => 'application/xml',
            'xquery' => 'application/xquery',
            'yaml' => 'text/x-yaml',
        ];
    }
}