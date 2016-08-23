@extends('layouts.default')

@section('css')

    {{ HTML::style(version('css_min/home_top.min.css')) }}

@stop

@section('slider')
    <div class="row-full-width">
        <div class="row top-slide">
            <div class="col lrg-12">
                <div class="slider-wrap">
                    <div class="slider">
                        <div class="outer">
                            <div class="tray">
                                @if (isset($showSlider))
                                    @foreach ($randomProfessionals as $key => $professional)
                                        @if (count($randomProfessionals) > 1)
                                            @if (isset($monthSubject->img) && !empty($monthSubject->img) && $key === 1 )
                                                <section style="left:{{ $value }}%" class="all-articles current">
                                                    <div class="slider-item">
                                                        <div class="box-slide">
                                                            <div class="slide">
                                                                {{ HTML::image($monthSubject->img, $monthSubject->theme_name, ['width' => '100%', 'height' => '100%', 'class' => 'full-width']) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </section>
                                            @endif

                                            @if (!empty($professional->post))
                                                <section style="left:{{ $value }}%"
                                                         class="all-articles {{ ($key === 1 && !$flag) ? 'current' : '' }}">
                                                    <div class="box-slide">
                                                        <a href="{{ url_to_post($professional->post) }}">
                                                            <div class="slide" data-link="#">
                                                                <div class="box-text">
                                                                    <p class="main-theme">{{ $monthSubject->theme_name }}</p>
                                                                    <p class="sub-theme">{{ $professional->post->theme != null && isset($professional->post->theme->themeLanguages()->first()->name) && $professional->post->theme->themeLanguages()->first()->name != null ? $professional->post->theme->themeLanguages()->first()->name : "" }}</p>
                                                                    <p class="title-post">{{{ $professional->post->title }}}</p>
                                                                    <p class="name-author">{{ (is_null($professional->post->user->profile->first_name) && is_null($professional->post->user->profile->last_name)) || (empty($professional->post->user->profile->first_name) && empty($professional->post->user->profile->last_name)) ? $professional->post->user->name : $professional->post->user->profile->first_name . ' ' . $professional->post->user->profile->last_name }}</p>
                                                                </div>
                                                                <img src="{{ empty($professional->slider_img) ? \App\Services\HelperService::getSlidePostThumbnail($professional->post) : '/' . $professional->slider_img}}">
                                                            </div>
                                                        </a>
                                                    </div>
                                                </section>
                                            @endif

                                        @else
                                            <section
                                                    @if (isset($monthSubject->img) && !empty($monthSubject->img)) class="all-articles"
                                                    style="left:-100%" @else class="all-articles current"
                                                    style="left:0%" @endif>
                                                <div class="box-slide">
                                                    <a href="{{ url_to_post($professional->post) }}">
                                                        <div class="slide" data-link="#">
                                                            <div class="box-text">
                                                                <p class="main-theme">{{ $monthSubject->theme_name }}</p>
                                                                <p class="sub-theme">{{ !is_null($professional->post->theme) ? $professional->post->theme->themeLanguages()->first()->name : '' }}</p>
                                                                <p class="title-post">{{{ $professional->post->title }}}</p>
                                                                <p class="name-author">{{ (is_null($professional->post->user->profile->first_name) && is_null($professional->post->user->profile->last_name)) || (empty($professional->post->user->profile->first_name) && empty($professional->post->user->profile->last_name)) ? $professional->post->user->name : $professional->post->user->profile->first_name . ' ' . $professional->post->user->profile->last_name }}</p>
                                                            </div>
                                                            <img src="{{ empty($professional->slider_img) ? \App\Services\HelperService::getSlidePostThumbnail($professional->post) : '/' . $professional->slider_img}}">
                                                        </div>
                                                    </a>
                                                </div>
                                            </section>
                                            @if (isset($monthSubject->img) && !empty($monthSubject->img))
                                                <section style="left:0%" class="all-articles current">
                                                    <div class="slider-item">
                                                        <div class="box-slide">
                                                            <div class="slide">
                                                                {{ HTML::image($monthSubject->img, $monthSubject->theme_name, ['width' => '100%', 'height' => '100%', 'class' => 'full-width']) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </section>
                                            @endif
                                        @endif
                                    @endforeach
                                @elseif (isset($monthSubject->img) && !empty($monthSubject->img))
                                    <section style="left:0%" class="all-articles current">
                                        <div class="slider-item">
                                            <div class="box-slide">
                                                <div class="slide">
                                                    {{ HTML::image($monthSubject->img, $monthSubject->theme_name, ['width' => '100%', 'height' => '100%', 'class' => 'full-width']) }}
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                @endif
                            </div>
                        </div>
                        <div class="mask left">
                            @if (isset($showSlider))
                                <a href="#" class="arrow left"></a>
                            @endif
                        </div>
                        <div class="mask right">
                            @if (isset($showSlider))
                                <a href="#" class="arrow right"></a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('main')

    @if (($professionals->count()) && ((App\Facades\Authority::check() && App\Facades\Authority::getCurrentUser()->setting->top_page_language == 'vi') || ($topPageLanguage == 'vi')))
        <div class="container box-professional">
            {{ HTML::image('img/btn-title.png') }}
            <div class="title">
                <div class="center"><span>{{ trans('labels.pick_up') }}</span></div>
            </div>

            <div class="row content-pro" id="professional-area">
                @foreach($professionals as $professional)
                    @include('themes._a_professional', ['professional' => $professional, 'monthSubject' => $monthSubject])
                @endforeach
            </div>

            <a class="and-more load-more {{ ($hideSeeMore) ? 'hidden' : '' }}"
               href="#">{{ trans('labels.and_more') }}</a>
        </div>
    @endif

    <div class="container box-professional box-theme-month">
        {{ HTML::image('img/btn-title.png') }}

        <div class="title">
            <div class="center">
            <span>
                @if ($lang == 'ja')
                    {{ trans('datetime.month.' . $monthSubject->publish_month) }}{{ trans('labels.theme_of') }}
                @else
                    {{ trans('labels.theme_of') }} {{ trans('datetime.month.' . $monthSubject->publish_month) }}
                @endif
            </span>
            </div>
        </div>

        <p class="name-theme">{{ $monthSubject->theme_name }}</p>

        @if ($monthSubject->themes)
            <ul class="box-sub-theme">
                @foreach ($monthSubject->themes as $theme)
                    @if (isset($theme->id) && $theme->id != null && isset($monthSubject->short_name) && $monthSubject->short_name != null && !empty($theme->themeLanguages()->first()))
                        <li>
                            <a class="name-sub-theme"
                               href="{{ route('subThemeNameTabLocale',
                                [$lang, $monthSubject->short_name, $theme->short_name]) }}">
                                {{ $theme->themeLanguages()->first()->name }} &nbsp;
                                ({{  \App\Services\PostService::getPostByThemeCategoryCount($theme->id) }})
                            </a>
                            @if (\App\Facades\Authority::check() && isset($currentUser))
                                <a class="btn-post"
                                   href="{{ route('getPostCreateTheme',
                                    [$theme->id]) }}">{{ trans('labels.post') }}</a>
                            @endif
                        </li>
                    @endif
                @endforeach
            </ul>
        @endif
    </div>

    <div class="container box-professional box-back-number">
        <div>
            {{ HTML::image('img/btn-title.png') }}
        </div>

        <div class="title">
            <div class="center"><span>{{ trans('labels.monthly_theme.back_number') }}</span></div>
        </div>

        @if ($backNumberSubject)
            <?php $currentYear = null ?>

            @foreach ($backNumberSubject as $index => $pastSubject)
                <?php $changeYearFlag = ($currentYear != $pastSubject->publish_year); ?>
                <?php $currentYear = ($changeYearFlag ? $pastSubject->publish_year : $currentYear); ?>

                @if ($changeYearFlag)
                    <p class="number-year">{{ $currentYear }}</p>

                    <ul class="box-number">
                        @endif
                        <li>
                            <a href="{{ url_to_themes($pastSubject->short_name) }}"
                               title="{{ $pastSubject->short_name }}">
                                <span class="name-number">{{ $pastSubject->theme_name }}</span><span
                                        class="month-number">{{ trans('datetime.month.' . $pastSubject->publish_month) }}</span>
                            </a>
                        </li>
                        @if (isset($backNumberSubject[$index + 1]) && ($backNumberSubject[$index + 1]->publish_year != $currentYear))
                    </ul>
                @endif
            @endforeach
        @endif

    </div>
@stop


@section('script')

    @if(Session::has('feedbackCreatedMessage'))
        <script>alert('{{ Session::get("feedbackCreatedMessage") }}');</script>
    @endif

    <script type="text/javascript">
        var monthlyThemSubjectId = {{ $monthSubject->id }};
        var pageCount = 1;
    </script>
    {{ HTML::script(version('js_min/home_top.min.js'), ['defer' => 'defer']) }}

@stop
