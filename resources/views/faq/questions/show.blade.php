@extends('layouts.default')

@section('css')
    {{ HTML::style('css/faq/home/faq-page.css') }}
    {{ HTML::style('css/faq/home/group.css') }}
    {{ HTML::style('css/faq/detail/faq-page-detail.css') }}
    {{ HTML::style('css/faq/detail/easy-responsive-tabs.css') }}
    {{ HTML::style('css/faq/detail/post-detail-groups.css') }}
    {{ HTML::style('css/faq/detail/post-detail.css') }}
    {{ HTML::style('css/faq/detail/group-detail.css') }}
    {{ HTML::style('css/faq/detail/group-post.css') }}
@endsection
@section('main')
    <div class="faq">
        <div class="post-detail row">
            <div class="col-md-9 col-lg-9 post-left">
                <div class="wrap-questions-detail">
                    <!-- /.faq-status -->
                    <div class="faq-status" {{ $solved['style'] }}>
                        {{ $solved['text'] }}
                    </div>
                    <!-- /.faq-detail-title -->
                    <h1 class="faq-detail-title">
                        {{{ $qTitle }}}
                    </h1>
                    <!-- /.tags -->
                    <div class="tags author-tag">
                        @include('categories._category_badges', ['categories' => $categories, 'takeCategoryNumber' => 50])
                    </div>
                    <!-- /.box-post-info -->
                    <div class="box-post-info">
                    <div class="btn-group">
                        @if (isset($currentUser) && $currentUser->id == $qAuthor['id'])
                            <a class="btn-edit" href="{{ route('faq.questions.edit', $qId) }}"
                                title="{{ trans('buttons.post.edit') }}">
                            </a>
                            <a class="btn-delete" href="javascript:void(0)"
                                data-url="{{ route('faq.questions.destroy', $qId) }}"
                                id="delete-post" data-message="{{ trans('messages.post.delete_confirm') }}"
                                data-label="{{ trans('messages.post.title_confirm') }}"
                                title="{{ trans('buttons.post.delete') }}">
                            </a>
                            @if (!empty($published))
                                <a class="btn-draft" href="javascript:void(0)"
                                    data-url="#"
                                    id="unpublished-post" data-message="{{ trans('messages.post.unpublished_confirm') }}"
                                    data-label="{{ trans('messages.post.title_confirm') }}"
                                    title="{{ trans('buttons.post.unpublished') }}">
                                </a>
                            @endif
                        @endif
                        <a href="#" class="btn-report" id="report-post"
                            data-post-id = "{{ $qId }}"
                            data-title="{{ trans('messages.report.box_title') }}"
                            data-message="<p>{{ trans('messages.report.box_header') }}</p>"
                            data-content='
                                <label class="display-block" for="report-type-0">
                                {{ Form::radio('type', \App\Data\Blog\Report::TYPE_SPAM, true, ['id' => 'report-type-0']) }}
                                {{ trans('messages.report.spam') }}</label><br>
                                <label class="display-block" for="report-type-1">
                                {{ Form::radio('type', \App\Data\Blog\Report::TYPE_ILLEGAL_CONTENT, false, ['id' => 'report-type-1']) }}
                                {{ trans('messages.report.illegal_content') }}</label><br>
                                <label class="display-block" for="report-type-2">
                                {{ Form::radio('type', \App\Data\Blog\Report::TYPE_HARASSMENT, false, ['id' => 'report-type-2']) }}
                                {{ trans('messages.report.harassment') }}</label>
                            '
                            data-report="{{ trans('buttons.report') }}">
                        </a>
                    </div>
                        <p class="post-info">
                            <a href="{{ route('getUser', $qAuthor['username']) }}" class="post-info-name">
                            {{ $qAuthor['name'] }}
                            </a>
                            <span>{{ $published }}</span>
                        </p>
                        <div class="clr"></div>
                    </div>
                    <!-- /.share-social -->
                    <div class="share-social static" style="top: 74px;">
                        <div class="fix-share">
                            <span>{{ trans('labels.share') }}</span>
                            <a class="face-share" target="_blank" href="http://www.facebook.com/sharer.php?u={{ route('faq.questions.show', ['id' => $qId, 'slug' => $qSlug]) }}&title={{{ $qTitle }}}">
                            </a>
                            <div class="face-share-count share-box-count">0</div>
                            <a class="tweet-share" target="_blank" href="https://opensharecount.com/count.json?url={{ route('faq.questions.show', ['id' => $qId, 'slug' => $qSlug]) }}">
                            </a>
                            <div class="tweet-share-count share-box-count">0</div>

                            <a class="clip btn-clip btn-favorite btn-stock" href="javascript:void(0)"></a>
                            <span class="toc">{{ trans('labels.toc') }}</span>
                            <div class="dropdown menu-toc pull-left" style="display: block;">
                                <a class="menu-toc-title dropdown-toggle toc" href="javascript:void(0)"
                                   id="dropdown-toc-menu" data-toggle="dropdown" aria-haspopup="true"
                                   aria-expanded="true">
                                </a>
                                 <ul class="dropdown-menu dropdown-menu-toc nav tocTreeContent" id="menuTocTree" aria-labelledby="dropdown-toc-menu"></ul>
                            </div>
                        </div>
                    </div>
                    <!-- /.faq-detail-content -->
                    <div class="faq-detail-content" id="content">
                        <section class="markdownContent cf break-word">{{{ $qContent }}}</section>
                    </div>
                    <!-- /.faq-detail-requestmore -->
                    @if(!empty($requestDetail))
                    <div class="faq-detail-requestmore">
                        <ul>
                        @foreach($requestDetail as $rKey => $request)
                            <li>
                                <p>
                                {{{ $request['requestDetailContent'] }}}
                                <span class="faq-detail-requestmore-display">- by <a href="{{ route('getUser', $request['requestDetailAccName']) }}">{{ $request['requestDetailUserName'] }}</a>
                                {{ $request['requestDetailCreatedAt'] }}
                                </span>
                                </p>
                                @if(!empty($request['answers']))
                                    @foreach($request['answers'] as $answer)
                                    <div class="requestmore-reply">
                                        <p>
                                        {{{ $answer['aContent'] }}}
                                        <span class="faq-detail-requestmore-display">- by <a href="{{$qAuthor['username']}}">
                                            {{ $qAuthor['name'] }}
                                        </a> {{ $answer['answeredAt'] }}</span>
                                        </p>
                                    </div>
                                    @endforeach
                                @endif
                                {{ $request['replyBtnLink'] }}
                                <div class="clr"></div>
                            </li>
                        @endforeach
                            <li class="no-border">
                                {{ $qRequestLink }}
                            </li>
                        </ul>
                        <div class="faq-comment-noti">
                            <p>For example...</p>
                            <ul>
                                <li>Could you give me little more detail of this part “xxxx (references from question)”
                                    in your question?
                                </li>
                                <li>Could you tell me your development environment?</li>
                                <li>There are some typos in “xxxx (references from question)”</li>
                            </ul>
                            <p>How to get references: Selecting the articles you want to use as refecences, and choose
                                "Reference" from option.</p>
                            <button class="faq-noti-close">OK</button>
                        </div>
                        <!-- /.faq-comment -->
                        <div class="faq-comment">
                            Comment...
                        </div>
                    </div>
                    @endif
                   @include('faq.comment.detail', $answers)
                    <div class="faq-request-answer">

                        <!--Horizontal Tab-->
                        <div id="parentHorizontalTab2">
                            <div class="faq-request-answer-top">
                                <h4>Do you need help? Ask users to answer this question.</h4>
                                <ul class="resp-tabs-list hor_2">
                                    <li>Recommended</li>
                                    @if(Auth::check())
                                    <li>Following</li>
                                    <li>Group</li>
                                    @endif
                                </ul>
                                <div class="clr"></div>
                            </div>
                            <div class="faq-request-answer-middle">
                                <div class="resp-tabs-container hor_2">
                                        @include('faq.includes.user_request', $users = $userRecommends)
                                        @include('faq.includes.user_request', $users = $userFollowing)
                                        @include('faq.includes.user_request', $users = $userInGroup)
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.faq-your-answer -->
                    <div class="faq-your-answer">
                        <h4>Your answer</h4>
                        <div class="clr"></div>
                        <div class="faq-comment-noti">
                            <p>Please be sure to write your answer based on refereces</p>
                            <p>If you want to....</p>
                            <ul>
                                <li>Reply to another answer <img src="../img/ico-arrow.png">Add comment</li>
                                <li>CNeed more details of this question <img src="../img/ico-arrow.png">Request more
                                    details about this question
                                </li>
                                <li>Ask for help <img src="../img/ico-arrow.png">Make a new question or ask users to
                                    answer this question
                                </li>
                            </ul>
                            <button class="faq-noti-close">OK</button>
                        </div>
                        <div class="faq-comment">
                            Comment...
                        </div>
                    </div>
                    <!-- /.faq-link-post -->
                    <div class="faq-link-post">
                        <div class="faq-link-post-inner">
                            <button>Post about this q&A</button>
                            <span>The link of this post is showed on Posts based on this Q&A.</span>
                        </div>
                        <div class="faq-link-post-inner">
                            <button>Create Group Q&A</button>
                            <span>Create new group included all users who questioned and answered in this question.</span>
                        </div>
                    </div>
                    <!-- /.faq-related-questions -->
                    <div class="faq-related-questions">
                        <!--Horizontal Tab-->
                        <div id="parentHorizontalTab">
                            <div class="faq-request-answer-top">
                                <h4>Related questions</h4>
                                <ul class="resp-tabs-list hor_1">
                                    <li>Unsolved</li>
                                    <li>Same Tag</li>
                                    <li>Most Answers</li>
                                    <li>Most Clips</li>
                                    <li>Most Helpful Answers</li>
                                </ul>
                                <div class="clr"></div>
                            </div>
                            <div class="faq-request-answer-middle">
                                <div class="resp-tabs-container hor_1">
                                    <div>
                                        <ul>
                                            <li><a href="#">Lorem ipsum dolor sit amet consectetur adipisicing elit sed
                                                    do eiusmod tempor incididunt ut labore et dolore magna aliqua?</a>
                                            </li>
                                            <li><a href="#">Lorem ipsum dolor sit amet consectetur adipisicing elit sed
                                                    et dolore magna aliqua?</a></li>
                                            <li><a href="#">Lorem ipsum dolor sit amet consectetur adipisicing elit sed
                                                    do eiusmod tempor incididunt ut labore et dolore magna aliqua?</a>
                                            </li>
                                            <li><a href="#">Lorem ipsum dolor sit amet consectetur adipisicing elit sed
                                                    et dolore magna aliqua?</a></li>
                                            <li><a href="#">Lorem ipsum dolor sit amet consectetur adipisicing elit sed
                                                    et?</a></li>
                                            <li><a href="#">Lorem ipsum dolor sit amet consectetur adipisicing elit sed
                                                    et dolore magna aliqua?</a></li>
                                        </ul>
                                    </div>
                                    
                                    <div>
                                        04
                                    </div>
                                    <div>
                                        05
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.faq-posts-based -->
                    <div class="faq-related-questions">
                        <div class="faq-request-answer-top">
                            <h4>Posts based on this Q&A</h4>
                            <div class="clr"></div>
                        </div>
                        <div class="faq-request-answer-middle">
                            <ul>
                                <li><a href="#">Lorem ipsum dolor sit amet consectetur adipisicing elit sed do eiusmod
                                        tempor incididunt ut labore et dolore magna aliqua?</a></li>
                                <li><a href="#">Lorem ipsum dolor sit et dolore magna aliqua?</a></li>
                                <li><a href="#">Lorem ipsum dolor sit amet consectetur adipisicing elit sed et dolore
                                        magna aliqua?</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- /.wrap-questions -->
            </div>
            <div class="col-md-3 col-lg-3 post-right">
                @include('faq.sidebarleft.clip')
                @include('faq.sidebarleft.average')
                @include('faq.sidebarleft.latest')
                @include('faq.sidebarleft.ranking')
                @include('faq.sidebarleft.category')
            </div>
        </div>
    </div>
