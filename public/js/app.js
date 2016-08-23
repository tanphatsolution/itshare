'use strict';

// var supportedLanguages = [
//     'Actionscript',
//     'Apache_conf',
//     'Batchfile',
//     'C_Cpp',
//     'Clojure',
//     'Coffee',
//     'ColdFusion',
//     'Cobol',
//     'Csharp',
//     'CSS',
//     'Django',
//     'Ejs',
//     'Gitignore',
//     'Golang',
//     'Groovy',
//     'haXe',
//     'HAML',
//     'Haskell',
//     'Haxe',
//     'HTML',
//     'HTML_Ruby',
//     'Jade',
//     'Java',
//     'JavaScript',
//     'JSON',
//     'LaTeX',
//     'LESS',
//     'Liquid',
//     'LIPS',
//     'Lua',
//     'Makefile',
//     'Markdown',
//     'Mysql',
//     'Nix',
//     'ObjectiveC',
//     'OCaml',
//     'Pascal',
//     'Perl',
//     'pgSQL',
//     'PHP',
//     'Powershell',
//     'Prolog',
//     'Python',
//     'Rdoc',
//     'Ruby',
//     'OpenSCAD',
//     'SASS',
//     'Scala',
//     'SCSS',
//     'SH',
//     'Smarty',
//     'SQL',
//     'SVG',
//     'Swift',
//     'Tex',
//     'Text',
//     'Textile',
//     'Twig',
//     'XML',
//     'XQuery',
//     'YAML',
// ];

