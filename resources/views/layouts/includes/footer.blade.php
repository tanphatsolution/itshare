<footer>
    <a id="back-to-top" class="btn-top" href="javascript:void()"></a>
    <div class="container">
        <div class="col-sm-3 col-md-3 col-lg-3 box-footer facebook-footer">
            <p class="title-box">{{ trans('labels.footer.facebook') }}</p>
            <div class="fb-page" data-href="https://www.facebook.com/viblo.asia" data-hide-cover="false" data-show-facepile="true" data-show-posts="false">
                <div class="fb-xfbml-parse-ignore">
                    <blockquote cite="https://www.facebook.com/viblo.asia">
                        <a href="https://www.facebook.com/viblo.asia">{{ trans('labels.footer.viblo') }}</a>
                    </blockquote>
                </div>
            </div>
        </div>

        <div class="col-sm-3 col-md-3 col-lg-3 box-footer tag-footer">
            <p class="title-box">{{ trans('labels.footer.tag') }}</p>
            <ul class="box-tag">
                @foreach (\App\Services\HelperService::getHeaderCategories() as $shortName => $name)
                    <li class="{{ $shortName }} category-active">
                        <a href="{{ url_to_category($shortName) }}">{{ $name }}</a>
                    </li>
                @endforeach
                <li><a href="{{ action('CategoriesController@index') }}">{{ trans('labels.view_all') }}...</a></li>
            </ul>
        </div>
        <div class="col-sm-3 col-md-3 col-lg-3 box-footer author-footer">
            <p class="title-box">{{ trans('labels.footer.hot_author') }}</p>
            <ul class="box-author clear-dot">
                @foreach (\App\Services\PostService::getTopUsers(true) as $user)
                    <li>
                        <a href="{{ url_to_user($user) }}" >
                            <img src="data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" class="lazy" data-original = "{{ user_img_url($user, 50) }}"/>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="col-sm-3 col-md-3 col-lg-3 box-footer feedback-footer">
            <p class="title-box">{{ trans('labels.footer.feedback') }}</p>
            {{ Form::open(['action' => 'FeedbacksController@store','class' => 'box-feedback']) }}
                {{ Form::text('title', null, ['placeholder' => trans('feedbacks.create.title'), 'id' => 'title-feedback']) }}
                {{ Form::textarea('message', null, ['placeholder' => trans('feedbacks.create.message'), 'id' => 'message-feedback', 'rows' => '4']) }}
                {{ Form::text('email', (\App\Facades\Authority::check()) ? \App\Facades\Authority::getCurrentUser()->email : null, ['placeholder' => trans('feedbacks.create.email'), 'id' => 'email-user-feedback']) }}
                <button type="button" id="send-feed-back">{{ trans('feedbacks.create.submit') }}</button>
            {{ Form::close() }}
        </div>
    </div>

    <div class="container-fluid last-footer">
        <div class="container">
            <a class="{{(Request::segment(1)=='theme') ? 'col-sm-4 col-md-4 col-lg-4':'' }}" href="http://framgia.co.jp/" target="_blank"><span class="copyright">{{ trans('labels.footer.fr_inc') }}</span></a>

            @if(Request::segment(1)=='theme')
                <ul class="team-privacy clear-dot col-sm-4 col-md-4 col-lg-4">
                    <li>
                        <a href="#">{{ trans('labels.language') }}:</a> </li>
                        @foreach (\App\Services\LanguageService::getSystemLangMinOptions() as $codelang => $language)
                           <li>
                               <a href="{{ url_to_themes(Request::segment(3),$codelang) }}" class="{{ \App\Services\LanguageService::getSystemLang() ==  $codelang ? 'active' : '' }}"
                                  title="{{ $language }}">{{ $language }}</a>
                           </li>
                        @endforeach
                </ul>
            @endif

            <ul class="team-privacy clear-dot {{(Request::segment(1)=='theme') ? 'col-sm-4 col-md-4 col-lg-4':'team-privacy-right' }}">
                @foreach (\App\Services\LanguageService::getSystemLangMinOptions() as $codelang => $language)
                    @if ($codelang == $lang)
                        <li><a id="language-setting-footer" href="javascript:void(0);"><span class="flag_{{ $lang }}" >&nbsp;</span> {{ $language }}</a></li>
                    @endif
                @endforeach
                <li><a href="/groups/viblo_apis">{{ trans('labels.footer.viblo_api') }}</a></li>
                <li><a href="/terms">{{ trans('messages.user.terms') }}</a></li>
            </ul>
        </div>
    </div>
</footer>
