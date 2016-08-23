<nav class="header-res">
    <div class="container">
        <div class="header-res-logo">
            <a class="global-logo pull-left" href="#"><img src={{asset('img/logo.png')}}></a>
        </div>
        <div class="header-res-menu">
            <ul class="controls pull-right">
                <li class="mobile-ranking">
                    <img src="{{asset('img/user-ranking.png')}}">
                </li>
                <li class="mobile-noti" role="button" aria-controls="m3" aria-expanded="false" tabindex="0">
                    <img src="{{asset('img/btn-bell2.png')}}">
                    <div class="notify">99</div>
                </li>
                <li class="mobile-avatar" role="button" aria-controls="m2" aria-expanded="false" tabindex="0">
                    <img src="{{asset('img/avar-1.jpg')}}">
                </li>
                <li class="mobile-menu" role="button" aria-controls="m1" aria-expanded="false" tabindex="0">
                    <img src="{{asset('img/icon-menu2.png')}}">
                </li>
            </ul>
        </div>
    </div>
</nav>

<div id="region_wrapper">
    <div class="container">
        <div id="m1" class="message" tabindex="-1" role="region" aria-labelledby="m1-label" aria-hidden="true">
            <ul>
                <li><input placeholder="Search"></li>
                <li class="dropdown"><a href="#" class="link-group"><span>Group</span></a>
                    <ul>
                        <li>
                            <a href="#">
                                <div class="group-img pull-left"><img src="{{asset('img/img-group-demo.jpg')}}"></div>
                                <div class="group-name">
                                    <h4>HTML5 & CSS3</h4>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <div class="group-img pull-left"><img src="{{asset('img/img-group-demo.jpg')}}"></div>
                                <div class="group-name">
                                    <h4>HTML5 & CSS3</h4>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <div class="group-img pull-left"><img src="{{asset('img/img-group-demo.jpg')}}"></div>
                                <div class="group-name">
                                    <h4>HTML5 & CSS3</h4>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <div class="group-img pull-left"><img src="{{asset('img/img-group-demo.jpg')}}"></div>
                                <div class="group-name">
                                    <h4>HTML5 & CSS3</h4>
                                </div>
                            </a>
                        </li>
                        <a href="#" class="group-see-all">See All</a>
                    </ul>
                </li>
                <li>
                    <a href="#" class="link-qna">Q&A</a>
                </li>
                <li><a href="#" class="link-post">Drafts</a></li>
                <li><a href="#" class="link-post">Post</a></li>
                <li><a href="/faq/questions/create" class="link-post">Question</a></li>
            </ul>
        </div>
        <div id="m2" class="message" role="region" aria-labelledby="m2-label" tabindex="-1" aria-hidden="true">
            <ul>
                <li><a href="#" class="link-clips">My Clips</a></li>
                <li><a href="#" class="link-profile">Profile</a></li>
                <li><a href="#" class="link-setting">Setting</a></li>
                <li><a href="#" class="link-language">Language</a></li>
                <li><a href="#" class="link-logout">Logout</a></li>
            </ul>
        </div>
        <div id="m3" class="message" role="region" aria-labelledby="m3-label" tabindex="-1" aria-hidden="true">
            <div class="wrap-notify-tabs">
                <!-- Nav tabs -->
                <div class="notify-tabs-header clearfix">
                    <ul class="nav nav-tabs notify-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#notifications" aria-controls="notifications"
                                                                  role="tab" data-toggle="tab">Notifications<span
                                        class="notify-count">99</span></a></li>
                        <li role="presentation"><a href="#request" aria-controls="request" role="tab" data-toggle="tab">Request<span
                                        class="notify-count">99</span></a></li>
                    </ul>
                    <a class="notify-setting" href="#"><img src="/img/icon-setting3.png"></a>
                </div>
                <!-- /.notify-tabs-header -->
                <div class="clearfix"></div>
                <!-- Tab panes -->
                <div class="tab-content notify-content">
                    <div role="tabpanel" class="tab-pane active" id="notifications">
                        <ul class="list-post">
                            <li class="entry-item">
                                <div class="entry-avatar">
                                    <a href="#">
                                        <img src="{{'img/notify-demo.png'}}" alt="">
                                    </a>
                                </div>
                                <!-- /.entry-avatar -->
                                <div class="info-box">
                                    <a href="#" class="author-name">Kanamikii</a>clipped post
                                    <a href="#" class="post-name">[Hướng dẫn] Cách tạo một khung cảnh kỳ lạ</a>
                                    of you
                                    <span class="entry-time">1 day ago</span>
                                </div>
                                <!-- /.info-box -->
                            </li>
                            <!-- /.entry-item -->
                            <li class="entry-item">
                                <div class="entry-avatar">
                                    <a href="#">
                                        <img src="{{'img/notify-demo.png'}}" alt="">
                                    </a>
                                </div>
                                <!-- /.entry-avatar -->
                                <div class="info-box">
                                    <a href="#" class="author-name">Kanamikii</a>clipped post
                                    <a href="#" class="post-name">[Hướng dẫn] Cách tạo một khung cảnh kỳ lạ</a>
                                    of you
                                    <span class="entry-time">1 day ago</span>
                                </div>
                                <!-- /.info-box -->
                            </li>
                            <!-- /.entry-item -->
                            <li class="entry-item">
                                <div class="entry-avatar">
                                    <a href="#">
                                        <img src="{{'img/notify-demo.png'}}" alt="">
                                    </a>
                                </div>
                                <!-- /.entry-avatar -->
                                <div class="info-box">
                                    <a href="#" class="author-name">Kanamikii</a>clipped post
                                    <a href="#" class="post-name">[Hướng dẫn] Cách tạo một khung cảnh kỳ lạ</a>
                                    of you
                                    <span class="entry-time">1 day ago</span>
                                </div>
                                <!-- /.info-box -->
                            </li>
                            <!-- /.entry-item -->
                            <li class="entry-item">
                                <div class="entry-avatar">
                                    <a href="#">
                                        <img src="{{'img/notify-demo.png'}}" alt="">
                                    </a>
                                </div>
                                <!-- /.entry-avatar -->
                                <div class="info-box">
                                    <a href="#" class="author-name">Kanamikii</a>clipped post
                                    <a href="#" class="post-name">Hướng dẫn] Cách tạo một khung cảnh kỳ lạ</a>
                                    of you
                                    <span class="entry-time">1 day ago</span>
                                </div>
                                <!-- /.info-box -->
                            </li>
                            <!-- /.entry-item -->
                        </ul>
                        <a href="#" class="see-all">See all</a>
                    </div>
                    <!-- notifications -->
                    <div role="tabpanel" class="tab-pane" id="request">
                        <ul class="list-post">
                            <li class="entry-item">
                                <div class="entry-avatar">
                                    <a href="#">
                                        <img src="{{'img/notify-demo.png'}}" alt="">
                                    </a>
                                </div>
                                <!-- /.entry-avatar -->
                                <div class="info-box">
                                    <a href="#" class="author-name">Kanamikii</a>request join
                                    <span class="viblo-break"></span>
                                    <a href="#" class="post-name">Public HTML5&CSS3</a>
                                    group
                                    <div class="viblo-btn-group clearfix">
                                        <a href="#" class="accept">Accept</a>
                                        <a href="#" class="decline">Decline</a>
                                    </div>
                                    <span class="entry-time">1 day ago</span>
                                </div>
                                <!-- /.info-box -->
                            </li>
                            <!-- /.entry-item -->
                            <li class="entry-item">
                                <div class="entry-avatar">
                                    <a href="#">
                                        <img src="{{'img/notify-demo.png'}}" alt="">
                                    </a>
                                </div>
                                <!-- /.entry-avatar -->
                                <div class="info-box">
                                    <a href="#" class="author-name">Kanamikii</a>request join
                                    <span class="viblo-break"></span>
                                    <a href="#" class="post-name">Public HTML5&CSS3</a>
                                    group
                                    <div class="viblo-btn-group clearfix">
                                        <a href="#" class="accept">Accept</a>
                                        <a href="#" class="decline">Decline</a>
                                    </div>
                                    <span class="entry-time">1 day ago</span>
                                </div>
                                <!-- /.info-box -->
                            </li>
                            <!-- /.entry-item -->
                            <li class="entry-item">
                                <div class="entry-avatar">
                                    <a href="#">
                                        <img src="{{'img/notify-demo.png'}}" alt="">
                                    </a>
                                </div>
                                <!-- /.entry-avatar -->
                                <div class="info-box">
                                    <a href="#" class="author-name">Kanamikii</a>request join
                                    <span class="viblo-break"></span>
                                    <a href="#" class="post-name">Public HTML5&CSS3</a>
                                    group
                                    <div class="viblo-btn-group clearfix">
                                        <a href="#" class="accept">Accept</a>
                                        <a href="#" class="decline">Decline</a>
                                    </div>
                                    <span class="entry-time">1 day ago</span>
                                </div>
                                <!-- /.info-box -->
                            </li>
                            <!-- /.entry-item -->
                            <li class="entry-item">
                                <div class="entry-avatar">
                                    <a href="#">
                                        <img src="{{'img/notify-demo.png'}}" alt="">
                                    </a>
                                </div>
                                <!-- /.entry-avatar -->
                                <div class="info-box">
                                    <a href="#" class="author-name">Kanamikii</a>request join
                                    <span class="viblo-break"></span>
                                    <a href="#" class="post-name">Public HTML5&CSS3</a>
                                    group
                                    <div class="viblo-btn-group clearfix">
                                        <a href="#" class="accept">Accept</a>
                                        <a href="#" class="decline">Decline</a>
                                    </div>
                                    <span class="entry-time">1 day ago</span>
                                </div>
                                <!-- /.info-box -->
                            </li>
                            <!-- /.entry-item -->
                        </ul>
                        <a href="#" class="see-all">See all</a>
                    </div>
                    <!-- request -->
                </div>
            </div>
            <!-- /.wrap-tabs -->
        </div>
    </div>