var emoticons = [
    {"key": "(baovtv)", "regex": "\\(baovtv\\)", "src": "http://i.imgur.com/c0ZemnT.jpg"},
    {"key": "(hi)", "regex": "\\(hi\\)", "src": "http://i.imgur.com/hdXJpIT.gif"},
    {"key": "(songphi)", "regex": "\\(songphi\\)", "src": "http://i.imgur.com/K1KOLAk.png"},
    {"key": "(hucau2)", "regex": "\\(hucau2\\)", "src": "http://i.imgur.com/z64MSb8.png"},
    {"key": "(iknow)", "regex": "\\(iknow\\)", "src": "http://i.imgur.com/pwVttrF.gif"},
    {"key": "(fly)", "regex": "\\(fly\\)", "src": "http://i.imgur.com/RieSoKb.png"},
    {"key": "(good)", "regex": "\\(good\\)", "src": "http://i.imgur.com/riA5Lgx.png"},
    {"key": "(dam2)", "regex": "\\(dam2\\)", "src": "http://i.imgur.com/VJrpk1C.png"},
    {"key": "(tat2)", "regex": "\\(tat2\\)", "src": "http://i.imgur.com/6hpiqf8.jpg"},
    {"key": "(hucau)", "regex": "\\(hucau\\)", "src": "http://i.imgur.com/GaTVf1E.png"},
    {"key": "(dam)", "regex": "\\(dam\\)", "src": "http://i.imgur.com/VVpKcVZ.png"},
    {"key": "(songphi2)", "regex": "\\(songphi2\\)", "src": "http://i.imgur.com/ytr1CvM.png"},
    {"key": "(chem)", "regex": "\\(chem\\)", "src": "http://i.imgur.com/zMHB7fQ.png"},
    {"key": "(lgtm3)", "regex": "\\(lgtm3\\)", "src": "http://i.imgur.com/kXkmJyX.gif"},
    {"key": "(lgtm2)", "regex": "\\(lgtm2\\)", "src": "http://i.imgur.com/WDo3dBM.gif"},
    {"key": "(lgtm)", "regex": "\\(lgtm\\)", "src": "http://i.imgur.com/jsFLAVe.gif"},
    {"key": "(pr)", "regex": "\\(pr\\)", "src": "http://i.imgur.com/O8qg4Gk.png"},
    {"key": "(commented)", "regex": "\\(commented\\)", "src": "http://i.imgur.com/kd8PWAI.png"},
    {"key": "(camap)", "regex": "\\(camap\\)", "src": "http://i.imgur.com/FXT65Dy.png"},
    {"key": "(yes)", "regex": "\\(yes\\)", "src": "http://i.imgur.com/3qpymnh.gif"},
    {"key": "(lengoi)", "regex": "\\(lengoi\\)", "src": "http://i.imgur.com/2XxBboy.png"},
    {"key": "(va)", "regex": "\\(va\\)", "src": "http://www.sherv.net/cm/emoticons/fighting/slapping.gif"},
    {"key": "(thanks)", "regex": "\\(thanks\\)", "src": "thanks.png"},
    {"key": "(dungcaidau2)", "regex": "\\(dungcaidau2\\)", "src": "dungcaidau2.png"},
    {"key": "(goodjob)", "regex": "\\(goodjob\\)", "src": "gj.png"},
    {"key": "(seeyou)", "regex": "\\(seeyou\\)", "src": "seeyou.png"},
    {"key": "(really?)", "regex": "\\(really\\?\\)", "src": "really.png"},
    {"key": "(please)", "regex": "\\(please\\)", "src": "please.png"},
    {"key": "(youcandoit)", "regex": "\\(youcandoit\\)", "src": "youcandoit.png"},
    {"key": "(go)", "regex": "\\(go\\)", "src": "jp/go.png"},
    {"key": "(thankyou)", "regex": "\\(thankyou\\)", "src": "thankyou.png"},
    {"key": "(ng)", "regex": "\\(ng\\)", "src": "ng.png"},
    {"key": "(huytsao)", "regex": "\\(huytsao\\)", "src": "huytsao.png"},
    {"key": "(?)", "regex": "\\(\\?\\)", "src": "khonghieu.png"},
    {"key": "(yeah)", "regex": "\\(yeah\\)", "src": "yeah.png"},
    {"key": "(gach3)", "regex": "\\(gach3\\)", "src": "http://i.imgur.com/tbSfBAA.gif"},
    {"key": "(tat3)", "regex": "\\(tat3\\)", "src": "http://i.imgur.com/UYpeWib.jpg"},
    {"key": "(ban)", "regex": "\\(ban\\)", "src": "http://i.imgur.com/07u4TWg.gif"},
    {"key": "(ban2)", "regex": "\\(ban2\\)", "src": "http://i.imgur.com/LzxMHJx.gif"},
    {"key": "(buonngu)", "regex": "\\(buonngu\\)", "src": "http://i.imgur.com/QmVpcLe.gif"},
    {"key": "(caigithe)", "regex": "\\(caigithe\\)", "src": "http://i.imgur.com/9unr7Yj.gif"},
    {"key": "(cat)", "regex": "\\(cat\\)", "src": "http://i.imgur.com/cJezFyu.gif"},
    {"key": "(dam3)", "regex": "\\(dam3\\)", "src": "http://i.imgur.com/QgNEyfN.gif"},
    {"key": "(dance2)", "regex": "\\(dance2\\)", "src": "http://i.imgur.com/5Vs00YT.gif"},
    {"key": "(dance3)", "regex": "\\(dance3\\)", "src": "http://i.imgur.com/LJpnYLP.gif"},
    {"key": "(hehe)", "regex": "\\(hehe\\)", "src": "http://i.imgur.com/ZiH4T5w.gif"},
    {"key": "(khoc2)", "regex": "\\(khoc2\\)", "src": "http://i.imgur.com/Eusq4uQ.gif"},
    {"key": "(khoc3)", "regex": "\\(khoc3\\)", "src": "http://i.imgur.com/R5UWsOE.gif"},
    {"key": "(hura)", "regex": "\\(hura\\)", "src": "http://i.imgur.com/zTn4sZA.gif"},
    {"key": "(khongchiudau2)", "regex": "\\(khongchiudau2\\)", "src": "http://i.imgur.com/1cuJEj9.gif"},
    {"key": "(lay2)", "regex": "\\(lay2\\)", "src": "http://i.imgur.com/X2duk2g.gif"},
    {"key": "(nguong)", "regex": "\\(nguong\\)", "src": "http://i.imgur.com/iEVcWcV.gif"},
    {"key": "(code)", "regex": "\\(code\\)", "src": "http://i.imgur.com/nVQMZzM.gif"},
    {"key": "(ok)", "regex": "\\(ok\\)", "src": "http://i.imgur.com/RtNJ689.png"},
    {"key": "(2tat)", "regex": "\\(2tat\\)", "src": "http://i.imgur.com/IsqGGoW.jpg"},
    {"key": "(hoho)", "regex": "\\(hoho\\)", "src": "http://i.imgur.com/TMoSZt9.gif"},
    {"key": "(haha)", "regex": "\\(haha\\)", "src": "http://i.imgur.com/DsnFira.gif"},
    {"key": "(khoc)", "regex": "\\(khoc\\)", "src": "http://i.imgur.com/yZmw63B.gif"},
    {"key": "(chao)", "regex": "\\(chao\\)", "src": "http://i.imgur.com/qEkBgjJ.gif"},
    {"key": "(sohai)", "regex": "\\(sohai\\)", "src": "http://i.imgur.com/2ns4Hf6.gif"},
    {"key": "(merged)", "regex": "\\(merged\\)", "src": "http://i.imgur.com/xhcqg3y.png"},
    {"key": "(da)", "regex": "\\(da\\)", "src": "http://i.imgur.com/jhATQjR.png"},
    {"key": "(banchuong)", "regex": "\\(banchuong\\)", "src": "http://i.imgur.com/hKkYmcH.jpg"},
    {"key": "(lay)", "regex": "\\(lay\\)", "src": "http://i.imgur.com/ifpKdPD.jpg"},
    {"key": "(kidding?)", "regex": "\\(kidding\\?\\)", "src": "areyoukiddingme.jpeg"},
    {"key": "(dap)", "regex": "\\(dap\\)", "src": "dap.gif"},
    {"key": "(tanghoa)", "regex": "\\(tanghoa\\)", "src": "tanghoa.png"},
    {"key": "(tat)", "regex": "\\(tat\\)", "src": "http://i.imgur.com/Wr5L3WT.jpg"},
    {"key": "(gach2)", "regex": "\\(gach2\\)", "src": "gach2.png"},
    {"key": "(gach)", "regex": "\\(gach\\)", "src": "gach.png"},
    {"key": "(baiphuc)", "regex": "\\(baiphuc\\)", "src": "baiphuc.jpg"},
    {"key": "(rip)", "regex": "\\(rip\\)", "src": "rip.gif"},
    {"key": "(love)", "regex": "\\(love\\)", "src": "love.jpg"},
    {"key": "(orz)", "regex": "\\(orz\\)", "src": "orz.jpg"},
    {"key": "(orz2)", "regex": "\\(orz2\\)", "src": "orz2.jpg"},
    {"key": "(yaoming)", "regex": "\\(yaoming\\)", "src": "yaoming.png"},
    {"key": "(dungcaidau)", "regex": "\\(dungcaidau\\)", "src": "dungcaidau.jpeg"},
    {"key": "(voteban)", "regex": "\\(voteban\\)", "src": "voteban.png"},
    {"key": "(otsu)", "regex": "\\(otsu\\)", "src": "otsu.png"},
    {"key": "(+1)", "regex": "\\(\\+1\\)", "src": "plusone.png"},
    {"key": "(-1)", "regex": "\\(\\-1\\)", "src": "minusone.png"},
    {"key": "(like)", "regex": "\\(like\\)", "src": "like.png"},
    {"key": "(dislike)", "regex": "\\(dislike\\)", "src": "dislike.png"},
    {"key": "(framgia)", "regex": "\\(framgia\\)", "src": "framgia.png"},
    {"key": "(facepalm)", "regex": "\\(facepalm\\)", "src": "facepalm.gif"},
    {"key": "(facepalm2)", "regex": "\\(facepalm2\\)", "src": "facepalm2.gif"},
    {"key": "(godzillafacepalm)", "regex": "\\(godzillafacepalm\\)", "src": "godzilla_facepalm.png"},
    {"key": "(honho)", "regex": "\\(honho\\)", "src": "honho.gif"},
    {"key": "(dull)", "regex": "\\(dull\\)", "src": "dull.gif"},
    {"key": "(mooning)", "regex": "\\(mooning\\)", "src": "mooning.gif"},
    {"key": "(hehehe)", "regex": "\\(hehehe\\)",    "src": "hehehe.gif"},
    {"key": "(silly)", "regex": "\\(silly\\)", "src": "silly.gif"},
    {"key": "(rofl)", "regex": "\\(rofl\\)",    "src": "rofl.gif"},
    {"key": "(sleepy)", "regex": "\\(sleepy\\)",    "src": "sleepy.gif"},
    {"key": "(venhvao)", "regex": "\\(venhvao\\)",  "src": "venhvao.gif"},
    {"key": "(ifyouknowwhatimean)", "regex": "\\(ifyouknowwhatimean\\)",    "src": "ifyouknowwhatimean.png"},
    {"key": "(wtf)", "regex": "\\(wtf\\)",  "src": "wtf.gif"},
    {"key": "(giggle)", "regex": "\\(giggle\\)",    "src": "giggle.gif"},
    {"key": "(chuckle)", "regex": "\\(chuckle\\)",  "src": "giggle.gif"},
    {"key": "(shit)", "regex": "\\(shit\\)",    "src": "shit.gif"},
    {"key": "(are)", "regex": "\\(are\\)",  "src": "e.jpg"},
    {"key": "(problem?)", "regex": "\\(problem\\?\\)",  "src": "problem.jpg"},
    {"key": "(thecoa)", "regex": "\\(thecoa\\)",    "src": "thecoa.jpg"},
    {"key": "(len)", "regex": "\\(len\\)", "src": "http://www.clubtuzki.com/emoticons/tuzki_038.gif"},
    {"key": "(hpbd)", "regex": "\\(hpbd\\)", "src": "http://www.clubtuzki.com/emoticons/tuzki_034.gif"},
    {"key": "(loncaiban)", "regex": "\\(loncaiban\\)", "src": "http://www.clubtuzki.com/emoticons/tuzki_047.gif"},
    {"key": "(huhuhu)", "regex": "\\(huhuhu\\)", "src": "http://www.clubtuzki.com/emoticons/tuzki_002.gif"},
    {"key": "(sleep)", "regex": "\\(sleep\\)", "src": "http://www.clubtuzki.com/emoticons/tuzki_027.gif"},
    {"key": "(idontknow)", "regex": "\\(idontknow\\)", "src": "http://www.clubtuzki.com/emoticons/tuzki_014.gif"},
    {"key": "(quaylen)", "regex": "\\(quaylen\\)", "src": "http://www.clubtuzki.com/emoticons/tuzki_025.gif"},
    {"key": "(haiz)", "regex": "\\(haiz\\)", "src": "http://www.clubtuzki.com/emoticons/tuzki_020.gif"},
    {"key": "(ohno)", "regex": "\\(ohno\\)", "src": "http://www.clubtuzki.com/emoticons/tuzki_011.gif"},
    {"key": "(hihi)", "regex": "\\(hihi\\)", "src": "http://www.clubtuzki.com/emoticons/tuzki_046.gif"},
    {"key": "(cogihot)", "regex": "\\(cogihot\\)", "src": "http://www.clubtuzki.com/emoticons/tuzki_045.gif"},
    {"key": "(khongchiudau)", "regex": "\\(khongchiudau\\)", "src": "http://www.clubtuzki.com/emoticons/tuzki_019.gif"},
    {"key": "(vaylasao)", "regex": "\\(vaylasao\\)", "src": "http://www.clubtuzki.com/emoticons/tuzki_031.gif"},
    {"key": "(chopmat)", "regex": "\\(chopmat\\)", "src": "https://s.yimg.com/lq/i/mesg/emoticons7/5.gif"},
    {"key": "(cuoideu)", "regex": "\\(cuoideu\\)", "src": "https://s.yimg.com/lq/i/mesg/emoticons7/71.gif"},
    {"key": "(kill)", "regex": "\\(kill\\)", "src": "http://www.clubtuzki.com/emoticons/tuzki_015.gif"},
    {"key": "(pray)", "regex": "\\(pray\\)", "src": "https://s.yimg.com/lq/i/mesg/emoticons7/63.gif"},
    {"key": "(bowbowbow)", "regex": "\\(bowbowbow\\)", "src": "https://s.yimg.com/lq/i/mesg/emoticons7/77.gif"},
    {"key": "(dancing)", "regex": "\\(dancing\\)", "src": "https://s.yimg.com/lq/i/mesg/emoticons7/69.gif"},
    {"key": "=))", "regex": "=\\)\\)", "src": "https://s.yimg.com/lq/i/mesg/emoticons7/24.gif"},
];

