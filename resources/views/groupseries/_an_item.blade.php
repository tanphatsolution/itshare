<div class="row col-sm-12">
    <div class="row">
        <div class="row">
            <div class="col-sm-10" style="padding:0px">
                {{Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Post title', 'readonly']) }}
            </div>
            <div class="col-sm-2" style="padding:0px ; height: 100%">
                <select id="language_code" name="language_code">
                    <option value="">{{ trans('socials.public') }}</option><option value="aa">{{ trans('socials.private') }}</option><option value="ab">{{ trans('socials.internet') }}</option>
                </select>
            </div>
            <div class="col-sm-3">
                <img src="/uploads/images/fbd3894db0f6e3ddd724cad8f3f5b3af878723e1//00b192a40df43739a46ee22df7a646be8a32c1c9.gif" height="80px" width="80px">
            </div>
            <div class="col-sm-9">
                {{ trans('socials.some_text') }}
            </div>
        </div>
        <div class="row">
        </div>
    </div>
</div>