</div>
<!-- End Header Reponsive -->



<nav class="navbar">
    <div class="serch-show-hide" style="display: none">
        <div class="container">
            <input placeholder="To search type and hit enter">
        </div>
    </div>

    <div class="container header">
        <div class="left col-lg-6">
            <a class="global-logo" href="#"><img src="{{asset('img/logo-top-page.png')}}"></a>
            <button class="box-search"></button>
            <ul class="menu-list">
                <li><a href="#">Group</a></li>
                <li class="active"><a href="#">Q&A</a></li>
            </ul>
        </div>
        <div class="right">
            <div class="name-theme"><span>Theme of April: React.JS</span></div>
            <div class="user-posts">
                <div class="user-posts-btn">Post</div>
                <img src="{{asset('img/btn-header-down.png')}}">
                <div class="user-dropdown">
                    <ul>
                        <li><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span><a
                                    href="/faq/questions/create">Question</a></li>
                        <li><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span><a
                                    href="https://viblo.asia/u/ngheroi">Drafts</a></li>
                    </ul>
                </div>
            </div>
            <div class="user-ranking">
                <img src="/img/user-ranking.png">
            </div>
            <div class="user-login btn-notify">
                <div class="inner">
                    <img src="/img/btn-bell.png">
                    <div class="notify">99</div>
                </div>
                <div class="notify-down">
                    <div class="wrap-notify-tabs">
                       <!-- Nav tabs -->
                        <div class="notify-tabs-header clearfix">
                            <ul class="nav nav-tabs notify-tabs" role="tablist">
                                <li role="presentation" class="active"><a href="#notifications-desktop"
                                                                          aria-controls="notifications-desktop"
                                                                          role="tab" data-toggle="tab">Notifications<span
                                                class="notify-count">99</span></a></li>
                                <li role="presentation"><a href="#request-desktop" aria-controls="request-desktop"
                                                           role="tab" data-toggle="tab">Request<span
                                                class="notify-count">99</span></a></li>
                            </ul>
                            <a class="notify-setting" href="#"><img src="/img/icon-setting3.png"></a>
                        </div>
                        <!-- /.notify-tabs-header -->
                        <div class="clearfix"></div>
                        <!-- Tab panes -->
                        <div class="tab-content notify-content">
                            <div role="tabpanel" class="tab-pane active" id="notifications-desktop">
                                <ul class="list-post">
                                    <li class="entry-item">
                                        <div class="entry-avatar">
                                            <a href="#">
                                                <img src="img/notify-demo.png" alt="">
                                            </a>
                                        </div>
                                        <!-- /.entry-avatar -->
                                        <div class="info-box">
                                            <a href="#" class="author-name">Kanamikii</a>clipped post
                                            <a href="#" class="post-name">[Hướng dẫn] Cách tạo một khung cảnh kỳ
                                                lạ</a>
                                            of you
                                            <span class="entry-time">1 day ago</span>
                                        </div>
                                        <!-- /.info-box -->
                                    </li>
                                    <!-- /.entry-item -->
                                    <li class="entry-item">
                                        <div class="entry-avatar">
                                            <a href="#">
                                                <img src="img/notify-demo.png" alt="">
                                            </a>
                                        </div>
                                        <!-- /.entry-avatar -->
                                        <div class="info-box">
                                            <a href="#" class="author-name">Kanamikii</a>clipped post
                                            <a href="#" class="post-name">[Hướng dẫn] Cách tạo một khung cảnh kỳ
                                                lạ</a>
                                            of you
                                            <span class="entry-time">1 day ago</span>
                                        </div>
                                        <!-- /.info-box -->
                                    </li>
                                    <!-- /.entry-item -->
                                    <li class="entry-item">
                                        <div class="entry-avatar">
                                            <a href="#">
                                                <img src="img/notify-demo.png" alt="">
                                            </a>
                                        </div>
                                        <!-- /.entry-avatar -->
                                        <div class="info-box">
                                            <a href="#" class="author-name">Kanamikii</a>clipped post
                                            <a href="#" class="post-name">[Hướng dẫn] Cách tạo một khung cảnh kỳ
                                                lạ</a>
                                            of you
                                            <span class="entry-time">1 day ago</span>
                                        </div>
                                        <!-- /.info-box -->
                                    </li>
                                    <!-- /.entry-item -->
                                    <li class="entry-item">
                                        <div class="entry-avatar">
                                            <a href="#">
                                                <img src="fqa_img//notify-demo.png" alt="">
                                            </a>
                                        </div>
                                        <!-- /.entry-avatar -->
                                        <div class="info-box">
                                            <a href="#" class="author-name">Kanamikii</a>clipped post
                                            <a href="#" class="post-name">Hướng dẫn] Cách tạo một khung cảnh kỳ
                                                lạ</a>
                                            of you
                                            <span class="entry-time">1 day ago</span>
                                        </div>
                                        <!-- /.info-box -->
                                    </li>
                                    <!-- /.entry-item -->
                                </ul>
                                <a href="#" class="see-all">See all</a>
                            </div>
                            <!-- /#notifications -->
                            <div role="tabpanel" class="tab-pane" id="request-desktop">
                                <ul class="list-post">
                                    <li class="entry-item">
                                        <div class="entry-avatar">
                                            <a href="#">
                                                <img src="fqa_img//notify-demo.png" alt="">
                                            </a>
                                        </div>
                                        <!-- /.entry-avatar -->
                                        <div class="info-box">
                                            <a href="#" class="author-name">Kanamikii</a>request join
                                            <span class="viblo-break"></span>
                                            <a href="#" class="post-name">Public HTML5&CSS3</a>
                                            group
                                            <div class="viblo-btn-group clearfix">
                                                <a href="#" class="accept">Accept</a>
                                                <a href="#" class="decline">Decline</a>
                                            </div>
                                            <span class="entry-time">1 day ago</span>
                                        </div>
                                        <!-- /.info-box -->
                                    </li>
                                    <!-- /.entry-item -->
                                    <li class="entry-item">
                                        <div class="entry-avatar">
                                            <a href="#">
                                                <img src="fqa_img//notify-demo.png" alt="">
                                            </a>
                                        </div>
                                        <!-- /.entry-avatar -->
                                        <div class="info-box">
                                            <a href="#" class="author-name">Kanamikii</a>request join
                                            <span class="viblo-break"></span>
                                            <a href="#" class="post-name">Public HTML5&CSS3</a>
                                            group
                                            <div class="viblo-btn-group clearfix">
                                                <a href="#" class="accept">Accept</a>
                                                <a href="#" class="decline">Decline</a>
                                            </div>
                                            <span class="entry-time">1 day ago</span>
                                        </div>
                                        <!-- /.info-box -->
                                    </li>
                                    <!-- /.entry-item -->
                                    <li class="entry-item">
                                        <div class="entry-avatar">
                                            <a href="#">
                                                <img src="fqa_img//notify-demo.png" alt="">
                                            </a>
                                        </div>
                                        <!-- /.entry-avatar -->
                                        <div class="info-box">
                                            <a href="#" class="author-name">Kanamikii</a>request join
                                            <span class="viblo-break"></span>
                                            <a href="#" class="post-name">Public HTML5&CSS3</a>
                                            group
                                            <div class="viblo-btn-group clearfix">
                                                <a href="#" class="accept">Accept</a>
                                                <a href="#" class="decline">Decline</a>
                                            </div>
                                            <span class="entry-time">1 day ago</span>
                                        </div>
                                        <!-- /.info-box -->
                                    </li>
                                    <!-- /.entry-item -->
                                    <li class="entry-item">
                                        <div class="entry-avatar">
                                            <a href="#">
                                                <img src="fqa_img//notify-demo.png" alt="">
                                            </a>
                                        </div>
                                        <!-- /.entry-avatar -->
                                        <div class="info-box">
                                            <a href="#" class="author-name">Kanamikii</a>request join
                                            <span class="viblo-break"></span>
                                            <a href="#" class="post-name">Public HTML5&CSS3</a>
                                            group
                                            <div class="viblo-btn-group clearfix">
                                                <a href="#" class="accept">Accept</a>
                                                <a href="#" class="decline">Decline</a>
                                            </div>
                                            <span class="entry-time">1 day ago</span>
                                        </div>
                                        <!-- /.info-box -->
                                    </li>
                                    <!-- /.entry-item -->
                                </ul>
                                <a href="#" class="see-all">See all</a>
                            </div>
                            <!-- /#request -->
                        </div>
                    </div>
                    <!-- /.wrap-tabs -->
                </div>
            </div>
            <div class="user-login">
                <div class="user-avartar" style="background: url(img/btn-header-avar.png)"></div>
                <img src="/img/btn-header-down.png">
                <div class="user-dropdown">
                    <ul>
                        <li><span class="glyphicon glyphicon-paperclip" aria-hidden="true"></span><a
                                    href="https://viblo.asia/u/ngheroi">My Clips</a></li>
                        <li><span class="glyphicon glyphicon-lock" aria-hidden="true"></span><a
                                    href="https://viblo.asia/u/ngheroi">Profile</a></li>
                        <li><span class="glyphicon glyphicon-cog" aria-hidden="true"></span><a
                                    href="https://viblo.asia/settings/index">Setting</a></li>
                        <li><span class="glyphicon glyphicon-flag" aria-hidden="true"></span><a
                                    href="https://viblo.asia/languages">Language</a></li>
                        <li><span class="glyphicon glyphicon-user" aria-hidden="true"></span><a
                                    href="https://viblo.asia/settings/admin-index">Administration</a></li>
                        <li><span class="glyphicon glyphicon-off" aria-hidden="true"></span><a
                                    href="https://viblo.asia/users/logout">Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>