var notificationOpening = false;

jQuery(document).ready(function($){
    // browser window scroll (in pixels) after which the "back to top" link is shown
    var offset = 300,
    //browser window scroll (in pixels) after which the "back to top" link opacity is reduced
        offset_opacity = 1200,
    //duration of the top scrolling animation (in ms)
        scroll_top_duration = 700,
    //grab the "back to top" link
        $back_to_top = $('.cd-top');

    //hide or show the "back to top" link
    $(window).scroll(function(){
        ( $(this).scrollTop() > offset ) ? $back_to_top.addClass('cd-is-visible') : $back_to_top.removeClass('cd-is-visible cd-fade-out');
        if( $(this).scrollTop() > offset_opacity ) {
            $back_to_top.addClass('cd-fade-out');
        }

        if ($(this).scrollTop() > 100 && $('nav').hasClass('on-scroll-fixed')){
            $('nav').addClass("navbar-scroll");
        }
        else{
            $('nav').removeClass("navbar-scroll");
        }
    });

    //smooth scroll to top
    $back_to_top.on('click', function(event){
        event.preventDefault();
        $('body,html').animate({
                scrollTop: 0 ,
            }, scroll_top_duration
        );
    });

    $('#back-to-top').on('click', function(e) {
        e.preventDefault();

        $('body,html').animate({
                scrollTop: 0 ,
            }, scroll_top_duration
        );
    });

    $("#header-search-form img.search-icon").on("click", function(e) {
        if($("#search-box").hasClass("search-icon-submitable")) {
            $(this).closest("form").submit();
        } else {
            $("#search-box").addClass("search-icon-submitable");
        }

        e.stopPropagation();
    });

    $(document).on("click", function() {
        if (!$("#search-box").is(":focus")) {
            $("#search-box").removeClass("search-icon-submitable");
        }
    });
});

