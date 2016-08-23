<?php namespace App\Http\Middleware;

use Closure;
use App\Services\LanguageService;
use Auth;
use Input;
use Redirect;

class BeforeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $languages = array_keys(LanguageService::getSystemLangMinOptions());

        if (!Auth::check()) {
            if (in_array($request->segment(1), $languages)) {
                LanguageService::setSystemLang($request->segment(1));
                app()->setLocale($request->segment(1));
            } elseif (empty($request->segment(1)) || mb_strlen($request->segment(1)) == 2) {
                $currentUserSystemLang = get_cookie('systemLang');

                if (empty($currentUserSystemLang)) {
                    $detectLanguage = LanguageService::getDetectedCountryAndLang();
                    $currentUserSystemLang = empty(head($detectLanguage['language']))
                        ? LanguageService::getSystemLang()
                        : head($detectLanguage['language']);
                }

                return Redirect::to('/' . htmlentities($currentUserSystemLang));
            }
        }
        // language in theme pages
        if ($request->segment(1) == 'theme' && in_array($request->segment(2), $languages)) {
            $systemlang = get_cookie('systemLang');
            if (Input::get('language')) {
                return Redirect::to(url_to_themes(htmlentities($request->segment(3))));
            } elseif (Auth::check() && $systemlang != $request->segment(2)) {
                $user = Auth::user();
                LanguageService::setSystemLang($request->segment(2));
                $user->setting->update(array('lang' => $request->segment(2)));
            } elseif ($systemlang != $request->segment(2)) {
                LanguageService::setSystemLang($request->segment(2));
                app()->setLocale($request->segment(2));
            }
        }
        return $next($request);
    }
}
