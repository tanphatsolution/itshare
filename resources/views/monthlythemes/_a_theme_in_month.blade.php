<div class="theme-element fader @if (isset($zeroOpacity)) {{ 'zeroOpacity' }} @endif">
    <div class="vietnam">
        {{ Form::text('theme_name[vi][]', null, ['class' => 'theme']) }}
    </div>
    <div class="eng">
        {{ Form::text('theme_name[en][]', null, ['class' => 'theme']) }}
    </div>
    <div class="jap">
        {{ Form::text('theme_name[ja][]', null, ['class' => 'theme']) }}
    </div>
    <button class="add add-theme-this-month" type="button"></button>
    <div class="clearfix"></div>
</div>