$(function(){

    // Tell jQuery to pass _token along whenever an AJAX call is made
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('meta[name="_token"]').attr('content')
        }
    });

    if ($('#search-box').length) {
        $("#search-box").autocomplete({
            minLength: 1,
            source: function( request, response ) {
                var term = request.term;
                var searchUrl = baseURL + '/quick-search';
                $.ajax({
                    url: searchUrl,
                    dataType: 'jsonp',
                    data: {
                        keyword: request.term
                    },
                    success: function(data) {
                        if (typeof data.data != 'undefined' && data.data.length > 0) {
                            response($.map(data.data, function(item) {
                                return item;
                            }));
                        }
                    }
                });
            },
            focus: function( event, ui) {
                return false;
            },
            select: function(event, ui) {
                if (ui.item.href != null) {
                    location.assign(ui.item.href);
                }
                if (ui.item.type == quickSearchType.footer) {
                    $('.box-search #header-search-form').submit();
                }
                return false;
            },
            open: function(){
                $('.ui-autocomplete').css('max-width', $('#search-box').outerWidth());
            }
        }).data('ui-autocomplete')._renderItemData = function(ul, item) {
            return quickSearchTemplate(ul, item);
        };
    }

    $('[data-toggle="tooltip"]').tooltip();

    $('#notification-badge').popover({
        html: true,
        content: function () {
            var popContent = $(this).attr('data-pop');
            return $('#'+popContent).html();
        }
    });

    dismissNotification();

    $('#notification-badge').hover(function() {
        fetchNotification();
    });

    $('.mobile-noti').on('click', function() {
        fetchNotification();
    });

    $('#viblo-login').on('click', function() {
        $('#m2').attr('aria-hidden', 'true');
    });

    $("#login-form-header").submit(function(event) {
        event.preventDefault();
        var $form = $ (this),
        data = $form.serialize(),
        url = $form.attr( 'action' );
        $.ajax({
            type: 'POST',
            url : url,
            data : data,
            dataType : 'json',
            success: function(result) {
                if (result.success == true) {
                    window.location.assign(result.url);
                } else {
                    window.location.assign(baseURL + '/login');
                }
            }
        });
    });

    if (typeof(category) != "undefined" && category !== null) {
        $("." + category).addClass('selected');
    } else if (typeof(wall) != "undefined" && wall !== null) {
        $("." + wall).addClass('selected');
    }

    $('body').on('click', '.request-accept, .request-deny', function() {
        var element = $(this).parents('.entry-item');
        var requestId = $(this).data('request-id');
        var type = ($(this).attr('class') == 'accept request-accept') ? 'accept' : 'deny';
        element.empty().addClass('notify-loading');
        $('.notify-' + requestId).empty().addClass('notify-loading');

        $.ajax({
            type: 'POST',
            url: baseURL + '/groups/postsUsers/approveFromNotify',
            data: {
                type: type,
                requestId: requestId,
            },
            success: function(result) {
                element.removeClass('notify-loading').empty().append(result.message);
                $('.notify-' + requestId).removeClass('notify-loading').empty().append(result.message);
            },
            complete: function(data) {
            }
        });
    });

    if (typeof(notificationsCount) != 'undefined' && notificationsCount > 0) {
        $('title').html('(' + notificationsCount + ')' + title);
    }

});

