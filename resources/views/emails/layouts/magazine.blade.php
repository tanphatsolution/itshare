<!DOCTYPE html>
<html lang="en" style="background: #f4f4f4;">
<head>
    <title>Viblo</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,300,600,700&subset=latin,vietnamese' rel='stylesheet' type='text/css'>
    <style type="text/css">
        body {
            font-family: 'Open Sans', sans-serif;
            font-size: 14px;
            font-weight: 400;
            color: #000;
        }
    </style>
</head>
<body style="background: #f4f4f4;">
<table width="100%" bgcolor="#f4f4f4" border="0" cellspacing="0" cellpadding="0" height="auto" style="font-family: 'Open Sans', sans-serif; font-size: 14px; font-weight: 400; color: #000;">
    <tbody>
    <tr>
        <td>
            <table width="600" border="0" cellspacing="0" cellpadding="0" style=" margin: 0 auto; min-width: 600px;">
                <tbody>
                <!--BANNER TOP-->
                <tr>
                    <td>
                        <a href="{{ URL::to('/') }}">
                            <img src="{{ asset('img/too-en.jpg') }}" style="width: 100%; height: auto; margin-top: 20px; margin-bottom: 10px;">
                        </a>
                    </td>
                </tr>
                <!--END BANNER TOP-->

                @yield('main')

                <!--CONNECT US-->
                <tr>
                    <td>
                        <table bgcolor="#fff" width="100%" border="0" cellspacing="0" cellpadding="0" style="border: 1px solid #e6e6e6; margin-top: -1px; border-top: 0px;">
                            <tbody>
                            <tr>
                                <td width="10"></td>
                                <td align="right" valign="top" style="padding: 20px 0 20px 0;">
                                    <span style="font-size:14px; font-weight: 300;">{{ trans('magazine.connect') }}</span>
                                    <a href="https://www.facebook.com/viblo.asia/">
                                        <img src="{{ asset('img/ico-fb.png') }}" style="margin-left: 7px; float: right;">
                                    </a>
                                </td>
                                <td width="10"></td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <!--END CONNECT US-->
                <!--margin-->
                <tr>
                    <td height="30"></td>
                </tr>
                <!--FOOTER-->
                <tr>
                    <td>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tbody>
                            <tr>
                                <td align="center">
                                    <a href="{{ URL::to('/') }}"><img src="{{ asset('img/logo_bottom.png') }}" class="email-footer-logo" style="margin-bottom: 10px;"></a>
                                </td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <span style="font-size: 11px; color: #666666; line-height: 18px; font-weight: 300;">{{ trans('magazine.slogan') }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <span style="font-size: 11px; color: #666666; line-height: 18px; font-weight: 300;">{{ trans('magazine.programing_language') }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <a href="{{ URL::to('/settings/notification') }}" style="font-size: 11px; color: #333333; font-style: italic; text-decoration: underline;">{{ trans('magazine.change_unsubscribe') }}</a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <hr style="display: block; height: 1px; border: 0; border-top: 1px solid #e4e4e4; margin: 1em 0; padding: 0;">
                                </td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <a href="http://framgia.co.jp/" style="text-decoration: none;">
                                        <span class="email-copyright" style="color: #999999; font-size: 11px; padding: 20px 0px 10px 0px;">&copy; Framgia Inc.</span>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td height="30"></td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <!--END FOOTER-->
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>
</body>
</html>
