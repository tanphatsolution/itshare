@extends('emails.layouts.magazine')

@section('main')
<!--NAME OF EMAIL-->
<tr>
    <td>
        <table style="background: url('{{ asset('img/header-bg-full.jpg') }}') right center no-repeat;" width="100%" border="0" cellspacing="0" cellpadding="0" align="left">
            <tbody>
            <tr>
                <td height="20">
                    <span style="color: #666; font-weight: 300; font-size: 14px;">{{ trans('magazine.theme_of', ['month' => $monthText]) }}: </span><span style=" font-weight: 600; font-size: 17px; color: #000;">{{ $monthSubject->theme_name }}</span>
                    <span style="color: #666666; background: #f4f4f4; padding-right:10px; font-size: 13px; font-weight: 300;"><img src="{{ asset('img/line-gray.png') }}" style="margin: 0px 5px 0px 5px;">01/{{ $month }} - {{ $endDate }}/{{ $month }}</span>
                </td>
            </tr>
            </tbody>
        </table>
    </td>
</tr>
<!--END NAME OF EMAIL-->
<tr>
    <td height="10">&nbsp;</td>
</tr>
<tr>
    <td>
        <table bgcolor="#fff" width="100%" border="0" cellspacing="0" cellpadding="0" align="left" style="border: 1px solid #e6e6e6;">
            <tbody>
            <!--LIST LINK-->
            <tr>
                <td>
                    <table width="100%" bgcolor="#fff" width="100%" border="0" cellspacing="0" cellpadding="0" align="left">
                        <tbody>
                        <tr align="left">
                            <td width="10">&nbsp;</td>
                            <td colspan="3">&nbsp;</td>
                            <td width="10">&nbsp;</td>
                        </tr>
                        <!--margin-->
                        <tr>
                            <td height="10">&nbsp;</td>
                        </tr>
                        <!--img-->
                        <tr>
                            <td>&nbsp;</td>
                            <td colspan="3"align="center">
                                <a href="{{ url_to_themes($short_name) }}" style="text-decoration: none;">
                                    {{ HTML::image($monthSubject->img, $monthSubject->theme_name, ['width' => '100%', 'height' => 'auto', 'max-height' => '300px']) }}
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
                                @if (isset($monthSubject) && $monthSubject->themes != null)
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" align="left">
                                        <tbody>
                                            @foreach ($monthSubject->themes as $theme)
                                                <!--Link-->
                                                <tr>
                                                    <td width="450">
                                                        <a class="name-sub-theme" href="{{ route('getSubThemeTab', [$monthSubject->short_name, $theme->short_name]) }}" style="color: #000000; font-size: 14px; text-decoration: none; display: block; width: 100%; line-height: 22px; float: left;">
                                                            {{ $theme->themeLanguages()->first()->name }}
                                                        </a>
                                                    </td>
                                                    <td align="left">
                                                        <a href="{{ route('getPostCreateTheme', [$theme->id]) }}" style="font-size: 13px; color: #e1df1a; background: #000; padding: 3px 15px; border: none; font-weight: 300; float: right; margin-top: 0px; line-height: 18px; display: block; cursor: pointer;text-decoration: none;">
                                                            <img src="{{ asset('img/ico-pen2.png') }}" style="width: 10px; height: auto; margin-right: 7px; display: inline-block;">{{ trans('magazine.write') }}
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td height="15">&nbsp;</td>
                                                </tr>
                                                <!-- End Link-->
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </td>
                            <td>&nbsp;</td>
                        </tr>
                        <!--margin-->
                        <tr>
                            <td height="20">&nbsp;</td>
                            <td colspan="3" >
                                <hr style="display: block; height: 1px; border: 0; border-top: 1px solid #e4e4e4; margin: 1em 0; padding: 0;">
                            </td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td height="5">&nbsp;</td>
                        </tr>
                        <!--button-->
                        <tr>
                            <td>&nbsp;</td>
                            <td colspan="3" align="center">
                                <a href="{{ URL::to('posts/create') }}" style="font-size: 16px; font-weight: 600; color: #000; background: #e1dd00; border-radius: 2px; text-align: center; border: none; padding: 10px 30px;margin: 0 auto; display: block; margin-bottom: 10px; display:inline-block; cursor: pointer; text-decoration: none;">
                                    <img src="{{ asset('img/ico-pen.png') }}" class="display1" style="margin-right: 10px;">
                                    {{ trans('magazine.share_knowledge', ['theme' => $monthSubject->theme_name]) }}
                                </a>
                            </td>
                            <td>&nbsp;</td>
                        </tr>
                        <!--end button-->
                        <tr>
                            <td height="5">&nbsp;</td>
                        </tr>
                        <!--text-->
                        <tr>
                            <td>&nbsp;</td>
                            <td colspan="3" align="center">
                                <span style="font-size: 14px; color: #000; line-height: 20px; display:inline-block;">{{ trans('magazine.refer_article') }}</span>
                            </td>
                            <td>&nbsp;</td>
                        </tr>
                        <!--end text-->
                        <tr>
                            <td height="5">&nbsp;</td>
                        </tr>
                        @if (isset($professionals) && count($professionals) > 0)
                            <!--margin-->
                            <tr>
                                <td height="20">&nbsp;</td>
                                <td colspan="3" >
                                    <hr style="display: block; height: 1px; border: 0; border-top: 1px solid #e4e4e4; margin: 1em 0; padding: 0;">
                                </td>
                                <td>&nbsp;</td>
                            </tr>
                            <!--end margin-->
                        @endif
                        </tbody>
                    </table>
                </td>
            </tr>
            <!--END LIST LINK-->
            @if (isset($professionals) && count($professionals) > 0)
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
                                                @foreach($professionals as $professional)
                                                    <!--article-->
                                                    <tr>
                                                        <td width="259" valign="top">
                                                            <a class="thumbnail" href="{{ url_to_post($professional->post) }}" style="float: left; margin-right: 25px;">
                                                                <img src="{{ empty($professional->professional_img) ? App\Services\HelperService::getPostThumbnail($professional->post) : '/' . $professional->professional_img }}" style="width: 259px; max-height: 160px;">
                                                            </a>
                                                        </td>
                                                        <td valign="top">
                                                            <table width="100%" border="0" cellspacing="0" cellpadding="0" align="left">
                                                                <tbody>
                                                                <tr>
                                                                    <td>
                                                                        <a class="pro-title" href="{{ url_to_post($professional->post) }}" style="font-size: 16px; font-weight: 300; color: #000; line-height: 24px; text-decoration: none;">{{{ isset($professional->post->title) ? $professional->post->title : "" }}}</a>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td height="10">&nbsp;</td>
                                                                </tr>
                                                                <tr>
                                                                    <td height="20">
                                                                        <a href="{{ route('getSubThemeTab', [$monthSubject->short_name, $monthSubject->short_name]) }}" style="font-size: 13px; font-weight: 400; background: #e1dd00; color: #000; padding: 3px 10px 3px 10px; text-decoration: none; line-height: 19px;">{{ $monthSubject->theme_name }}</a>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td height="25">
                                                                        <a href="{{ isset($professional->post->user) ? url_to_user($professional->post->user) : '#' }}" style="font-size: 12px; text-transform: uppercase; background: #000; padding: 5px 11px; color: #fff; text-decoration: none; line-height: 19px;">{{ (is_null($professional->post->user->profile->first_name) && is_null($professional->post->user->profile->last_name)) || (empty($professional->post->user->profile->first_name) && empty($professional->post->user->profile->last_name)) ? $professional->post->user->name : $professional->post->user->profile->first_name . ' ' . $professional->post->user->profile->last_name }}</a>
                                                                    </td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    <!--end article-->
                                                    <!--line gray-->
                                                    <tr>
                                                        <td colspan="2">
                                                            <hr style="display: block; height: 1px; border: 0; border-top: 1px solid #e4e4e4; margin: 1em 0; padding: 0;">
                                                        </td>
                                                    </tr>
                                                    <!--end line gray-->
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </td>
                                    <td width="10">&nbsp;</td>
                                </tr>
                                <!--end header title-->
                                <!--margin-->
                                <tr>
                                    <td height="10">&nbsp;</td>
                                </tr>
                                <!--end margin-->
                                @if ($short_name)
                                    <!--button-->
                                    <tr align="left">
                                        <td width="10">&nbsp;</td>
                                        <td colspan="3">
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <tbody>
                                                <tr>
                                                    <td>
                                                        <a href="{{ url_to_themes($short_name) }}" style="font-size: 13px; color: #e1df1a; background: #000; padding: 3px 15px 5px 15px; border: none; font-weight: 300; line-height: 25px; cursor: pointer; text-decoration: none;">{{ trans('magazine.read_more') }}</a>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                        <td width="10">&nbsp;</td>
                                    </tr>
                                    <!--/ button-->
                                @endif
                            </tbody>
                        </table>
                    </td>
                </tr>
                <!--margin-->
                <tr>
                    <td height="30">&nbsp;</td>
                </tr>
            @endif
            </tbody>
        </table>
    </td>
</tr>
@stop