function quickSearchTemplate(ul, item) {
    if (item.type == quickSearchType.header) {
        return $('<li class="header"></li>')
            .data('ui-autocomplete-item', item )
            .append('<a href="#" rel="ignore" target=""><span class="text">' + item.text + '</span></a>')
            .appendTo(ul);
    } if (item.type == quickSearchType.footer) {
        return $('<li class="footer"></li>')
            .data('ui-autocomplete-item', item )
            .append('<a onclick="$(\'#header-search-form\').submit()" href="#" rel="ignore" target=""><span class="text">' + item.text + '</span></a>')
            .appendTo(ul);
    } else {
        var insideHtml = '';
        if (item.category != null && item.subtext != null) {
            insideHtml = '<a href="' + item.href + '" rel="ignore" target=""><span class="text">' + item.text + '</span><span class="category">' + item.category + '</span><span class="subtext">' + item.subtext + '</span></a>';
        } else if (item.category != null && item.subtext == null) {
            insideHtml = '<a href="' + item.href + '" rel="ignore" target=""><span class="text">' + item.text + '</span><span class="category">' + item.category + '</span></a>';
        } else if (item.category == null && item.subtext != null) {
            insideHtml = '<a href="' + item.href + '" rel="ignore" target=""><span class="text">' + item.text + '</span><span class="subtext">' + item.subtext + '</span></a>';
        } else {
            insideHtml = '<a href="' + item.href + '" rel="ignore" target=""><span class="text">' + item.text + '</span></a>';
        }
        return $('<li></li>')
            .data('ui-autocomplete-item', item )
            .append(insideHtml)
            .appendTo(ul);

    }
}