@endsection

@section('script')
    {{ HTML::script('js/faq/easyResponsiveTabs.js') }}
    <script type="text/javascript">
        $(document).ready(function () {
            //Horizontal Tab
            $('#parentHorizontalTab').easyResponsiveTabs({
                type: 'default', //Types: default, vertical, accordion
                width: 'auto', //auto or any width like 600px
                fit: true, // 100% fit in a container
                tabidentify: 'hor_1', // The tab groups identifier
                activate: function (event) { // Callback function if tab is switched
                    var $tab = $(this);
                    var $info = $('#nested-tabInfo');
                    var $name = $('span', $info);
                    $name.text($tab.text());
                    $info.show();
                }
            });
            $('#parentHorizontalTab2').easyResponsiveTabs({
                type: 'default', //Types: default, vertical, accordion
                width: 'auto', //auto or any width like 600px
                fit: true, // 100% fit in a container
                tabidentify: 'hor_2', // The tab groups identifier
                activate: function (event) { // Callback function if tab is switched
                    var $tab = $(this);
                    var $info = $('#nested-tabInfo2');
                    var $name = $('span', $info);
                    $name.text($tab.text());
                    $info.show();
                }
            });

            // Child Tab
            $('#ChildVerticalTab_1').easyResponsiveTabs({
                type: 'vertical',
                width: 'auto',
                fit: true,
                tabidentify: 'ver_1', // The tab groups identifier
                activetab_bg: '#fff', // background color for active tabs in this group
                inactive_bg: '#F5F5F5', // background color for inactive tabs in this group
                active_border_color: '#c1c1c1', // border color for active tabs heads in this group
                active_content_border_color: '#5AB1D0' // border color for active tabs contect in this group so that it matches the tab head border
            });

            //Vertical Tab
            $('#parentVerticalTab').easyResponsiveTabs({
                type: 'vertical', //Types: default, vertical, accordion
                width: 'auto', //auto or any width like 600px
                fit: true, // 100% fit in a container
                closed: 'accordion', // Start closed if in accordion view
                tabidentify: 'hor_1', // The tab groups identifier
                activate: function (event) { // Callback function if tab is switched
                    var $tab = $(this);
                    var $info = $('#nested-tabInfo2');
                    var $name = $('span', $info);
                    $name.text($tab.text());
                    $info.show();
                }
            });
        });
    </script>

    {{ HTML::script('js/faq/home.js') }}
    {{ HTML::script('js/relationships.js') }}
    <script>
        var loading = {{ json_encode(['loading' => Lang::get('messages.loading'), 'loaded' => Lang::get('labels.load_more')]) }};
        var home = new home(loading);
    </script>

@endsection