@extends('layouts.default')

@section('css')

{{ HTML::style(version('css_min/themes_back_number.min.css')) }}

@stop

@section('main')
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
                            <a href="{{ url_to_themes($pastSubject->short_name) }}">
                                <span class="name-number">{{ $pastSubject->theme_name }}</span><span class="month-number">{{ trans('datetime.month.' . $pastSubject->publish_month) }}</span>
                            </a>
                        </li>

                @if (isset($backNumberSubject[$index + 1]) && ($backNumberSubject[$index + 1]->publish_year != $currentYear))
                    </ul>
                @endif
            @endforeach
        @endif

    </div>
@stop