function updateNotificationBadge(notificationsCount) {
    var notificationBadge = $('#notification-badge-count');
    if (notificationBadge.length === 0) {
        $('<span>', {
            id: 'notification-badge-count',
            class: 'badge badge-notify',
            html: notificationsCount
        }).appendTo('#notification-badge');
    } else {
        notificationBadge.html(notificationsCount);
    }
    notificationBadge.removeClass('hide');

    var notificationBadgeResponsive = $('#notification-badge-count-res');
    if (notificationBadgeResponsive.length === 0) {
        $('<span>', {
            id: 'notification-badge-count-res',
            class: 'badge badge-notify',
            html: notificationsCount
        }).appendTo('.mobile-noti');
    } else {
        notificationBadgeResponsive.html(notificationsCount);
    }
    notificationBadgeResponsive.removeClass('hide');
}

function fetchNotification() {
    var url = baseURL + '/notifications/fetch';
    var notifyIcon = $('#notification-badge');
    var dataInit = notifyIcon.attr('data-init');
    var notificationBadge = $('#notification-badge-count');
    var notificationBadgeResponsive = $('#notification-badge-count-res');
    var notificationCount = parseInt(notificationBadge.html());
    var data = {init: false};
    if (dataInit == 'true') {
        data.init = true;
        notifyIcon.attr('data-init', false);
    }
    if (dataInit != 'true' && !notificationCount) {
        return;
    }
    notificationLoading(true, data.init, notificationCount);
    $.ajax({
        type: 'POST',
        url: url,
        data: data,
        success: function(result)
        {
            processNotification(result);
        },
        complete: function(data) {
            notificationLoading(false, data.init, notificationCount);
            $('title').html(title);
            notificationBadge.html('0');
            notificationBadge.addClass('hide');
            notificationBadgeResponsive.html('0');
            notificationBadgeResponsive.addClass('hide');
        }
    });
}

function notificationLoading(show, dataInit, notificationCount) {
    var notifyLoading = $('.notify-loading');
    if (show && (dataInit || notificationCount)) {
        return notifyLoading.removeClass('hide');
    }
    return notifyLoading.addClass('hide');
}

function dismissNotification() {
    $('body').on('click', function (e) {
        $('#notification-badge').each(function () {
            if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && notificationOpening) {
                $('#notification-content').addClass('hide');
                notificationOpening = false;
            }
        });
    });
}

function processNotification(result) {
    if (result.notify !== '') {
        $('body #notification-list').prepend(result.notify).delay(100).queue(function(nxt) {
            $('.fader').animate({opacity: 1}, 1);
            $('#notification-list').animate({scrollTop: 0}, 250);
        });
    }

    if (result.request !== '') {
        $('body #requests-list').prepend(result.request).delay(100).queue(function(nxt) {
            $('.fader').animate({opacity: 1}, 1);
            $('#requests-list').animate({scrollTop: 0}, 250);
        });
    }

    if (result.unreadNotifyCount > 0) {
        $('body .notify-count.notifications').empty().append(result.unreadNotifyCount);
    }

    if (result.unreadRequestCount > 0) {
        $('body .notify-count.requests').empty().append(result.unreadRequestCount);
    }
}
