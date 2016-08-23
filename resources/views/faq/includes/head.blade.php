
{{ HTML::style('css/bootstrap.css') }}
{{ HTML::style('css/bootstrap-theme.css') }}
{{ HTML::style('css/font-awesome.css') }}
{{ HTML::style('css/bootstrap-social.css') }}
{{ HTML::style('css/jquery-ui.css') }}
{{ HTML::style('css/animate.css') }}
{{ HTML::style('css/sweet-alert.css') }}

{{ HTML::style('css/faq/home/common.css') }}
{{ HTML::style('css/faq/home/common-v2.css') }}
{{ HTML::style('css/faq/home/responsive.css') }}
{{ HTML::style('css/faq/home/header-res.css') }}
{{ HTML::style('css/faq/home/custom.css') }}

{{ HTML::style('css/faq/home/faq-page.css') }}
{{ HTML::style('css/faq/home/see-more.css') }}
{{ HTML::style('css/faq/home/group.css') }}
{{ HTML::style('css/faq/home/group-detail.css') }}

{{ HTML::style('css/faq/user-profile.css') }}
{{ HTML::style('css/faq/user-ranking.css') }}
{{ HTML::style('css/faq/user.css') }}

{{ HTML::style('//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,300,600,700&subset=latin,vietnamese') }}

{{ HTML::script('js/jquery-1.11.1.min.js') }}
{{ HTML::script('https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js') }}

<script>
    $(document).ready(function () {
        $(".box-search").click(function () {
            $(".serch-show-hide").toggle();
        });

        $(".nav-tabs-dropdown-lv1").click(function () {
            var X = $(this).attr('id');

            if (X == 1) {
                $(".nav-tabs-dropdown-lv2").hide();
                $(this).attr('id', '0');
            } else {
                $(".nav-tabs-dropdown-lv2").show();
                $(this).attr('id', '1');
            }
        });

        //Mouseup textarea false
        $(".nav-tabs-dropdown-lv2").mouseup(function () {
            return false
        });

        $(".nav-tabs-dropdown-lv1").mouseup(function () {
            return false
        });

        //Textarea without editing.
        $(document).mouseup(function () {
            $(".nav-tabs-dropdown-lv2").hide();
            $(".nav-tabs-dropdown-lv1").attr('id', '');
        });
    });
</script>