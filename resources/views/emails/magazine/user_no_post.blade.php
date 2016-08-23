@extends('emails.layouts.magazine')

@section('main')
    <tr>
        <td>
            <table bgcolor="#fff" width="100%" border="0" cellspacing="0" cellpadding="0" align="left" style="border: 1px solid #e6e6e6;">
                <tbody>
                <!--text-->
                <tr>
                    <td align="center" style="padding-bottom: 20px; padding-top: 20px;">
                        <span style="font-size: 16px; color: #000;">
                            {{ trans('magazine.post_in_month', ['href' => URL::to('posts'), 'number' => $lastMonthPost, 'month' => $lastMonth]) }}
                        </span>
                    </td>
                </tr>
                <!--end text-->
                <!--button-->
                <tr>
                    <td align="center">
                        <a href="{{ URL::to('posts/create') }}" style="font-size: 16px; font-weight: 600; color: #000; background: #e1dd00; border-radius: 2px; text-align: center; border: none; padding: 10px 30px;margin: 0 auto; margin-bottom: 30px; display:inline-block; cursor: pointer; text-decoration: none;">
                            <img src="{{ asset('img/ico-pen.png') }}" style="margin-right: 10px;">{{ trans('magazine.try_write') }}
                        </a>
                    </td>
                </tr>
                <!--end button-->
                @if (isset($followingPosts) && count($followingPosts) > 0)
                    <tr>
                        <td>
                            <table bgcolor="#fff" width="100%" border="0" cellspacing="0" cellpadding="0" align="left">
                                <tbody>
                                <!--header title-->
                                <tr align="left">
                                    <td width="10">&nbsp;</td>
                                    <td colspan="3">
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0" align="left">
                                            <tbody>
                                            <tr>
                                                <td style="font-size: 20px; font-weight: 600; margin-bottom: 15px; text-align: left; border-bottom: 1px solid #e5e5e5; padding-bottom: 10px; width: 100%;">{{ trans('magazine.your_following') }}</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td width="10">&nbsp;</td>
                                </tr>
                                <!--end header title-->
                                <!--margin-->
                                <tr>
                                    <td height="20">&nbsp;</td>
                                </tr>
                                <!--end margin-->
                                @foreach ($followingPosts as $post)
                                    <!--article-->
                                    <tr>
                                        <td width="10">&nbsp;</td>
                                        <td width="200" valign="top">
                                            <table width="200" border="0" cellspacing="0" cellpadding="0" valign="top">
                                                <tbody>
                                                <tr>
                                                    <td colspan="5" width="200" height="130" bgcolor="" valign="top">
                                                        <a href="{{ url_to_post($post) }}" style="display: block; width: 200px; max-height: 130px; text-decoration: none;">
                                                            <img src="{{ App\Services\HelperService::getPostThumbnail($post, App\Services\HelperService::THUMBNAIL_RIGHT_SIZE) }}" style="width: 200px; max-height: 130px; min-height: 130px;">
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="35" bgcolor="#000" height="25">&nbsp;</td>
                                                    <td width="55" align="center" bgcolor="#000" valign="middle">
                                                        <img src="{{ asset('img/icon-thumbnail-view2.png') }}" style="width:18px; height: auto; margin-right: 5px; float: left;">
                                                        <span style="color: #e1df19; text-align: center; font-size: 11px; position: relative; line-height:19px; float: left;">{{ $post->views_count }}</span>
                                                    </td>
                                                    <td width="50" align="center" bgcolor="#000" valign="middle">
                                                        <img src="{{ asset('img/icon-thumbnail-com2.png') }}" style="width:17px; height: auto; margin-right: 5px; float: left;">
                                                        <span style="color: #e1df19; text-align: center; font-size: 11px; position: relative; line-height:19px; float: left;">{{ $post->comments_count }}</span>
                                                    </td>
                                                    <td width="50" align="center" bgcolor="#000" valign="middle">
                                                        <img src="{{ asset('img/icon-thumbnail-clip2.png') }}" style="width:12px; height: auto; margin-right: 5px; float: left; margin-top: 3px;">
                                                        <span style="color: #e1df19; text-align: center; font-size: 11px; position: relative; line-height:19px; float: left;">{{ $post->blocked }}</span>
                                                    </td>
                                                    <td width="15" bgcolor="#000">&nbsp;</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                        <td width="20">&nbsp;</td>
                                        <td aligh="left" valign="top">
                                            <table border="0" cellspacing="0" cellpadding="0" valign="top">
                                                <tbody>
                                                <tr>
                                                    <td>
                                                        <a href="{{ url_to_post($post) }}" style=" display:block; font-size: 16px; color: #343434; font-weight: 600; line-height: 25px; text-decoration: none; margin-top: -5px; margin-bottom: 5px;">{{{ isset($post->title) ? $post->title : "" }}}</a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <table border="0" cellspacing="0" cellpadding="0">
                                                            <tbody>
                                                            <tr>
                                                                <td valign="top">
                                                                    <a href="{{ url_to_user($post->user) }}" style="float: left; margin-top: 2px; line-height: 20px;">
                                                                        <img src="{{ user_img_url($post->user, 100) }}" style="width: 20px; height: 20px; border-radius: 20px; display: inline-block; background-position: center !important; background-size: cover !important; background-repeat: none !important;">
                                                                    </a>
                                                                </td>
                                                                <td width="5">&nbsp;</td>
                                                                <td valign="top">
                                                                    <a href="{{ url_to_user($post->user) }}" style="text-decoration:none; line-height: 20px;">
                                                                        <span class="author-name" style="font-size: 12px; color: #000; font-style: italic; text-decoration: underline; margin-right: 5px;">{{{ get_full_name_of_user($post->user) }}}</span>
                                                                    </a>
                                                                </td>
                                                                <td valign="top">
                                                                    <span style="font-size: 12px; font-style: italic; text-decoration: underline; color: #6f6f6f; text-decoration: none; line-height: 20px;">{{ trans('labels.posted_on') }}</span>
                                                                    <span class="date" style="font-size: 12px; color: #6f6f6f; text-decoration: none; line-height: 20px;">{{ convert_to_japanese_date(is_null($post->published_at) ? $post->updated_at : $post->published_at) }}</span>
                                                                </td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td height="10">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <p class="detail break-word" style="font-size: 13px; margin: 0 0 10px; color: #333333; line-height: 20px; max-height: 80px; overflow: hidden;">{{{ App\Services\HelperService::getPostDescription($post->getParsedContent(), 200) }}}</p>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                        <td width="10">&nbsp;</td>
                                    </tr>
                                    <!--end article-->
                                    <!--margin-->
                                    <tr>
                                        <td height="20">&nbsp;</td>
                                        <td colspan="3" height="20">
                                            <hr style="display: block; height: 1px; border: 0; border-top: 1px solid #e4e4e4; margin: 1em 0; padding: 0;">
                                        </td>
                                        <td height="20">&nbsp;</td>
                                    </tr>
                                    <!--end margin-->
                                @endforeach
                                <!--button-->
                                <tr align="left">
                                    <td width="10">&nbsp;</td>
                                    <td colspan="3">
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                            <tbody>
                                            <tr>
                                                <td>
                                                    <a href="{{ URL::to('posts/create') }}" style="font-size: 13px; color: #e1df1a; background: #000; padding: 3px 15px; border: none; font-weight: 300; line-height: 25px; cursor: pointer; text-decoration: none;">
                                                        <img src="{{ asset('img/ico-pen2.png') }}" class="display2" style="width: 10px; height: auto; margin-right: 7px;">{{ trans('magazine.try_write') }}
                                                    </a>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td width="10">&nbsp;</td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <!--margin-->
                    <tr>
                        <td height="30">&nbsp;</td>
                    </tr>
                @endif

                <!--THEME OF MONTH-->
                @if (isset($short_name) && isset($monthSubject))
                    <tr>
                        <td>
                            <table bgcolor="#fff" width="100%" border="0" cellspacing="0" cellpadding="0" align="left">
                                <tbody>
                                <!--header title-->
                                <tr align="left">
                                    <td width="10">&nbsp;</td>
                                    <td colspan="3">
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0" align="left">
                                            <tbody>
                                            <tr>
                                                <td style="font-size: 20px; font-weight: 600; margin-bottom: 15px; text-align: left; border-bottom: 1px solid #e5e5e5; padding-bottom: 10px; width: 100%;">{{ trans('magazine.theme_of_month') }}
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td width="10">&nbsp;</td>
                                </tr>
                                <!--margin-->
                                <tr>
                                    <td height="20">&nbsp;</td>
                                </tr>
                                <!--img-->
                                <tr>
                                    <td>&nbsp;</td>
                                    <td colspan="3"align="center">
                                        <a href="{{ url_to_themes($short_name) }}" style="display: block; text-decoration: none;">
                                            {{ HTML::image($monthSubject->img, $monthSubject->theme_name, ['width' => '100%', 'height' => 'auto', 'max-height' => '300px', 'border' => '1px solid #e4e4e4']) }}
                                        </a>
                                    </td>
                                    <td>&nbsp;</td>
                                </tr>
                                <!--margin-->
                                <tr>
                                    <td height="20">&nbsp;</td>
                                </tr>
                                <!--button-->
                                <tr>
                                    <td>&nbsp;</td>
                                    <td colspan="3" align="center">
                                        <a href="{{ URL::to('posts/create') }}" style="font-size: 16px; font-weight: 600; color: #000; background: #e1dd00; border-radius: 2px; text-align: center; border: none; padding: 10px 30px;margin: 0 auto; display: block; margin-bottom: 10px; display:inline-block; cursor: pointer; text-decoration: none;">
                                            <img src="{{ asset('img/ico-pen.png') }}" style="margin-right: 10px;">
                                            {{ trans('magazine.share_knowledge', ['theme' => $monthSubject->theme_name]) }}
                                        </a>
                                    </td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td colspan="3" align="center">
                                        <span style="font-size: 14px; color: #000; line-height: 20px; display:inline-block;">{{ trans('magazine.refer_article') }}</span>
                                    </td>
                                    <td>&nbsp;</td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <!--margin-->
                    <tr>
                        <td height="30">&nbsp;</td>
                    </tr>
                @endif
                <!--END THEME OF MONTH-->

                <!--MOST CLIP-->
                @if (isset($mostClips) && count($mostClips) > 0)
                    <tr>
                        <td>
                            <table bgcolor="#fff" width="100%" border="0" cellspacing="0" cellpadding="0" align="left">
                                <tbody>
                                <!--header title-->
                                <tr align="left">
                                    <td width="10">&nbsp;</td>
                                    <td colspan="3">
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0" align="left">
                                            <tbody>
                                            <tr>
                                                <td style="font-size: 20px; font-weight: 600; margin-bottom: 15px; text-align: left; border-bottom: 1px solid #e5e5e5; padding-bottom: 10px; width: 100%;">{{ trans('magazine.most_clip') }}
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td width="10">&nbsp;</td>
                                </tr>
                                <!--margin-->
                                <tr>
                                    <td height="20">&nbsp;</td>
                                </tr>
                                @foreach ($mostClips as $post)
                                    <!--article-->
                                    <tr>
                                        <td width="10">&nbsp;</td>
                                        <td width="200" valign="top">
                                            <table width="200" border="0" cellspacing="0" cellpadding="0" valign="top">
                                                <tbody>
                                                <tr>
                                                    <td colspan="5" width="200" height="130" bgcolor="" valign="top">
                                                        <a href="{{ url_to_post($post) }}" style="display: block; width: 200px; max-height: 130px; text-decoration: none;">
                                                            <img src="{{ App\Services\HelperService::getPostThumbnail($post, App\Services\HelperService::THUMBNAIL_RIGHT_SIZE) }}" style="width: 200px; max-height: 130px; min-height: 130px;">
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="35" bgcolor="#000" height="25">&nbsp;</td>
                                                    <td width="55" align="center" bgcolor="#000" valign="middle">
                                                        <img src="{{ asset('img/icon-thumbnail-view2.png') }}" style="width:18px; height: auto; margin-right: 5px; float: left;">
                                                        <span style="color: #e1df19; text-align: center; font-size: 11px; position: relative; line-height:19px; float: left;">{{ $post->views_count }}</span>
                                                    </td>
                                                    <td width="50" align="center" bgcolor="#000" valign="middle">
                                                        <img src="{{ asset('img/icon-thumbnail-com2.png') }}" style="width:17px; height: auto; margin-right: 5px; float: left;">
                                                        <span style="color: #e1df19; text-align: center; font-size: 11px; position: relative; line-height:19px; float: left;">{{ $post->comments_count }}</span>
                                                    </td>
                                                    <td width="50" align="center" bgcolor="#000" valign="middle">
                                                        <img src="{{ asset('img/icon-thumbnail-clip2.png') }}" style="width:12px; height: auto; margin-right: 5px; float: left; margin-top: 3px;">
                                                        <span style="color: #e1df19; text-align: center; font-size: 11px; position: relative; line-height:19px; float: left;">{{ $post->blocked }}</span>
                                                    </td>
                                                    <td width="15" bgcolor="#000">&nbsp;</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                        <td width="20">&nbsp;</td>
                                        <td aligh="left" valign="top">
                                            <table border="0" cellspacing="0" cellpadding="0" valign="top">
                                                <tbody>
                                                <tr>
                                                    <td>
                                                        <a href="{{ url_to_post($post) }}" style=" display:block; font-size: 16px; color: #343434; font-weight: 600; line-height: 25px; text-decoration: none; margin-top: -5px; margin-bottom: 5px; text-decoration: none;">{{{ isset($post->title) ? $post->title : "" }}}</a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <table border="0" cellspacing="0" cellpadding="0">
                                                            <tbody>
                                                            <tr>
                                                                <td valign="top">
                                                                    <a href="{{ url_to_user($post->user) }}" style="float: left; margin-top: 2px; line-height: 20px; text-decoration: none;">
                                                                        <img src="{{ user_img_url($post->user, 100) }}" style="width: 20px; height: 20px; border-radius: 20px; display: inline-block; background-position: center !important; background-size: cover !important; background-repeat: none !important;">
                                                                    </a>
                                                                </td>
                                                                <td width="5">&nbsp;</td>
                                                                <td valign="top">
                                                                    <a href="{{ url_to_user($post->user) }}" style="text-decoration:none; line-height: 20px; text-decoration: none;">
                                                                        <span class="author-name" style="font-size: 12px; color: #000; font-style: italic; text-decoration: underline; margin-right: 5px;">{{{ get_full_name_of_user($post->user) }}}</span>
                                                                    </a>
                                                                </td>
                                                                <td valign="top">
                                                                    <span style="font-size: 12px; font-style: italic; text-decoration: underline; color: #6f6f6f; text-decoration: none; line-height: 20px;">{{ trans('labels.posted_on') }}</span>
                                                                    <span class="date" style="font-size: 12px; color: #6f6f6f; text-decoration: none; line-height: 20px;">{{ convert_to_japanese_date(is_null($post->published_at) ? $post->updated_at : $post->published_at) }}</span>
                                                                </td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td height="10">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <p class="detail break-word" style="font-size: 13px; margin: 0 0 10px; color: #333333; line-height: 20px; max-height: 80px; overflow: hidden;">{{{ App\Services\HelperService::getPostDescription($post->getParsedContent(), 200) }}}</p>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                        <td width="10">&nbsp;</td>
                                    </tr>
                                    <!--end article-->
                                    <!--margin-->
                                    <tr>
                                        <td height="20">&nbsp;</td>
                                        <td colspan="3" height="20">
                                            <hr style="display: block; height: 1px; border: 0; border-top: 1px solid #e4e4e4; margin: 1em 0; padding: 0;">
                                        </td>
                                        <td height="20">&nbsp;</td>
                                    </tr>
                                    <!--end margin-->
                                @endforeach
                                <!--button-->
                                <tr align="left">
                                    <td width="10">&nbsp;</td>
                                    <td colspan="3">
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                            <tbody>
                                            <tr>
                                                <td>
                                                    <a href="{{ URL::to('posts/create') }}" style="font-size: 13px; color: #e1df1a; background: #000; padding: 3px 15px; border: none; font-weight: 300; line-height: 25px; cursor: pointer; text-decoration: none;">
                                                        <img src="{{ asset('img/ico-pen2.png') }}" class="display2" style="width: 10px; height: auto; margin-right: 7px;">{{ trans('magazine.try_write') }}
                                                    </a>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td width="10">&nbsp;</td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <!--margin-->
                    <tr>
                        <td height="30">&nbsp;</td>
                    </tr>
                @endif
                <!--END MOST CLIP-->

                <!--MOST HELPFUL-->
                @if (isset($mostHelpful) && count($mostHelpful) > 0)
                    <tr>
                        <td>
                            <table bgcolor="#fff" width="100%" border="0" cellspacing="0" cellpadding="0" align="left">
                                <tbody>
                                <!--header title-->
                                <tr align="left">
                                    <td width="10">&nbsp;</td>
                                    <td colspan="3">
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0" align="left">
                                            <tbody>
                                            <tr>
                                                <td style="font-size: 20px; font-weight: 600; margin-bottom: 15px; text-align: left; border-bottom: 1px solid #e5e5e5; padding-bottom: 10px; width: 100%;">{{ trans('magazine.most_helpful') }}
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td width="10">&nbsp;</td>
                                </tr>
                                <!--margin-->
                                <tr>
                                    <td height="20">&nbsp;</td>
                                </tr>
                                @foreach ($mostHelpful as $post)
                                    <!--article-->
                                    <tr>
                                        <td width="10">&nbsp;</td>
                                        <td width="200" valign="top">
                                            <table width="200" border="0" cellspacing="0" cellpadding="0" valign="top">
                                                <tbody>
                                                <tr>
                                                    <td colspan="5" width="200" height="130" bgcolor="" valign="top">
                                                        <a href="{{ url_to_post($post) }}" style="display: block; width: 200px; max-height: 130px; text-decoration: none;">
                                                            <img src="{{ App\Services\HelperService::getPostThumbnail($post, App\Services\HelperService::THUMBNAIL_RIGHT_SIZE) }}" style="width: 200px; max-height: 130px; min-height: 130px;">
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="35" bgcolor="#000" height="25">&nbsp;</td>
                                                    <td width="55" align="center" bgcolor="#000" valign="middle">
                                                        <img src="{{ asset('img/icon-thumbnail-view2.png') }}" style="width:18px; height: auto; margin-right: 5px; float: left;">
                                                        <span style="color: #e1df19; text-align: center; font-size: 11px; position: relative; line-height:19px; float: left;">{{ $post->views_count }}</span>
                                                    </td>
                                                    <td width="50" align="center" bgcolor="#000" valign="middle">
                                                        <img src="{{ asset('img/icon-thumbnail-com2.png') }}" style="width:17px; height: auto; margin-right: 5px; float: left;">
                                                        <span style="color: #e1df19; text-align: center; font-size: 11px; position: relative; line-height:19px; float: left;">{{ $post->comments_count }}</span>
                                                    </td>
                                                    <td width="50" align="center" bgcolor="#000" valign="middle">
                                                        <img src="{{ asset('img/icon-thumbnail-clip2.png') }}" style="width:12px; height: auto; margin-right: 5px; float: left; margin-top: 3px;">
                                                        <span style="color: #e1df19; text-align: center; font-size: 11px; position: relative; line-height:19px; float: left;">{{ $post->blocked }}</span>
                                                    </td>
                                                    <td width="15" bgcolor="#000">&nbsp;</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                        <td width="20">&nbsp;</td>
                                        <td aligh="left" valign="top">
                                            <table border="0" cellspacing="0" cellpadding="0" valign="top">
                                                <tbody>
                                                <tr>
                                                    <td>
                                                        <a href="{{ url_to_post($post) }}" style=" display:block; font-size: 16px; color: #343434; font-weight: 600; line-height: 25px; text-decoration: none; margin-top: -5px; margin-bottom: 5px; text-decoration: none;">{{{ isset($post->title) ? $post->title : "" }}}</a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <table border="0" cellspacing="0" cellpadding="0">
                                                            <tbody>
                                                            <tr>
                                                                <td valign="top">
                                                                    <a href="{{ url_to_user($post->user) }}" style="float: left; margin-top: 2px; line-height: 20px; text-decoration: none;">
                                                                        <img src="{{ user_img_url($post->user, 100) }}" style="width: 20px; height: 20px; border-radius: 20px; display: inline-block; background-position: center !important; background-size: cover !important; background-repeat: none !important;">
                                                                    </a>
                                                                </td>
                                                                <td width="5">&nbsp;</td>
                                                                <td valign="top">
                                                                    <a href="{{ url_to_user($post->user) }}" style="text-decoration:none; line-height: 20px;">
                                                                        <span class="author-name" style="font-size: 12px; color: #000; font-style: italic; text-decoration: underline; margin-right: 5px;">{{{ get_full_name_of_user($post->user) }}}</span>
                                                                    </a>
                                                                </td>
                                                                <td valign="top">
                                                                    <span style="font-size: 12px; font-style: italic; text-decoration: underline; color: #6f6f6f; text-decoration: none; line-height: 20px;">{{ trans('labels.posted_on') }}</span>
                                                                    <span class="date" style="font-size: 12px; color: #6f6f6f; text-decoration: none; line-height: 20px;">{{ convert_to_japanese_date(is_null($post->published_at) ? $post->updated_at : $post->published_at) }}</span>
                                                                </td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td height="10">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <p class="detail break-word" style="font-size: 13px; margin: 0 0 10px; color: #333333; line-height: 20px; max-height: 80px; overflow: hidden;">{{{ App\Services\HelperService::getPostDescription($post->getParsedContent(), 200) }}}</p>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                        <td width="10">&nbsp;</td>
                                    </tr>
                                    <!--end article-->
                                    <!--margin-->
                                    <tr>
                                        <td height="20">&nbsp;</td>
                                        <td colspan="3" height="20">
                                            <hr style="display: block; height: 1px; border: 0; border-top: 1px solid #e4e4e4; margin: 1em 0; padding: 0;">
                                        </td>
                                        <td height="20">&nbsp;</td>
                                    </tr>
                                    <!--end margin-->
                                @endforeach
                                <!--button-->
                                <tr align="left">
                                    <td width="10">&nbsp;</td>
                                    <td colspan="3">
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                            <tbody>
                                            <tr>
                                                <td>
                                                    <a href="{{ URL::to('posts/create') }}" style="font-size: 13px; color: #e1df1a; background: #000; padding: 3px 15px; border: none; font-weight: 300; line-height: 25px; cursor: pointer; text-decoration: none;">
                                                        <img src="{{ asset('img/ico-pen2.png') }}" class="display2" style="width: 10px; height: auto; margin-right: 7px;">{{ trans('magazine.try_write') }}
                                                    </a>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td width="10">&nbsp;</td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <!--margin-->
                    <tr>
                        <td height="30">&nbsp;</td>
                    </tr>
                @endif
                <!--END MOST HELPFUL-->
                </tbody>
            </table>
        </td>
    </tr>
@stop

