<div class="bloger-like">
    <div class="list-number-social">
        <div class="info-number">
            <span>{{ $numberAnswer }}</span>
            <p class="">{{ Lang::get('labels.question.answers') }}</p>
        </div>
        <div class="info-number">
            <span>{{ $numberClip  }}</span>
            <p class="">{{ Lang::get('labels.clip') }}</p>
        </div>
        <div class="info-number no-border">
            <span>{{ $numberView }}</span>
            <p class="">{{ Lang::get('labels.view') }}</p>
        </div>
    </div>
    <button class="btn-favorite"><img
                src="{{asset('img/icon-clip-post.png')}}">{{ Lang::get('labels.question.clip_this_question') }}</button>
    <ul class="bloger-list">
        <li><a href="#" style="background: url(../img/2.png)"></a></li>
        <li><a href="#" style="background: url(../img/6.png)"></a></li>
        <li><a href="#" style="background: url(../img/5.png)"></a></li>
        <li><a href="#" style="background: url(../img/2.png)"></a></li>
        <li><a href="#" style="background: url(../img/6.png)"></a></li>
        <li><a href="#" style="background: url(../img/pic-1.png)">+15</a></li>
    </ul>
</div>