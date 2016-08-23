$(function() {
    $('#start').datepicker({
        changeMonth: true,
        changeYear: true
    });

    $('#score_end').datepicker({
        changeMonth: true,
        changeYear: true
    });
    
    $('body').on('change','#start',function(){
        $('#end').datepicker({
            changeMonth: true,
            changeYear: true,
            minDate: document.getElementById('start').value
        });
        $('#end')[0].disabled = false;
    });

    $('#end').datepicker({
        changeMonth: true,
        changeYear: true,
        minDate: null
    });   

    $('body').on('click', '.btn-articles', function(event){
        var userId = event.target.dataset.user_id;
        var contestId = event.target.dataset.contest_id;
        var username = event.target.dataset.username;

        var urlPrefix = window.location.protocol;
        urlPrefix += "/" + username + "/posts/";

        $('.modal-title-user').html(username);

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "GET",
            url: baseURL + "/contests/" + contestId + "/articles/" + userId,
            data: {},
            success: function(data) {
                $('.modal-body').html(null); // Erase content

                var newContent = "<p>";
                if(data.length > 0) {
                    $.each(data, function(key, value) {
                        newContent += "<b><a href='" + urlPrefix + value.encrypted_id + "'>" + value.title + "</a></b><br>";
                    });
                    $('.modal-body').html(newContent += "</p>");
                } else {
                    $('.modal-body').html("<p>Could not find the articles.</p>");
                }
            }
        });

    });
});

function initCategoryInput() {
    $("#category-input").tagsinput({
        tagClass: "label label-primary",
        trimValue: !0,
        typeahead: {
            source: categories
        }
    }), $(".bootstrap-tagsinput").addClass("col-xs-12 mgbt-0"), $(".topCategories").click(function() {
        var a = $(this).data("category");
        $("#category-input").tagsinput("add", a);
    });
}

function initEmailInput() {
    $("#email-input").tagsinput({
        tagClass: "label label-primary",
        trimValue: !0,
        typeahead: {
            source: emails
        }
    }), $(".bootstrap-tagsinput").addClass("col-xs-12 mgbt-0"), $(".topCategories").click(function() {
        var a = $(this).data("email");
        $("#email-input").tagsinput("add", a);
    });
}

function previewNavigation() {
    return postBodyWrapper.hasClass(previewStages.not) ? ($("i." + navStages.full).show(),
    $("i." + navStages.halfl).show(), $("i." + navStages.halfr).hide(), void $("i." + navStages.close).hide()) : postBodyWrapper.hasClass(previewStages.half) ? ($("i." + navStages.full).hide(),
    $("i." + navStages.halfl).show(), $("i." + navStages.halfr).show(), void $("i." + navStages.close).hide()) : postBodyWrapper.hasClass(previewStages.full) ? ($("i." + navStages.full).hide(),
    $("i." + navStages.halfl).hide(), $("i." + navStages.halfr).show(), void $("i." + navStages.close).show()) : void 0;
}

function initLinkTarget() {
    var a = $(".markdownContent").find("a");
    a.each(function() {
        $(this).attr("target", "_blank");
    });
}

function initTocTree() {
    var a = $(".markdownContent").find("h1, h2, h3");
    a.each(function(a) {
        var b = $(this), c = stringSanitizer(b.text()) + "-" + a;
        b.attr("id", c);
        var d = '<li><a class="' + b.prop("tagName") + '" href="#' + c + '">' + b.text() + "</a></li>";
        $(d).appendTo("ul#menuTocTree");
    });
}

function stringSanitizer(a) {
    return a = a.toLowerCase(), a = a.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a"),
    a = a.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e"), a = a.replace(/ì|í|ị|ỉ|ĩ/g, "i"),
    a = a.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o"), a = a.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u"),
    a = a.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y"), a = a.replace(/đ/g, "d"), a = a.replace(/!|@|%|\^|\*|\(|\)|\+|\=|\<|\>|\?|\/|,|\.|\:|\;|\'| |\"|\&|\#|\[|\]|~|$|_/g, "-"),
    a = a.replace(/-+-/g, "-"), a = a.replace(/^\-+|\-+$/g, "");
}

function stock() {
    $(".post-list").on("click", ".icon-stock", function() {
        var a = $(this), b = $(this).attr("post_id");
        $(this).hasClass("activated") ? (a.attr("disabled", "disabled"), $.ajax({
            type: "DELETE",
            url: baseURL + "/clip/" + b,
            success: function() {
                a.removeClass("activated"), a.removeAttr("disabled");
            }
        })) : (a.attr("disabled", "disabled"), $.ajax({
            type: "POST",
            url: baseURL + "/clip",
            data: {
                postId: b
            },
            success: function() {
                a.addClass("activated"), a.removeAttr("disabled");
            }
        }));
    });
}

function loadMoreUserStock() {
    $(document).on("click", "#seeMoreUserStock", function() {
        var a = $(this), b = a.data("post");
        message = a.data("message"), start = a.attr("data-start"), increase = a.data("increase"),
        a.attr("disabled", "disabled").html(message), $.ajax({
            type: "GET",
            url: baseURL + "/posts/listclip",
            data: {
                start: start,
                postId: b
            },
            success: function(b) {
                a.removeAttr("disabled").html(b.message), newStart = parseInt(start) + increase,
                a.attr("data-start", newStart), b.end ? a.removeAttr("id").fadeOut() : ($("#userStockList").append(b.html),
                $.getScript(baseURL + "/js/relationships.js"));
            }
        });
    });
}

function getMonthlyThemes(a) {
    var b = $("#monthly-theme-subject-id").val();
    $.ajax({
        type: "GET",
        url: baseURL + "/monthlythemesubjects/getListMonthlyThemes",
        data: {
            monthlyThemeSubjectId: b,
            themeId: a
        },
        success: function(a) {
            $(".themes-in-month").empty().html(a.html);
        },
        error: function() {
            swal({
                title: errorLabel,
                text: errorMsg,
                type: "error"
            });
        }
    });
}

function showLabelLang() {
    $("#screen_preview pre code").each(function() {
        var a = $(this).attr("class"), b = "Default";
        void 0 != a && (b = a.slice(9, a.length)), $(this).prepend('<span class="label label-default">' + b + "</span><br/>");
    });
}

$(function() {
    $("#group-post-privacy").prop("disabled", !0), "undefined" != typeof isAuthor && 0 == isAuthor ? $("#group-post-privacy").prop("disabled", !0) : "undefined" != typeof groupPrivacyProtected && groupPrivacyProtected && $("#group-post-privacy").prop("disabled", !1),
    $("#group-id").on("change", function() {
        var a = $("#group-id").val();
    }), "undefined" != typeof triggerChange && 1 == triggerChange && $("#group-id").trigger("change");
}), !function(a, b) {
    function c(b) {
        var c = p(), d = c.querySelector("h2"), e = c.querySelector("p"), f = c.querySelector("button.cancel"), g = c.querySelector("button.confirm");
        if (d.innerHTML = u(b.title).split("\n").join("<br>"), e.innerHTML = u(b.text || "").split("\n").join("<br>"),
        b.text && w(e), y(c.querySelectorAll(".icon")), b.type) {
            for (var h = !1, i = 0; i < n.length; i++) if (b.type === n[i]) {
                h = !0;
                break;
            }
            var j = c.querySelector(".icon." + b.type), k = document.getElementsByClassName("sweet-alert");

        }
        if (b.imageUrl) {
            var l = c.querySelector(".icon.custom");
            l.style.backgroundImage = "url(" + b.imageUrl + ")", w(l);
            var m = 80, o = 80;
            if (b.imageSize) {
                var q = b.imageSize.split("x")[0], r = b.imageSize.split("x")[1];
                q && r ? (m = q, o = r, l.css({
                    width: q + "px",
                    height: r + "px"
                })) : a.console.error("Parameter imageSize expects value with format WIDTHxHEIGHT, got " + b.imageSize);
            }
            l.setAttribute("style", l.getAttribute("style") + "width:" + m + "px; height:" + o + "px");
        }
        c.setAttribute("data-has-cancel-button", b.showCancelButton), b.showCancelButton ? f.style.display = "inline-block" : y(f),
        b.cancelButtonText && (f.innerHTML = u(b.cancelButtonText)), b.confirmButtonText && (g.innerHTML = u(b.confirmButtonText)),
        g.className = "confirm", s(g, b.confirmButtonClass), c.setAttribute("data-allow-ouside-click", b.allowOutsideClick);
        var t = b.doneFunction ? !0 : !1;
        c.setAttribute("data-has-done-function", t), c.setAttribute("data-timer", b.timer);
    }
    function f() {
        var c = p();
        C(q(), 5), C(c, 5), t(c, "showSweetAlert"), s(c, "hideSweetAlert"), t(c, "visible");
        var d = c.querySelector(".icon.success");
        t(d, "animate"), t(d.querySelector(".tip"), "animateSuccessTip"), t(d.querySelector(".long"), "animateSuccessLong");
        var e = c.querySelector(".icon.error");
        t(e, "animateErrorIcon"), t(e.querySelector(".x-mark"), "animateXMark");
        var f = c.querySelector(".icon.warning");
        t(f, "pulseWarning"), t(f.querySelector(".body"), "pulseWarningIns"), t(f.querySelector(".dot"), "pulseWarningIns"),
        a.onkeydown = j, b.onclick = i, h && h.focus(), k = void 0;
    }
    var h, i, j, k, l = ".sweet-alert", m = ".sweet-overlay", n = [ "error", "warning", "info", "success" ], o = {
        title: "",
        text: "",
        type: null,
        allowOutsideClick: !1,
        showCancelButton: !1,
        closeOnConfirm: !0,
        closeOnCancel: !0,
        confirmButtonText: "OK",
        confirmButtonClass: "btn-primary",
        cancelButtonText: "Cancel",
        imageUrl: null,
        imageSize: null,
        timer: null
    }, p = function() {
        return b.querySelector(l);
    }, q = function() {
        return b.querySelector(m);
    }, r = function(a, b) {
        return new RegExp(" " + b + " ").test(" " + a.className + " ");
    }, s = function(a, b) {
        r(a, b) || (a.className += " " + b);
    }, t = function(a, b) {
        var c = " " + a.className.replace(/[\t\r\n]/g, " ") + " ";
        if (r(a, b)) {
            for (;c.indexOf(" " + b + " ") >= 0; ) c = c.replace(" " + b + " ", " ");
            a.className = c.replace(/^\s+|\s+$/g, "");
        }
    }, u = function(a) {
        var c = b.createElement("div");
        return c.appendChild(b.createTextNode(a)), c.innerHTML;
    }, v = function(a) {
        a.style.opacity = "", a.style.display = "block";
    }, w = function(a) {
        if (a && !a.length) return v(a);
        for (var b = 0; b < a.length; ++b) v(a[b]);
    }, x = function(a) {
        a.style.opacity = "", a.style.display = "none";
    }, y = function(a) {
        if (a && !a.length) return x(a);
        for (var b = 0; b < a.length; ++b) x(a[b]);
    }, z = function(a, b) {
        for (var c = b.parentNode; null !== c; ) {
            if (c === a) return !0;
            c = c.parentNode;
        }
        return !1;
    }, A = function(a) {
        a.style.left = "-9999px", a.style.display = "block";
        var b = a.clientHeight, c = parseInt(getComputedStyle(a).getPropertyValue("padding"), 10);
        return a.style.left = "", a.style.display = "none", "-" + parseInt(b / 2 + c) + "px";
    }, B = function(a, b) {
        if (+a.style.opacity < 1) {
            b = b || 16, a.style.opacity = 0, a.style.display = "block";
            var c = +new Date(), d = function() {
                a.style.opacity = +a.style.opacity + (new Date() - c) / 100, c = +new Date(), +a.style.opacity < 1 && setTimeout(d, b);
            };
            d();
        }
    }, C = function(a, b) {
        b = b || 16, a.style.opacity = 1;
        var c = +new Date(), d = function() {
            a.style.opacity = +a.style.opacity - (new Date() - c) / 100, c = +new Date(), +a.style.opacity > 0 ? setTimeout(d, b) : a.style.display = "none";
        };
        d();
    }, D = function(c) {
        if (MouseEvent) {
            var d = new MouseEvent("click", {
                view: a,
                bubbles: !1,
                cancelable: !0
            });
            c.dispatchEvent(d);
        } else if (b.createEvent) {
            var e = b.createEvent("MouseEvents");
            e.initEvent("click", !1, !1), c.dispatchEvent(e);
        } else b.createEventObject ? c.fireEvent("onclick") : "function" == typeof c.onclick && c.onclick();
    }, E = function(b) {
        "function" == typeof b.stopPropagation ? (b.stopPropagation(), b.preventDefault()) : a.event && a.event.hasOwnProperty("cancelBubble") && (a.event.cancelBubble = !0);
    };
    a.sweetAlertInitialize = function() {
        var a = '<div class="sweet-overlay" tabIndex="-1"></div><div class="sweet-alert" tabIndex="-1"><div class="icon error icon-error"><span class="x-mark"><span class="line left"></span><span class="line right"></span></span></div><div class="icon warning icon-warning"> <span class="body"></span> <span class="dot"></span> </div> <div class="icon info"></div> <div class="icon success icon-success animateErrorIcon"> <span class="line tip"></span> <span class="line long"></span> <div class="placeholder"></div> <div class="fix"></div> </div> <div class="icon custom"></div> <h2>Title</h2><p class="lead text-muted">Text</p><p><button class="cancel btn-cancel" tabIndex="2">Cancel</button> <button class="confirm btn-confirm" tabIndex="1">OK</button></p></div>', c = b.createElement("div");
        c.innerHTML = a, b.body.appendChild(c);
    }, a.sweetAlert = a.swal = function() {
        function h(a) {
            var b = a.keyCode || a.which;
            if (-1 !== [ 9, 13, 32, 27 ].indexOf(b)) {
                for (var c = a.target || a.srcElement, d = -1, e = 0; e < w.length; e++) if (c === w[e]) {
                    d = e;
                    break;
                }
                9 === b ? (c = -1 === d ? u : d === w.length - 1 ? w[0] : w[d + 1], E(a), c.focus()) : (c = 13 === b || 32 === b ? -1 === d ? u : void 0 : 27 !== b || v.hidden || "none" === v.style.display ? void 0 : v,
                void 0 !== c && D(c, a));
            }
        }
        if (void 0 === arguments[0]) return a.console.error("sweetAlert expects at least 1 attribute!"),
        !1;
        var m = d({}, o);
        switch (typeof arguments[0]) {
          case "string":
            m.title = arguments[0], m.text = arguments[1] || "", m.type = arguments[2] || "";
            break;

          case "object":
            if (void 0 === arguments[0].title) return a.console.error('Missing "title" argument!'),
            !1;
            m.title = arguments[0].title, m.text = arguments[0].text || o.text, m.type = arguments[0].type || o.type,
            m.allowOutsideClick = arguments[0].allowOutsideClick || o.allowOutsideClick, m.showCancelButton = void 0 !== arguments[0].showCancelButton ? arguments[0].showCancelButton : o.showCancelButton,
            m.closeOnConfirm = void 0 !== arguments[0].closeOnConfirm ? arguments[0].closeOnConfirm : o.closeOnConfirm,
            m.closeOnCancel = void 0 !== arguments[0].closeOnCancel ? arguments[0].closeOnCancel : o.closeOnCancel,
            m.timer = arguments[0].timer || o.timer, m.confirmButtonText = o.showCancelButton ? "Confirm" : o.confirmButtonText,
            m.confirmButtonText = arguments[0].confirmButtonText || o.confirmButtonText, m.confirmButtonClass = arguments[0].confirmButtonClass || o.confirmButtonClass,
            m.cancelButtonText = arguments[0].cancelButtonText || o.cancelButtonText, m.imageUrl = arguments[0].imageUrl || o.imageUrl,
            m.imageSize = arguments[0].imageSize || o.imageSize, m.doneFunction = arguments[1] || null;
            break;

          default:
            return a.console.error('Unexpected type of argument! Expected "string" or "object", got ' + typeof arguments[0]),
            !1;
        }
        c(m), g(), e();
        for (var n = p(), q = function(a) {
            var b = a.target || a.srcElement, c = b.className.indexOf("confirm") > -1, d = r(n, "visible"), e = m.doneFunction && "true" === n.getAttribute("data-has-done-function");
            switch (a.type) {
              case "click":
                if (c && e && d) m.doneFunction(!0), m.closeOnConfirm && f(); else if (e && d) {
                    var g = String(m.doneFunction).replace(/\s/g, ""), h = "function(" === g.substring(0, 9) && ")" !== g.substring(9, 10);
                    h && m.doneFunction(!1), m.closeOnCancel && f();
                } else f();
            }
        }, s = n.querySelectorAll("button"), t = 0; t < s.length; t++) s[t].onclick = q;
        i = b.onclick, b.onclick = function(a) {
            var b = a.target || a.srcElement, c = n === b, d = z(n, a.target), e = r(n, "visible"), g = "true" === n.getAttribute("data-allow-ouside-click");
            !c && !d && e && g && f();
        };
        var u = n.querySelector("button.confirm"), v = n.querySelector("button.cancel"), w = n.querySelectorAll("button:not([type=hidden])");
        j = a.onkeydown, a.onkeydown = h, u.onblur = l, v.onblur = l, a.onfocus = function() {
            a.setTimeout(function() {
                void 0 !== k && (k.focus(), k = void 0);
            }, 0);
        };
    }, a.swal.setDefaults = function(a) {
        if (!a) throw new Error("userParams is required");
        if ("object" != typeof a) throw new Error("userParams has to be a object");
        d(o, a);
    }, function() {
        "complete" === b.readyState || "interactive" === b.readyState && b.body ? sweetAlertInitialize() : b.addEventListener ? b.addEventListener("DOMContentLoaded", function() {
            b.removeEventListener("DOMContentLoaded", arguments.callee, !1), sweetAlertInitialize();
        }, !1) : b.attachEvent && b.attachEvent("onreadystatechange", function() {
            "complete" === b.readyState && (b.detachEvent("onreadystatechange", arguments.callee),
            sweetAlertInitialize());
        });
    }();
}(window, document), !function(a, b) {
    "use strict";
    var c, d, e = a, f = e.document, g = e.navigator, h = e.setTimeout, i = e.encodeURIComponent, j = e.ActiveXObject, k = e.Error, l = e.Number.parseInt || e.parseInt, m = e.Number.parseFloat || e.parseFloat, n = e.Number.isNaN || e.isNaN, o = e.Math.round, p = e.Date.now, q = e.Object.keys, r = e.Object.defineProperty, s = e.Object.prototype.hasOwnProperty, t = e.Array.prototype.slice, u = function() {
        var a = function(a) {
            return a;
        };
        if ("function" == typeof e.wrap && "function" == typeof e.unwrap) try {
            var b = f.createElement("div"), c = e.unwrap(b);
            1 === b.nodeType && c && 1 === c.nodeType && (a = e.unwrap);
        } catch (d) {}
        return a;
    }(), v = function(a) {
        return t.call(a, 0);
    }, w = function() {
        var a, c, d, e, f, g, h = v(arguments), i = h[0] || {};
        for (a = 1, c = h.length; c > a; a++) if (null != (d = h[a])) for (e in d) s.call(d, e) && (f = i[e],
        g = d[e], i !== g && g !== b && (i[e] = g));
        return i;
    }, x = function(a) {
        var b, c, d, e;
        if ("object" != typeof a || null == a) b = a; else if ("number" == typeof a.length) for (b = [],
        c = 0, d = a.length; d > c; c++) s.call(a, c) && (b[c] = x(a[c])); else {
            b = {};
            for (e in a) s.call(a, e) && (b[e] = x(a[e]));
        }
        return b;
    }, y = function(a, b) {
        for (var c = {}, d = 0, e = b.length; e > d; d++) b[d] in a && (c[b[d]] = a[b[d]]);
        return c;
    }, z = function(a, b) {
        var c = {};
        for (var d in a) -1 === b.indexOf(d) && (c[d] = a[d]);
        return c;
    }, A = function(a) {
        if (a) for (var b in a) s.call(a, b) && delete a[b];
        return a;
    }, B = function(a, b) {
        if (a && 1 === a.nodeType && a.ownerDocument && b && (1 === b.nodeType && b.ownerDocument && b.ownerDocument === a.ownerDocument || 9 === b.nodeType && !b.ownerDocument && b === a.ownerDocument)) do {
            if (a === b) return !0;
            a = a.parentNode;
        } while (a);
        return !1;
    }, C = function(a) {
        var b;
        return "string" == typeof a && a && (b = a.split("#")[0].split("?")[0], b = a.slice(0, a.lastIndexOf("/") + 1)),
        b;
    }, D = function(a) {
        var b, c;
        return "string" == typeof a && a && (c = a.match(/^(?:|[^:@]*@|.+\)@(?=http[s]?|file)|.+?\s+(?: at |@)(?:[^:\(]+ )*[\(]?)((?:http[s]?|file):\/\/[\/]?.+?\/[^:\)]*?)(?::\d+)(?::\d+)?/),
        c && c[1] ? b = c[1] : (c = a.match(/\)@((?:http[s]?|file):\/\/[\/]?.+?\/[^:\)]*?)(?::\d+)(?::\d+)?/),
        c && c[1] && (b = c[1]))), b;
    }, E = function() {
        var a, b;
        try {
            throw new k();
        } catch (c) {
            b = c;
        }
        return b && (a = b.sourceURL || b.fileName || D(b.stack)), a;
    }, F = function() {
        var a, c, d;
        if (f.currentScript && (a = f.currentScript.src)) return a;
        if (c = f.getElementsByTagName("script"), 1 === c.length) return c[0].src || b;
        if ("readyState" in c[0]) for (d = c.length; d--; ) if ("interactive" === c[d].readyState && (a = c[d].src)) return a;
        return "loading" === f.readyState && (a = c[c.length - 1].src) ? a : (a = E()) ? a : b;
    }, G = function() {
        var a, c, d, e = f.getElementsByTagName("script");
        for (a = e.length; a--; ) {
            if (!(d = e[a].src)) {
                c = null;
                break;
            }
            if (d = C(d), null == c) c = d; else if (c !== d) {
                c = null;
                break;
            }
        }
        return c || b;
    }, H = function() {
        var a = C(F()) || G() || "";
        return a + "ZeroClipboard.swf";
    }, I = {
        bridge: null,
        version: "0.0.0",
        pluginType: "unknown",
        disabled: null,
        outdated: null,
        unavailable: null,
        deactivated: null,
        overdue: null,
        ready: null
    }, J = "11.0.0", K = {}, L = {}, M = null, N = {
        ready: "Flash communication is established",
        error: {
            "flash-disabled": "Flash is disabled or not installed",
            "flash-outdated": "Flash is too outdated to support ZeroClipboard",
            "flash-unavailable": "Flash is unable to communicate bidirectionally with JavaScript",
            "flash-deactivated": "Flash is too outdated for your browser and/or is configured as click-to-activate",
            "flash-overdue": "Flash communication was established but NOT within the acceptable time limit"
        }
    }, O = {
        swfPath: H(),
        trustedDomains: a.location.host ? [ a.location.host ] : [],
        cacheBust: !0,
        forceEnhancedClipboard: !1,
        flashLoadTimeout: 3e4,
        autoActivate: !0,
        bubbleEvents: !0,
        containerId: "global-zeroclipboard-html-bridge",
        containerClass: "global-zeroclipboard-container",
        swfObjectId: "global-zeroclipboard-flash-bridge",
        hoverClass: "zeroclipboard-is-hover",
        activeClass: "zeroclipboard-is-active",
        forceHandCursor: !1,
        title: null,
        zIndex: 999999999
    }, P = function(a) {
        if ("object" == typeof a && null !== a) for (var b in a) if (s.call(a, b)) if (/^(?:forceHandCursor|title|zIndex|bubbleEvents)$/.test(b)) O[b] = a[b]; else if (null == I.bridge) if ("containerId" === b || "swfObjectId" === b) {
            if (!cb(a[b])) throw new Error("The specified `" + b + "` value is not valid as an HTML4 Element ID");
            O[b] = a[b];
        } else O[b] = a[b];
        return "string" == typeof a && a ? s.call(O, a) ? O[a] : void 0 : x(O);
    }, Q = function() {
        return {
            browser: y(g, [ "userAgent", "platform", "appName" ]),
            flash: z(I, [ "bridge" ]),
            zeroclipboard: {
                version: Fb.version,
                config: Fb.config()
            }
        };
    }, R = function() {
        return !!(I.disabled || I.outdated || I.unavailable || I.deactivated);
    }, S = function(a, b) {
        var c, d, e, f = {};
        if ("string" == typeof a && a) e = a.toLowerCase().split(/\s+/); else if ("object" == typeof a && a && "undefined" == typeof b) for (c in a) s.call(a, c) && "string" == typeof c && c && "function" == typeof a[c] && Fb.on(c, a[c]);
        if (e && e.length) {
            for (c = 0, d = e.length; d > c; c++) a = e[c].replace(/^on/, ""), f[a] = !0, K[a] || (K[a] = []),
            K[a].push(b);
            if (f.ready && I.ready && Fb.emit({
                type: "ready"
            }), f.error) {
                var g = [ "disabled", "outdated", "unavailable", "deactivated", "overdue" ];
                for (c = 0, d = g.length; d > c; c++) if (I[g[c]] === !0) {
                    Fb.emit({
                        type: "error",
                        name: "flash-" + g[c]
                    });
                    break;
                }
            }
        }
        return Fb;
    }, T = function(a, b) {
        var c, d, e, f, g;
        if (0 === arguments.length) f = q(K); else if ("string" == typeof a && a) f = a.split(/\s+/); else if ("object" == typeof a && a && "undefined" == typeof b) for (c in a) s.call(a, c) && "string" == typeof c && c && "function" == typeof a[c] && Fb.off(c, a[c]);
        if (f && f.length) for (c = 0, d = f.length; d > c; c++) if (a = f[c].toLowerCase().replace(/^on/, ""),
        g = K[a], g && g.length) if (b) for (e = g.indexOf(b); -1 !== e; ) g.splice(e, 1),
        e = g.indexOf(b, e); else g.length = 0;
        return Fb;
    }, U = function(a) {
        var b;
        return b = "string" == typeof a && a ? x(K[a]) || null : x(K);
    }, V = function(a) {
        var b, c, d;
        return a = db(a), a && !jb(a) ? "ready" === a.type && I.overdue === !0 ? Fb.emit({
            type: "error",
            name: "flash-overdue"
        }) : (b = w({}, a), ib.call(this, b), "copy" === a.type && (d = pb(L), c = d.data,
        M = d.formatMap), c) : void 0;
    }, W = function() {
        if ("boolean" != typeof I.ready && (I.ready = !1), !Fb.isFlashUnusable() && null === I.bridge) {
            var a = O.flashLoadTimeout;
            "number" == typeof a && a >= 0 && h(function() {
                "boolean" != typeof I.deactivated && (I.deactivated = !0), I.deactivated === !0 && Fb.emit({
                    type: "error",
                    name: "flash-deactivated"
                });
            }, a), I.overdue = !1, nb();
        }
    }, X = function() {
        Fb.clearData(), Fb.blur(), Fb.emit("destroy"), ob(), Fb.off();
    }, Y = function(a, b) {
        var c;
        if ("object" == typeof a && a && "undefined" == typeof b) c = a, Fb.clearData(); else {
            if ("string" != typeof a || !a) return;
            c = {}, c[a] = b;
        }
        for (var d in c) "string" == typeof d && d && s.call(c, d) && "string" == typeof c[d] && c[d] && (L[d] = c[d]);
    }, Z = function(a) {
        "undefined" == typeof a ? (A(L), M = null) : "string" == typeof a && s.call(L, a) && delete L[a];
    }, $ = function(a) {
        return "undefined" == typeof a ? x(L) : "string" == typeof a && s.call(L, a) ? L[a] : void 0;
    }, _ = function(a) {
        if (a && 1 === a.nodeType) {
            c && (xb(c, O.activeClass), c !== a && xb(c, O.hoverClass)), c = a, wb(a, O.hoverClass);
            var b = a.getAttribute("title") || O.title;
            if ("string" == typeof b && b) {
                var d = mb(I.bridge);
                d && d.setAttribute("title", b);
            }
            var e = O.forceHandCursor === !0 || "pointer" === yb(a, "cursor");
            Cb(e), Bb();
        }
    }, ab = function() {
        var a = mb(I.bridge);
        a && (a.removeAttribute("title"), a.style.left = "0px", a.style.top = "-9999px",
        a.style.width = "1px", a.style.top = "1px"), c && (xb(c, O.hoverClass), xb(c, O.activeClass),
        c = null);
    }, bb = function() {
        return c || null;
    }, cb = function(a) {
        return "string" == typeof a && a && /^[A-Za-z][A-Za-z0-9_:\-\.]*$/.test(a);
    }, db = function(a) {
        var b;
        if ("string" == typeof a && a ? (b = a, a = {}) : "object" == typeof a && a && "string" == typeof a.type && a.type && (b = a.type),
        b) {
            !a.target && /^(copy|aftercopy|_click)$/.test(b.toLowerCase()) && (a.target = d),
            w(a, {
                type: b.toLowerCase(),
                target: a.target || c || null,
                relatedTarget: a.relatedTarget || null,
                currentTarget: I && I.bridge || null,
                timeStamp: a.timeStamp || p() || null
            });
            var e = N[a.type];
            return "error" === a.type && a.name && e && (e = e[a.name]), e && (a.message = e),
            "ready" === a.type && w(a, {
                target: null,
                version: I.version
            }), "error" === a.type && (/^flash-(disabled|outdated|unavailable|deactivated|overdue)$/.test(a.name) && w(a, {
                target: null,
                minimumVersion: J
            }), /^flash-(outdated|unavailable|deactivated|overdue)$/.test(a.name) && w(a, {
                version: I.version
            })), "copy" === a.type && (a.clipboardData = {
                setData: Fb.setData,
                clearData: Fb.clearData
            }), "aftercopy" === a.type && (a = qb(a, M)), a.target && !a.relatedTarget && (a.relatedTarget = eb(a.target)),
            a = fb(a);
        }
    }, eb = function(a) {
        var b = a && a.getAttribute && a.getAttribute("data-clipboard-target");
        return b ? f.getElementById(b) : null;
    }, fb = function(a) {
        if (a && /^_(?:click|mouse(?:over|out|down|up|move))$/.test(a.type)) {
            var c = a.target, d = "_mouseover" === a.type && a.relatedTarget ? a.relatedTarget : b, g = "_mouseout" === a.type && a.relatedTarget ? a.relatedTarget : b, h = Ab(c), i = e.screenLeft || e.screenX || 0, j = e.screenTop || e.screenY || 0, k = f.body.scrollLeft + f.documentElement.scrollLeft, l = f.body.scrollTop + f.documentElement.scrollTop, m = h.left + ("number" == typeof a._stageX ? a._stageX : 0), n = h.top + ("number" == typeof a._stageY ? a._stageY : 0), o = m - k, p = n - l, q = i + o, r = j + p, s = "number" == typeof a.movementX ? a.movementX : 0, t = "number" == typeof a.movementY ? a.movementY : 0;
            delete a._stageX, delete a._stageY, w(a, {
                srcElement: c,
                fromElement: d,
                toElement: g,
                screenX: q,
                screenY: r,
                pageX: m,
                pageY: n,
                clientX: o,
                clientY: p,
                x: o,
                y: p,
                movementX: s,
                movementY: t,
                offsetX: 0,
                offsetY: 0,
                layerX: 0,
                layerY: 0
            });
        }
        return a;
    }, gb = function(a) {
        var b = a && "string" == typeof a.type && a.type || "";
        return !/^(?:(?:before)?copy|destroy)$/.test(b);
    }, hb = function(a, b, c, d) {
        d ? h(function() {
            a.apply(b, c);
        }, 0) : a.apply(b, c);
    }, ib = function(a) {
        if ("object" == typeof a && a && a.type) {
            var b = gb(a), c = K["*"] || [], d = K[a.type] || [], f = c.concat(d);
            if (f && f.length) {
                var g, h, i, j, k, l = this;
                for (g = 0, h = f.length; h > g; g++) i = f[g], j = l, "string" == typeof i && "function" == typeof e[i] && (i = e[i]),
                "object" == typeof i && i && "function" == typeof i.handleEvent && (j = i, i = i.handleEvent),
                "function" == typeof i && (k = w({}, a), hb(i, j, [ k ], b));
            }
            return this;
        }
    }, jb = function(a) {
        var b = a.target || c || null, e = "swf" === a._source;
        delete a._source;
        var f = [ "flash-disabled", "flash-outdated", "flash-unavailable", "flash-deactivated", "flash-overdue" ];
        switch (a.type) {
          case "error":
            -1 !== f.indexOf(a.name) && w(I, {
                disabled: "flash-disabled" === a.name,
                outdated: "flash-outdated" === a.name,
                unavailable: "flash-unavailable" === a.name,
                deactivated: "flash-deactivated" === a.name,
                overdue: "flash-overdue" === a.name,
                ready: !1
            });
            break;

          case "ready":
            var g = I.deactivated === !0;
            w(I, {
                disabled: !1,
                outdated: !1,
                unavailable: !1,
                deactivated: !1,
                overdue: g,
                ready: !g
            });
            break;

          case "beforecopy":
            d = b;
            break;

          case "copy":
            var h, i, j = a.relatedTarget;
            !L["text/html"] && !L["text/plain"] && j && (i = j.value || j.outerHTML || j.innerHTML) && (h = j.value || j.textContent || j.innerText) ? (a.clipboardData.clearData(),
            a.clipboardData.setData("text/plain", h), i !== h && a.clipboardData.setData("text/html", i)) : !L["text/plain"] && a.target && (h = a.target.getAttribute("data-clipboard-text")) && (a.clipboardData.clearData(),
            a.clipboardData.setData("text/plain", h));
            break;

          case "aftercopy":
            Fb.clearData(), b && b !== vb() && b.focus && b.focus();
            break;

          case "_mouseover":
            Fb.focus(b), O.bubbleEvents === !0 && e && (b && b !== a.relatedTarget && !B(a.relatedTarget, b) && kb(w({}, a, {
                type: "mouseenter",
                bubbles: !1,
                cancelable: !1
            })), kb(w({}, a, {
                type: "mouseover"
            })));
            break;

          case "_mouseout":
            Fb.blur(), O.bubbleEvents === !0 && e && (b && b !== a.relatedTarget && !B(a.relatedTarget, b) && kb(w({}, a, {
                type: "mouseleave",
                bubbles: !1,
                cancelable: !1
            })), kb(w({}, a, {
                type: "mouseout"
            })));
            break;

          case "_mousedown":
            wb(b, O.activeClass), O.bubbleEvents === !0 && e && kb(w({}, a, {
                type: a.type.slice(1)
            }));
            break;

          case "_mouseup":
            xb(b, O.activeClass), O.bubbleEvents === !0 && e && kb(w({}, a, {
                type: a.type.slice(1)
            }));
            break;

          case "_click":
            d = null, O.bubbleEvents === !0 && e && kb(w({}, a, {
                type: a.type.slice(1)
            }));
            break;

          case "_mousemove":
            O.bubbleEvents === !0 && e && kb(w({}, a, {
                type: a.type.slice(1)
            }));
        }
        return /^_(?:click|mouse(?:over|out|down|up|move))$/.test(a.type) ? !0 : void 0;
    }, kb = function(a) {
        if (a && "string" == typeof a.type && a) {
            var b, c = a.target || null, d = c && c.ownerDocument || f, g = {
                view: d.defaultView || e,
                canBubble: !0,
                cancelable: !0,
                detail: "click" === a.type ? 1 : 0,
                button: "number" == typeof a.which ? a.which - 1 : "number" == typeof a.button ? a.button : d.createEvent ? 0 : 1
            }, h = w(g, a);
            c && d.createEvent && c.dispatchEvent && (h = [ h.type, h.canBubble, h.cancelable, h.view, h.detail, h.screenX, h.screenY, h.clientX, h.clientY, h.ctrlKey, h.altKey, h.shiftKey, h.metaKey, h.button, h.relatedTarget ],
            b = d.createEvent("MouseEvents"), b.initMouseEvent && (b.initMouseEvent.apply(b, h),
            b._source = "js", c.dispatchEvent(b)));
        }
    }, lb = function() {
        var a = f.createElement("div");
        return a.id = O.containerId, a.className = O.containerClass, a.style.position = "absolute",
        a.style.left = "0px", a.style.top = "-9999px", a.style.width = "1px", a.style.height = "1px",
        a.style.zIndex = "" + Db(O.zIndex), a;
    }, mb = function(a) {
        for (var b = a && a.parentNode; b && "OBJECT" === b.nodeName && b.parentNode; ) b = b.parentNode;
        return b || null;
    }, nb = function() {
        var a, b = I.bridge, c = mb(b);
        if (!b) {
            var d = ub(e.location.host, O), g = "never" === d ? "none" : "all", h = sb(O), i = O.swfPath + rb(O.swfPath, O);
            c = lb();
            var j = f.createElement("div");
            c.appendChild(j), f.body.appendChild(c);
            var k = f.createElement("div"), l = "activex" === I.pluginType;
            k.innerHTML = '<object id="' + O.swfObjectId + '" name="' + O.swfObjectId + '" width="100%" height="100%" ' + (l ? 'classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"' : 'type="application/x-shockwave-flash" data="' + i + '"') + ">" + (l ? '<param name="movie" value="' + i + '"/>' : "") + '<param name="allowScriptAccess" value="' + d + '"/><param name="allowNetworking" value="' + g + '"/><param name="menu" value="false"/><param name="wmode" value="transparent"/><param name="flashvars" value="' + h + '"/></object>',
            b = k.firstChild, k = null, u(b).ZeroClipboard = Fb, c.replaceChild(b, j);
        }
        return b || (b = f[O.swfObjectId], b && (a = b.length) && (b = b[a - 1]), !b && c && (b = c.firstChild)),
        I.bridge = b || null, b;
    }, ob = function() {
        var a = I.bridge;
        if (a) {
            var b = mb(a);
            b && ("activex" === I.pluginType && "readyState" in a ? (a.style.display = "none",
            function c() {
                if (4 === a.readyState) {
                    for (var d in a) "function" == typeof a[d] && (a[d] = null);
                    a.parentNode && a.parentNode.removeChild(a), b.parentNode && b.parentNode.removeChild(b);
                } else h(c, 10);
            }()) : (a.parentNode && a.parentNode.removeChild(a), b.parentNode && b.parentNode.removeChild(b))),
            I.ready = null, I.bridge = null, I.deactivated = null;
        }
    }, pb = function(a) {
        var b = {}, c = {};
        if ("object" == typeof a && a) {
            for (var d in a) if (d && s.call(a, d) && "string" == typeof a[d] && a[d]) switch (d.toLowerCase()) {
              case "text/plain":
              case "text":
              case "air:text":
              case "flash:text":
                b.text = a[d], c.text = d;
                break;

              case "text/html":
              case "html":
              case "air:html":
              case "flash:html":
                b.html = a[d], c.html = d;
                break;

              case "application/rtf":
              case "text/rtf":
              case "rtf":
              case "richtext":
              case "air:rtf":
              case "flash:rtf":
                b.rtf = a[d], c.rtf = d;
            }
            return {
                data: b,
                formatMap: c
            };
        }
    }, qb = function(a, b) {
        if ("object" != typeof a || !a || "object" != typeof b || !b) return a;
        var c = {};
        for (var d in a) if (s.call(a, d)) {
            if ("success" !== d && "data" !== d) {
                c[d] = a[d];
                continue;
            }
            c[d] = {};
            var e = a[d];
            for (var f in e) f && s.call(e, f) && s.call(b, f) && (c[d][b[f]] = e[f]);
        }
        return c;
    }, rb = function(a, b) {
        var c = null == b || b && b.cacheBust === !0;
        return c ? (-1 === a.indexOf("?") ? "?" : "&") + "noCache=" + p() : "";
    }, sb = function(a) {
        var b, c, d, f, g = "", h = [];
        if (a.trustedDomains && ("string" == typeof a.trustedDomains ? f = [ a.trustedDomains ] : "object" == typeof a.trustedDomains && "length" in a.trustedDomains && (f = a.trustedDomains)),
        f && f.length) for (b = 0, c = f.length; c > b; b++) if (s.call(f, b) && f[b] && "string" == typeof f[b]) {
            if (d = tb(f[b]), !d) continue;
            if ("*" === d) {
                h.length = 0, h.push(d);
                break;
            }
            h.push.apply(h, [ d, "//" + d, e.location.protocol + "//" + d ]);
        }
        return h.length && (g += "trustedOrigins=" + i(h.join(","))), a.forceEnhancedClipboard === !0 && (g += (g ? "&" : "") + "forceEnhancedClipboard=true"),
        "string" == typeof a.swfObjectId && a.swfObjectId && (g += (g ? "&" : "") + "swfObjectId=" + i(a.swfObjectId)),
        g;
    }, tb = function(a) {
        if (null == a || "" === a) return null;
        if (a = a.replace(/^\s+|\s+$/g, ""), "" === a) return null;
        var b = a.indexOf("//");
        a = -1 === b ? a : a.slice(b + 2);
        var c = a.indexOf("/");
        return a = -1 === c ? a : -1 === b || 0 === c ? null : a.slice(0, c), a && ".swf" === a.slice(-4).toLowerCase() ? null : a || null;
    }, ub = function() {
        var a = function(a) {
            var b, c, d, e = [];
            if ("string" == typeof a && (a = [ a ]), "object" != typeof a || !a || "number" != typeof a.length) return e;
            for (b = 0, c = a.length; c > b; b++) if (s.call(a, b) && (d = tb(a[b]))) {
                if ("*" === d) {
                    e.length = 0, e.push("*");
                    break;
                }
                -1 === e.indexOf(d) && e.push(d);
            }
            return e;
        };
        return function(b, c) {
            var d = tb(c.swfPath);
            null === d && (d = b);
            var e = a(c.trustedDomains), f = e.length;
            if (f > 0) {
                if (1 === f && "*" === e[0]) return "always";
                if (-1 !== e.indexOf(b)) return 1 === f && b === d ? "sameDomain" : "always";
            }
            return "never";
        };
    }(), vb = function() {
        try {
            return f.activeElement;
        } catch (a) {
            return null;
        }
    }, wb = function(a, b) {
        if (!a || 1 !== a.nodeType) return a;
        if (a.classList) return a.classList.contains(b) || a.classList.add(b), a;
        if (b && "string" == typeof b) {
            var c = (b || "").split(/\s+/);
            if (1 === a.nodeType) if (a.className) {
                for (var d = " " + a.className + " ", e = a.className, f = 0, g = c.length; g > f; f++) d.indexOf(" " + c[f] + " ") < 0 && (e += " " + c[f]);
                a.className = e.replace(/^\s+|\s+$/g, "");
            } else a.className = b;
        }
        return a;
    }, xb = function(a, b) {
        if (!a || 1 !== a.nodeType) return a;
        if (a.classList) return a.classList.contains(b) && a.classList.remove(b), a;
        if ("string" == typeof b && b) {
            var c = b.split(/\s+/);
            if (1 === a.nodeType && a.className) {
                for (var d = (" " + a.className + " ").replace(/[\n\t]/g, " "), e = 0, f = c.length; f > e; e++) d = d.replace(" " + c[e] + " ", " ");
                a.className = d.replace(/^\s+|\s+$/g, "");
            }
        }
        return a;
    }, yb = function(a, b) {
        var c = e.getComputedStyle(a, null).getPropertyValue(b);
        return "cursor" !== b || c && "auto" !== c || "A" !== a.nodeName ? c : "pointer";
    }, zb = function() {
        var a, b, c, d = 1;
        return "function" == typeof f.body.getBoundingClientRect && (a = f.body.getBoundingClientRect(),
        b = a.right - a.left, c = f.body.offsetWidth, d = o(b / c * 100) / 100), d;
    }, Ab = function(a) {
        var b = {
            left: 0,
            top: 0,
            width: 0,
            height: 0
        };
        if (a.getBoundingClientRect) {
            var c, d, g, h = a.getBoundingClientRect();
            "pageXOffset" in e && "pageYOffset" in e ? (c = e.pageXOffset, d = e.pageYOffset) : (g = zb(),
            c = o(f.documentElement.scrollLeft / g), d = o(f.documentElement.scrollTop / g));
            var i = f.documentElement.clientLeft || 0, j = f.documentElement.clientTop || 0;
            b.left = h.left + c - i, b.top = h.top + d - j, b.width = "width" in h ? h.width : h.right - h.left,
            b.height = "height" in h ? h.height : h.bottom - h.top;
        }
        return b;
    }, Bb = function() {
        var a;
        if (c && (a = mb(I.bridge))) {
            var b = Ab(c);
            w(a.style, {
                width: b.width + "px",
                height: b.height + "px",
                top: b.top + "px",
                left: b.left + "px",
                zIndex: "" + Db(O.zIndex)
            });
        }
    }, Cb = function(a) {
        I.ready === !0 && (I.bridge && "function" == typeof I.bridge.setHandCursor ? I.bridge.setHandCursor(a) : I.ready = !1);
    }, Db = function(a) {
        if (/^(?:auto|inherit)$/.test(a)) return a;
        var b;
        return "number" != typeof a || n(a) ? "string" == typeof a && (b = Db(l(a, 10))) : b = a,
        "number" == typeof b ? b : "auto";
    }, Eb = function(a) {
        function b(a) {
            var b = a.match(/[\d]+/g);
            return b.length = 3, b.join(".");
        }
        function c(a) {
            return !!a && (a = a.toLowerCase()) && (/^(pepflashplayer\.dll|libpepflashplayer\.so|pepperflashplayer\.plugin)$/.test(a) || "chrome.plugin" === a.slice(-13));
        }
        function d(a) {
            a && (i = !0, a.version && (l = b(a.version)), !l && a.description && (l = b(a.description)),
            a.filename && (k = c(a.filename)));
        }
        var e, f, h, i = !1, j = !1, k = !1, l = "";
        if (g.plugins && g.plugins.length) e = g.plugins["Shockwave Flash"], d(e), g.plugins["Shockwave Flash 2.0"] && (i = !0,
        l = "2.0.0.11"); else if (g.mimeTypes && g.mimeTypes.length) h = g.mimeTypes["application/x-shockwave-flash"],
        e = h && h.enabledPlugin, d(e); else if ("undefined" != typeof a) {
            j = !0;
            try {
                f = new a("ShockwaveFlash.ShockwaveFlash.7"), i = !0, l = b(f.GetVariable("$version"));
            } catch (n) {
            }
        }
        I.disabled = i !== !0, I.outdated = l && m(l) < m(J), I.version = l || "0.0.0",
        I.pluginType = k ? "pepper" : j ? "activex" : i ? "netscape" : "unknown";
    };
    Eb(j);
    var Fb = function() {
        return this instanceof Fb ? void ("function" == typeof Fb._createClient && Fb._createClient.apply(this, v(arguments))) : new Fb();
    };
    r(Fb, "version", {
        value: "2.1.6",
        writable: !1,
        configurable: !0,
        enumerable: !0
    }), Fb.config = function() {
        return P.apply(this, v(arguments));
    }, Fb.state = function() {
        return Q.apply(this, v(arguments));
    }, Fb.isFlashUnusable = function() {
        return R.apply(this, v(arguments));
    }, Fb.on = function() {
        return S.apply(this, v(arguments));
    }, Fb.off = function() {
        return T.apply(this, v(arguments));
    }, Fb.handlers = function() {
        return U.apply(this, v(arguments));
    }, Fb.emit = function() {
        return V.apply(this, v(arguments));
    }, Fb.create = function() {
        return W.apply(this, v(arguments));
    }, Fb.destroy = function() {
        return X.apply(this, v(arguments));
    }, Fb.setData = function() {
        return Y.apply(this, v(arguments));
    }, Fb.clearData = function() {
        return Z.apply(this, v(arguments));
    }, Fb.getData = function() {
        return $.apply(this, v(arguments));
    }, Fb.focus = Fb.activate = function() {
        return _.apply(this, v(arguments));
    }, Fb.blur = Fb.deactivate = function() {
        return ab.apply(this, v(arguments));
    }, Fb.activeElement = function() {
        return bb.apply(this, v(arguments));
    };
    var Gb = 0, Hb = {}, Ib = 0, Jb = {}, Kb = {};
    w(O, {
        autoActivate: !0
    });
    var Lb = function(a) {
        var b = this;
        b.id = "" + Gb++, Hb[b.id] = {
            instance: b,
            elements: [],
            handlers: {}
        }, a && b.clip(a), Fb.on("*", function(a) {
            return b.emit(a);
        }), Fb.on("destroy", function() {
            b.destroy();
        }), Fb.create();
    }, Mb = function(a, b) {
        var c, d, e, f = {}, g = Hb[this.id] && Hb[this.id].handlers;
        if ("string" == typeof a && a) e = a.toLowerCase().split(/\s+/); else if ("object" == typeof a && a && "undefined" == typeof b) for (c in a) s.call(a, c) && "string" == typeof c && c && "function" == typeof a[c] && this.on(c, a[c]);
        if (e && e.length) {
            for (c = 0, d = e.length; d > c; c++) a = e[c].replace(/^on/, ""), f[a] = !0, g[a] || (g[a] = []),
            g[a].push(b);
            if (f.ready && I.ready && this.emit({
                type: "ready",
                client: this
            }), f.error) {
                var h = [ "disabled", "outdated", "unavailable", "deactivated", "overdue" ];
                for (c = 0, d = h.length; d > c; c++) if (I[h[c]]) {
                    this.emit({
                        type: "error",
                        name: "flash-" + h[c],
                        client: this
                    });
                    break;
                }
            }
        }
        return this;
    }, Nb = function(a, b) {
        var c, d, e, f, g, h = Hb[this.id] && Hb[this.id].handlers;
        if (0 === arguments.length) f = q(h); else if ("string" == typeof a && a) f = a.split(/\s+/); else if ("object" == typeof a && a && "undefined" == typeof b) for (c in a) s.call(a, c) && "string" == typeof c && c && "function" == typeof a[c] && this.off(c, a[c]);
        if (f && f.length) for (c = 0, d = f.length; d > c; c++) if (a = f[c].toLowerCase().replace(/^on/, ""),
        g = h[a], g && g.length) if (b) for (e = g.indexOf(b); -1 !== e; ) g.splice(e, 1),
        e = g.indexOf(b, e); else g.length = 0;
        return this;
    }, Ob = function(a) {
        var b = null, c = Hb[this.id] && Hb[this.id].handlers;
        return c && (b = "string" == typeof a && a ? c[a] ? c[a].slice(0) : [] : x(c)),
        b;
    }, Pb = function(a) {
        if (Ub.call(this, a)) {
            "object" == typeof a && a && "string" == typeof a.type && a.type && (a = w({}, a));
            var b = w({}, db(a), {
                client: this
            });
            Vb.call(this, b);
        }
        return this;
    }, Qb = function(a) {
        a = Wb(a);
        for (var b = 0; b < a.length; b++) if (s.call(a, b) && a[b] && 1 === a[b].nodeType) {
            a[b].zcClippingId ? -1 === Jb[a[b].zcClippingId].indexOf(this.id) && Jb[a[b].zcClippingId].push(this.id) : (a[b].zcClippingId = "zcClippingId_" + Ib++,
            Jb[a[b].zcClippingId] = [ this.id ], O.autoActivate === !0 && Xb(a[b]));
            var c = Hb[this.id] && Hb[this.id].elements;
            -1 === c.indexOf(a[b]) && c.push(a[b]);
        }
        return this;
    }, Rb = function(a) {
        var b = Hb[this.id];
        if (!b) return this;
        var c, d = b.elements;
        a = "undefined" == typeof a ? d.slice(0) : Wb(a);
        for (var e = a.length; e--; ) if (s.call(a, e) && a[e] && 1 === a[e].nodeType) {
            for (c = 0; -1 !== (c = d.indexOf(a[e], c)); ) d.splice(c, 1);
            var f = Jb[a[e].zcClippingId];
            if (f) {
                for (c = 0; -1 !== (c = f.indexOf(this.id, c)); ) f.splice(c, 1);
                0 === f.length && (O.autoActivate === !0 && Yb(a[e]), delete a[e].zcClippingId);
            }
        }
        return this;
    }, Sb = function() {
        var a = Hb[this.id];
        return a && a.elements ? a.elements.slice(0) : [];
    }, Tb = function() {
        this.unclip(), this.off(), delete Hb[this.id];
    }, Ub = function(a) {
        if (!a || !a.type) return !1;
        if (a.client && a.client !== this) return !1;
        var b = Hb[this.id] && Hb[this.id].elements, c = !!b && b.length > 0, d = !a.target || c && -1 !== b.indexOf(a.target), e = a.relatedTarget && c && -1 !== b.indexOf(a.relatedTarget), f = a.client && a.client === this;
        return d || e || f ? !0 : !1;
    }, Vb = function(a) {
        if ("object" == typeof a && a && a.type) {
            var b = gb(a), c = Hb[this.id] && Hb[this.id].handlers["*"] || [], d = Hb[this.id] && Hb[this.id].handlers[a.type] || [], f = c.concat(d);
            if (f && f.length) {
                var g, h, i, j, k, l = this;
                for (g = 0, h = f.length; h > g; g++) i = f[g], j = l, "string" == typeof i && "function" == typeof e[i] && (i = e[i]),
                "object" == typeof i && i && "function" == typeof i.handleEvent && (j = i, i = i.handleEvent),
                "function" == typeof i && (k = w({}, a), hb(i, j, [ k ], b));
            }
            return this;
        }
    }, Wb = function(a) {
        return "string" == typeof a && (a = []), "number" != typeof a.length ? [ a ] : a;
    }, Xb = function(a) {
        if (a && 1 === a.nodeType) {
            var b = function(a) {
                (a || (a = e.event)) && ("js" !== a._source && (a.stopImmediatePropagation(), a.preventDefault()),
                delete a._source);
            }, c = function(c) {
                (c || (c = e.event)) && (b(c), Fb.focus(a));
            };
            a.addEventListener("mouseover", c, !1), a.addEventListener("mouseout", b, !1), a.addEventListener("mouseenter", b, !1),
            a.addEventListener("mouseleave", b, !1), a.addEventListener("mousemove", b, !1),
            Kb[a.zcClippingId] = {
                mouseover: c,
                mouseout: b,
                mouseenter: b,
                mouseleave: b,
                mousemove: b
            };
        }
    }, Yb = function(a) {
        if (a && 1 === a.nodeType) {
            var b = Kb[a.zcClippingId];
            if ("object" == typeof b && b) {
                for (var c, d, e = [ "move", "leave", "enter", "out", "over" ], f = 0, g = e.length; g > f; f++) c = "mouse" + e[f],
                d = b[c], "function" == typeof d && a.removeEventListener(c, d, !1);
                delete Kb[a.zcClippingId];
            }
        }
    };
    Fb._createClient = function() {
        Lb.apply(this, v(arguments));
    }, Fb.prototype.on = function() {
        return Mb.apply(this, v(arguments));
    }, Fb.prototype.off = function() {
        return Nb.apply(this, v(arguments));
    }, Fb.prototype.handlers = function() {
        return Ob.apply(this, v(arguments));
    }, Fb.prototype.emit = function() {
        return Pb.apply(this, v(arguments));
    }, Fb.prototype.clip = function() {
        return Qb.apply(this, v(arguments));
    }, Fb.prototype.unclip = function() {
        return Rb.apply(this, v(arguments));
    }, Fb.prototype.elements = function() {
        return Sb.apply(this, v(arguments));
    }, Fb.prototype.destroy = function() {
        return Tb.apply(this, v(arguments));
    }, Fb.prototype.setText = function(a) {
        return Fb.setData("text/plain", a), this;
    }, Fb.prototype.setHtml = function(a) {
        return Fb.setData("text/html", a), this;
    }, Fb.prototype.setRichText = function(a) {
        return Fb.setData("application/rtf", a), this;
    }, Fb.prototype.setData = function() {
        return Fb.setData.apply(this, v(arguments)), this;
    }, Fb.prototype.clearData = function() {
        return Fb.clearData.apply(this, v(arguments)), this;
    }, Fb.prototype.getData = function() {
        return Fb.getData.apply(this, v(arguments));
    }, "function" == typeof define && define.amd ? define(function() {
        return Fb;
    }) : "object" == typeof module && module && "object" == typeof module.exports && module.exports ? module.exports = Fb : a.ZeroClipboard = Fb;
}(function() {
    return this || window;
}()), function() {
    function a(b) {
        var c = a.modules[b];
        if (!c) throw new Error('failed to require "' + b + '"');
        return "exports" in c || "function" != typeof c.definition || (c.client = c.component = !0,
        c.definition.call(this, c.exports = {}, c), delete c.definition), c.exports;
    }
    a.loader = "component", a.helper = {}, a.helper.semVerSort = function(a, b) {
        for (var c = a.version.split("."), d = b.version.split("."), e = 0; e < c.length; ++e) {
            var f = parseInt(c[e], 10), g = parseInt(d[e], 10);
            if (f !== g) return f > g ? 1 : -1;
            var h = c[e].substr(("" + f).length), i = d[e].substr(("" + g).length);
            if ("" === h && "" !== i) return 1;
            if ("" !== h && "" === i) return -1;
            if ("" !== h && "" !== i) return h > i ? 1 : -1;
        }
        return 0;
    }, a.modules = {}, a.register = function(b, c) {
        a.modules[b] = {
            definition: c
        };
    }, a.define = function(b, c) {
        a.modules[b] = {
            exports: c
        };
    }, a.register("component~emitter@1.1.2", function(a, b) {
        function c(a) {
            return a ? d(a) : void 0;
        }
        function d(a) {
            for (var b in c.prototype) a[b] = c.prototype[b];
            return a;
        }
        b.exports = c, c.prototype.on = c.prototype.addEventListener = function(a, b) {
            return this._callbacks = this._callbacks || {}, (this._callbacks[a] = this._callbacks[a] || []).push(b),
            this;
        }, c.prototype.once = function(a, b) {
            function c() {
                d.off(a, c), b.apply(this, arguments);
            }
            var d = this;
            return this._callbacks = this._callbacks || {}, c.fn = b, this.on(a, c), this;
        }, c.prototype.off = c.prototype.removeListener = c.prototype.removeAllListeners = c.prototype.removeEventListener = function(a, b) {
            if (this._callbacks = this._callbacks || {}, 0 == arguments.length) return this._callbacks = {},
            this;
            var c = this._callbacks[a];
            if (!c) return this;
            if (1 == arguments.length) return delete this._callbacks[a], this;
            for (var d, e = 0; e < c.length; e++) if (d = c[e], d === b || d.fn === b) {
                c.splice(e, 1);
                break;
            }
            return this;
        }, c.prototype.emit = function(a) {
            this._callbacks = this._callbacks || {};
            var b = [].slice.call(arguments, 1), c = this._callbacks[a];
            if (c) {
                c = c.slice(0);
                for (var d = 0, e = c.length; e > d; ++d) c[d].apply(this, b);
            }
            return this;
        }, c.prototype.listeners = function(a) {
            return this._callbacks = this._callbacks || {}, this._callbacks[a] || [];
        }, c.prototype.hasListeners = function(a) {
            return !!this.listeners(a).length;
        };
    }), a.register("dropzone", function(b, c) {
        c.exports = a("dropzone/lib/dropzone.js");
    }), a.register("dropzone/lib/dropzone.js", function(b, c) {
        (function() {
            var b, d, e, f, g, h, i, j, k = {}.hasOwnProperty, l = function(a, b) {
                function c() {
                    this.constructor = a;
                }
                for (var d in b) k.call(b, d) && (a[d] = b[d]);
                return c.prototype = b.prototype, a.prototype = new c(), a.__super__ = b.prototype,
                a;
            }, m = [].slice;
            d = "undefined" != typeof Emitter && null !== Emitter ? Emitter : a("component~emitter@1.1.2"),
            i = function() {}, b = function(a) {
                function b(a, d) {
                    var e, f, g;
                    if (this.element = a, this.version = b.version, this.defaultOptions.previewTemplate = this.defaultOptions.previewTemplate.replace(/\n*/g, ""),
                    this.clickableElements = [], this.listeners = [], this.files = [], "string" == typeof this.element && (this.element = document.querySelector(this.element)),
                    !this.element || null == this.element.nodeType) throw new Error("Invalid dropzone element.");
                    if (this.element.dropzone) throw new Error("Dropzone already attached.");
                    if (b.instances.push(this), this.element.dropzone = this, e = null != (g = b.optionsForElement(this.element)) ? g : {},
                    this.options = c({}, this.defaultOptions, e, null != d ? d : {}), this.options.forceFallback || !b.isBrowserSupported()) return this.options.fallback.call(this);
                    if (null == this.options.url && (this.options.url = this.element.getAttribute("action")),
                    !this.options.url) throw new Error("No URL provided.");
                    if (this.options.acceptedFiles && this.options.acceptedMimeTypes) throw new Error("You can't provide both 'acceptedFiles' and 'acceptedMimeTypes'. 'acceptedMimeTypes' is deprecated.");
                    this.options.acceptedMimeTypes && (this.options.acceptedFiles = this.options.acceptedMimeTypes,
                    delete this.options.acceptedMimeTypes), this.options.method = this.options.method.toUpperCase(),
                    (f = this.getExistingFallback()) && f.parentNode && f.parentNode.removeChild(f),
                    this.options.previewsContainer !== !1 && (this.options.previewsContainer ? this.previewsContainer = b.getElement(this.options.previewsContainer, "previewsContainer") : this.previewsContainer = this.element),
                    this.options.clickable && (this.options.clickable === !0 ? this.clickableElements = [ this.element ] : this.clickableElements = b.getElements(this.options.clickable, "clickable")),
                    this.init();
                }
                var c;
                return l(b, a), b.prototype.events = [ "drop", "dragstart", "dragend", "dragenter", "dragover", "dragleave", "addedfile", "removedfile", "thumbnail", "error", "errormultiple", "processing", "processingmultiple", "uploadprogress", "totaluploadprogress", "sending", "sendingmultiple", "success", "successmultiple", "canceled", "canceledmultiple", "complete", "completemultiple", "reset", "maxfilesexceeded", "maxfilesreached" ],
                b.prototype.defaultOptions = {

                }, c = function() {

                }, b.prototype.init = function() {
                    var a, c, d, e, f, g, h;
                    for ("form" === this.element.tagName && this.element.setAttribute("enctype", "multipart/form-data"),
                    this.element.classList.contains("dropzone") && !this.element.querySelector(".dz-message") && this.element.appendChild(b.createElement('<div class="dz-default dz-message"><span>' + this.options.dictDefaultMessage + "</span></div>")),
                    this.clickableElements.length && (d = function(a) {
                        return function() {
                            return a.hiddenFileInput && document.body.removeChild(a.hiddenFileInput), a.hiddenFileInput = document.createElement("input"),
                            a.hiddenFileInput.setAttribute("type", "file"), (null == a.options.maxFiles || a.options.maxFiles > 1) && a.hiddenFileInput.setAttribute("multiple", "multiple"),
                            a.hiddenFileInput.className = "dz-hidden-input", null != a.options.acceptedFiles && a.hiddenFileInput.setAttribute("accept", a.options.acceptedFiles),
                            null != a.options.capture && a.hiddenFileInput.setAttribute("capture", a.options.capture),
                            a.hiddenFileInput.style.visibility = "hidden", a.hiddenFileInput.style.position = "absolute",
                            a.hiddenFileInput.style.top = "0", a.hiddenFileInput.style.left = "0", a.hiddenFileInput.style.height = "0",
                            a.hiddenFileInput.style.width = "0", document.body.appendChild(a.hiddenFileInput),
                            a.hiddenFileInput.addEventListener("change", function() {
                                var b, c, e, f;
                                if (c = a.hiddenFileInput.files, c.length) for (e = 0, f = c.length; f > e; e++) b = c[e],
                                a.addFile(b);
                                return d();
                            });
                        };
                    }(this))(), this.URL = null != (g = window.URL) ? g : window.webkitURL, h = this.events,
                    e = 0, f = h.length; f > e; e++) a = h[e], this.on(a, this.options[a]);
                    return this.on("uploadprogress", function(a) {
                        return function() {
                            return a.updateTotalUploadProgress();
                        };
                    }(this)), this.on("removedfile", function(a) {
                        return function() {
                            return a.updateTotalUploadProgress();
                        };
                    }(this)), this.on("canceled", function(a) {
                        return function(b) {
                            return a.emit("complete", b);
                        };
                    }(this)), this.on("complete", function(a) {
                        return function(b) {
                            return 0 === a.getUploadingFiles().length && 0 === a.getQueuedFiles().length ? setTimeout(function() {
                                return a.emit("queuecomplete");
                            }, 0) : void 0;
                        };
                    }(this)), c = function(a) {
                        return a.stopPropagation(), a.preventDefault ? a.preventDefault() : a.returnValue = !1;
                    }, this.listeners = [ {
                        element: this.element,

                    } ], this.clickableElements.forEach(function(a) {
                        return function(c) {
                            return a.listeners.push({
                                element: c,
                                events: {
                                    click: function(d) {
                                        return c !== a.element || d.target === a.element || b.elementInside(d.target, a.element.querySelector(".dz-message")) ? a.hiddenFileInput.click() : void 0;
                                    }
                                }
                            });
                        };
                    }(this)), this.enable(), this.options.init.call(this);
                }, b.prototype.destroy = function() {
                    var a;
                    return this.disable(), this.removeAllFiles(!0), (null != (a = this.hiddenFileInput) ? a.parentNode : void 0) && (this.hiddenFileInput.parentNode.removeChild(this.hiddenFileInput),
                    this.hiddenFileInput = null), delete this.element.dropzone, b.instances.splice(b.instances.indexOf(this), 1);
                }, b.prototype.updateTotalUploadProgress = function() {
                    var a, b, c, d, e, f, g, h;
                    if (d = 0, c = 0, a = this.getActiveFiles(), a.length) {
                        for (h = this.getActiveFiles(), f = 0, g = h.length; g > f; f++) b = h[f], d += b.upload.bytesSent,
                        c += b.upload.total;
                        e = 100 * d / c;
                    } else e = 100;
                    return this.emit("totaluploadprogress", e, c, d);
                }, b.prototype._getParamName = function(a) {
                    return "function" == typeof this.options.paramName ? this.options.paramName(a) : "" + this.options.paramName + (this.options.uploadMultiple ? "[" + a + "]" : "");
                }, b.prototype.getFallbackForm = function() {
                    var a, c, d, e;
                    return (a = this.getExistingFallback()) ? a : (d = '<div class="dz-fallback">',
                    this.options.dictFallbackText && (d += "<p>" + this.options.dictFallbackText + "</p>"),
                    d += '<input type="file" name="' + this._getParamName(0) + '" ' + (this.options.uploadMultiple ? 'multiple="multiple"' : void 0) + ' /><input type="submit" value="Upload!"></div>',
                    c = b.createElement(d), "FORM" !== this.element.tagName ? (e = b.createElement('<form action="' + this.options.url + '" enctype="multipart/form-data" method="' + this.options.method + '"></form>'),
                    e.appendChild(c)) : (this.element.setAttribute("enctype", "multipart/form-data"),
                    this.element.setAttribute("method", this.options.method)), null != e ? e : c);
                }, b.prototype.getExistingFallback = function() {
                    var a, b, c, d, e, f;
                    for (b = function(a) {
                        var b, c, d;
                        for (c = 0, d = a.length; d > c; c++) if (b = a[c], /(^| )fallback($| )/.test(b.className)) return b;
                    }, f = [ "div", "form" ], d = 0, e = f.length; e > d; d++) if (c = f[d], a = b(this.element.getElementsByTagName(c))) return a;
                }, b.prototype.setupEventListeners = function() {
                    var a, b, c, d, e, f, g;
                    for (f = this.listeners, g = [], d = 0, e = f.length; e > d; d++) a = f[d], g.push(function() {
                        var d, e;
                        d = a.events, e = [];
                        for (b in d) c = d[b], e.push(a.element.addEventListener(b, c, !1));
                        return e;
                    }());
                    return g;
                }, b.prototype.removeEventListeners = function() {
                    var a, b, c, d, e, f, g;
                    for (f = this.listeners, g = [], d = 0, e = f.length; e > d; d++) a = f[d], g.push(function() {
                        var d, e;
                        d = a.events, e = [];
                        for (b in d) c = d[b], e.push(a.element.removeEventListener(b, c, !1));
                        return e;
                    }());
                    return g;
                }, b.prototype.disable = function() {
                    var a, b, c, d, e;
                    for (this.clickableElements.forEach(function(a) {
                        return a.classList.remove("dz-clickable");
                    }), this.removeEventListeners(), d = this.files, e = [], b = 0, c = d.length; c > b; b++) a = d[b],
                    e.push(this.cancelUpload(a));
                    return e;
                }, b.prototype.enable = function() {
                    return this.clickableElements.forEach(function(a) {
                        return a.classList.add("dz-clickable");
                    }), this.setupEventListeners();
                }, b.prototype.filesize = function(a) {
                    var b;
                    return a >= 109951162777.6 ? (a /= 109951162777.6, b = "TiB") : a >= 107374182.4 ? (a /= 107374182.4,
                    b = "GiB") : a >= 104857.6 ? (a /= 104857.6, b = "MiB") : a >= 102.4 ? (a /= 102.4,
                    b = "KiB") : (a = 10 * a, b = "b"), "<strong>" + Math.round(a) / 10 + "</strong> " + b;
                }, b.prototype._updateMaxFilesReachedClass = function() {
                    return null != this.options.maxFiles && this.getAcceptedFiles().length >= this.options.maxFiles ? (this.getAcceptedFiles().length === this.options.maxFiles && this.emit("maxfilesreached", this.files),
                    this.element.classList.add("dz-max-files-reached")) : this.element.classList.remove("dz-max-files-reached");
                }, b.prototype.drop = function(a) {
                    var b, c;
                    a.dataTransfer && (this.emit("drop", a), b = a.dataTransfer.files, b.length && (c = a.dataTransfer.items,
                    c && c.length && null != c[0].webkitGetAsEntry ? this._addFilesFromItems(c) : this.handleFiles(b)));
                }, b.prototype.paste = function(a) {
                    var b, c;
                    if (null != (null != a && null != (c = a.clipboardData) ? c.items : void 0)) return this.emit("paste", a),
                    b = a.clipboardData.items, b.length ? this._addFilesFromItems(b) : void 0;
                }, b.prototype.handleFiles = function(a) {
                    var b, c, d, e;
                    for (e = [], c = 0, d = a.length; d > c; c++) b = a[c], e.push(this.addFile(b));
                    return e;
                }, b.prototype._addFilesFromItems = function(a) {
                    var b, c, d, e, f;
                    for (f = [], d = 0, e = a.length; e > d; d++) c = a[d], null != c.webkitGetAsEntry && (b = c.webkitGetAsEntry()) ? b.isFile ? f.push(this.addFile(c.getAsFile())) : b.isDirectory ? f.push(this._addFilesFromDirectory(b, b.name)) : f.push(void 0) : null != c.getAsFile && (null == c.kind || "file" === c.kind) ? f.push(this.addFile(c.getAsFile())) : f.push(void 0);
                    return f;
                }, b.prototype._addFilesFromDirectory = function(a, b) {
                    var c, d;
                    return c = a.createReader(), d = function(a) {
                        return function(c) {
                            var d, e, f;
                            for (e = 0, f = c.length; f > e; e++) d = c[e], d.isFile ? d.file(function(c) {
                                return a.options.ignoreHiddenFiles && "." === c.name.substring(0, 1) ? void 0 : (c.fullPath = "" + b + "/" + c.name,
                                a.addFile(c));
                            }) : d.isDirectory && a._addFilesFromDirectory(d, "" + b + "/" + d.name);
                        };
                    }(this), c.readEntries(d, function(a) {
                        return "undefined" != typeof console && null !== console && "function" == typeof console.log ? console.log(a) : void 0;
                    });
                }, b.prototype.accept = function(a, c) {
                    return a.size > 1024 * this.options.maxFilesize * 1024 ? c(this.options.dictFileTooBig.replace("{{filesize}}", Math.round(a.size / 1024 / 10.24) / 100).replace("{{maxFilesize}}", this.options.maxFilesize)) : b.isValidFile(a, this.options.acceptedFiles) ? null != this.options.maxFiles && this.getAcceptedFiles().length >= this.options.maxFiles ? (c(this.options.dictMaxFilesExceeded.replace("{{maxFiles}}", this.options.maxFiles)),
                    this.emit("maxfilesexceeded", a)) : this.options.accept.call(this, a, c) : c(this.options.dictInvalidFileType);
                }, b.prototype.addFile = function(a) {
                    return a.upload = {
                        progress: 0,
                        total: a.size,
                        bytesSent: 0
                    }, this.files.push(a), a.status = b.ADDED, this.emit("addedfile", a), this._enqueueThumbnail(a),
                    this.accept(a, function(b) {
                        return function(c) {
                            return c ? (a.accepted = !1, b._errorProcessing([ a ], c)) : (a.accepted = !0, b.options.autoQueue && b.enqueueFile(a)),
                            b._updateMaxFilesReachedClass();
                        };
                    }(this));
                }, b.prototype.enqueueFiles = function(a) {
                    var b, c, d;
                    for (c = 0, d = a.length; d > c; c++) b = a[c], this.enqueueFile(b);
                    return null;
                }, b.prototype.enqueueFile = function(a) {
                    if (a.status !== b.ADDED || a.accepted !== !0) throw new Error("This file can't be queued because it has already been processed or was rejected.");
                    return a.status = b.QUEUED, this.options.autoProcessQueue ? setTimeout(function(a) {
                        return function() {
                            return a.processQueue();
                        };
                    }(this), 0) : void 0;
                }, b.prototype._thumbnailQueue = [], b.prototype._processingThumbnail = !1, b.prototype._enqueueThumbnail = function(a) {
                    return this.options.createImageThumbnails && a.type.match(/image.*/) && a.size <= 1024 * this.options.maxThumbnailFilesize * 1024 ? (this._thumbnailQueue.push(a),
                    setTimeout(function(a) {
                        return function() {
                            return a._processThumbnailQueue();
                        };
                    }(this), 0)) : void 0;
                }, b.prototype._processThumbnailQueue = function() {
                    return this._processingThumbnail || 0 === this._thumbnailQueue.length ? void 0 : (this._processingThumbnail = !0,
                    this.createThumbnail(this._thumbnailQueue.shift(), function(a) {
                        return function() {
                            return a._processingThumbnail = !1, a._processThumbnailQueue();
                        };
                    }(this)));
                }, b.prototype.removeFile = function(a) {
                    return a.status === b.UPLOADING && this.cancelUpload(a), this.files = j(this.files, a),
                    this.emit("removedfile", a), 0 === this.files.length ? this.emit("reset") : void 0;
                }, b.prototype.removeAllFiles = function(a) {
                    var c, d, e, f;
                    for (null == a && (a = !1), f = this.files.slice(), d = 0, e = f.length; e > d; d++) c = f[d],
                    (c.status !== b.UPLOADING || a) && this.removeFile(c);
                    return null;
                }, b.prototype.createThumbnail = function(a, b) {
                    var c;
                    return c = new FileReader(), c.onload = function(d) {
                        return function() {
                            var e;
                            return "image/svg+xml" === a.type ? (d.emit("thumbnail", a, c.result), void (null != b && b())) : (e = document.createElement("img"),
                            e.onload = function() {
                                var c, f, g, i, j, k, l, m;
                                return a.width = e.width, a.height = e.height, g = d.options.resize.call(d, a),
                                null == g.trgWidth && (g.trgWidth = g.optWidth), null == g.trgHeight && (g.trgHeight = g.optHeight),
                                c = document.createElement("canvas"), f = c.getContext("2d"), c.width = g.trgWidth,
                                c.height = g.trgHeight, h(f, e, null != (j = g.srcX) ? j : 0, null != (k = g.srcY) ? k : 0, g.srcWidth, g.srcHeight, null != (l = g.trgX) ? l : 0, null != (m = g.trgY) ? m : 0, g.trgWidth, g.trgHeight),
                                i = c.toDataURL("image/png"), d.emit("thumbnail", a, i), null != b ? b() : void 0;
                            }, e.src = c.result);
                        };
                    }(this), c.readAsDataURL(a);
                }, b.prototype.processQueue = function() {
                    var a, b, c, d;
                    if (b = this.options.parallelUploads, c = this.getUploadingFiles().length, a = c,
                    !(c >= b) && (d = this.getQueuedFiles(), d.length > 0)) {
                        if (this.options.uploadMultiple) return this.processFiles(d.slice(0, b - c));
                        for (;b > a; ) {
                            if (!d.length) return;
                            this.processFile(d.shift()), a++;
                        }
                    }
                }, b.prototype.processFile = function(a) {
                    return this.processFiles([ a ]);
                }, b.prototype.processFiles = function(a) {
                    var c, d, e;
                    for (d = 0, e = a.length; e > d; d++) c = a[d], c.processing = !0, c.status = b.UPLOADING,
                    this.emit("processing", c);
                    return this.options.uploadMultiple && this.emit("processingmultiple", a), this.uploadFiles(a);
                }, b.prototype._getFilesWithXhr = function(a) {
                    var b, c;
                    return c = function() {
                        var c, d, e, f;
                        for (e = this.files, f = [], c = 0, d = e.length; d > c; c++) b = e[c], b.xhr === a && f.push(b);
                        return f;
                    }.call(this);
                }, b.prototype.cancelUpload = function(a) {
                    var c, d, e, f, g, h, i;
                    if (a.status === b.UPLOADING) {
                        for (d = this._getFilesWithXhr(a.xhr), e = 0, g = d.length; g > e; e++) c = d[e],
                        c.status = b.CANCELED;
                        for (a.xhr.abort(), f = 0, h = d.length; h > f; f++) c = d[f], this.emit("canceled", c);
                        this.options.uploadMultiple && this.emit("canceledmultiple", d);
                    } else ((i = a.status) === b.ADDED || i === b.QUEUED) && (a.status = b.CANCELED,
                    this.emit("canceled", a), this.options.uploadMultiple && this.emit("canceledmultiple", [ a ]));
                    return this.options.autoProcessQueue ? this.processQueue() : void 0;
                }, b.prototype.uploadFile = function(a) {
                    return this.uploadFiles([ a ]);
                }, b.prototype.uploadFiles = function(a) {
                    var d, e, f, g, h, i, j, k, l, m, n, o, p, q, r, s, t, u, v, w, x, y, z, A, B, C, D, E, F, G, H, I;
                    for (t = new XMLHttpRequest(), u = 0, y = a.length; y > u; u++) d = a[u], d.xhr = t;
                    t.open(this.options.method, this.options.url, !0), t.withCredentials = !!this.options.withCredentials,
                    q = null, f = function(b) {
                        return function() {
                            var c, e, f;
                            for (f = [], c = 0, e = a.length; e > c; c++) d = a[c], f.push(b._errorProcessing(a, q || b.options.dictResponseError.replace("{{statusCode}}", t.status), t));
                            return f;
                        };
                    }(this), r = function(b) {
                        return function(c) {
                            var e, f, g, h, i, j, k, l, m;
                            if (null != c) for (f = 100 * c.loaded / c.total, g = 0, j = a.length; j > g; g++) d = a[g],
                            d.upload = {
                                progress: f,
                                total: c.total,
                                bytesSent: c.loaded
                            }; else {
                                for (e = !0, f = 100, h = 0, k = a.length; k > h; h++) d = a[h], (100 !== d.upload.progress || d.upload.bytesSent !== d.upload.total) && (e = !1),
                                d.upload.progress = f, d.upload.bytesSent = d.upload.total;
                                if (e) return;
                            }
                            for (m = [], i = 0, l = a.length; l > i; i++) d = a[i], m.push(b.emit("uploadprogress", d, f, d.upload.bytesSent));
                            return m;
                        };
                    }(this), t.onload = function(c) {
                        return function(d) {
                            var e;
                            if (a[0].status !== b.CANCELED && 4 === t.readyState) {
                                if (q = t.responseText, t.getResponseHeader("content-type") && ~t.getResponseHeader("content-type").indexOf("application/json")) try {
                                    q = JSON.parse(q);
                                } catch (g) {
                                    d = g, q = "Invalid JSON response from server.";
                                }
                                return r(), 200 <= (e = t.status) && 300 > e ? c._finished(a, q, d) : f();
                            }
                        };
                    }(this), t.onerror = function(c) {
                        return function() {
                            return a[0].status !== b.CANCELED ? f() : void 0;
                        };
                    }(this), p = null != (D = t.upload) ? D : t, p.onprogress = r, i = {
                        Accept: "application/json",
                        "Cache-Control": "no-cache",
                        "X-Requested-With": "XMLHttpRequest"
                    }, this.options.headers && c(i, this.options.headers);
                    for (g in i) h = i[g], t.setRequestHeader(g, h);
                    if (e = new FormData(), this.options.params) {
                        E = this.options.params;
                        for (n in E) s = E[n], e.append(n, s);
                    }
                    for (v = 0, z = a.length; z > v; v++) d = a[v], this.emit("sending", d, t, e);
                    if (this.options.uploadMultiple && this.emit("sendingmultiple", a, t, e), "FORM" === this.element.tagName) for (F = this.element.querySelectorAll("input, textarea, select, button"),
                    w = 0, A = F.length; A > w; w++) if (k = F[w], l = k.getAttribute("name"), m = k.getAttribute("type"),
                    "SELECT" === k.tagName && k.hasAttribute("multiple")) for (G = k.options, x = 0,
                    B = G.length; B > x; x++) o = G[x], o.selected && e.append(l, o.value); else (!m || "checkbox" !== (H = m.toLowerCase()) && "radio" !== H || k.checked) && e.append(l, k.value);
                    for (j = C = 0, I = a.length - 1; I >= 0 ? I >= C : C >= I; j = I >= 0 ? ++C : --C) e.append(this._getParamName(j), a[j], a[j].name);
                    return t.send(e);
                }, b.prototype._finished = function(a, c, d) {
                    var e, f, g;
                    for (f = 0, g = a.length; g > f; f++) e = a[f], e.status = b.SUCCESS, this.emit("success", e, c, d),
                    this.emit("complete", e);
                    return this.options.uploadMultiple && (this.emit("successmultiple", a, c, d), this.emit("completemultiple", a)),
                    this.options.autoProcessQueue ? this.processQueue() : void 0;
                }, b.prototype._errorProcessing = function(a, c, d) {
                    var e, f, g;
                    for (f = 0, g = a.length; g > f; f++) e = a[f], e.status = b.ERROR, this.emit("error", e, c, d),
                    this.emit("complete", e);
                    return this.options.uploadMultiple && (this.emit("errormultiple", a, c, d), this.emit("completemultiple", a)),
                    this.options.autoProcessQueue ? this.processQueue() : void 0;
                }, b;
            }(d), b.version = "3.11.1", b.options = {}, b.optionsForElement = function(a) {
                return a.getAttribute("id") ? b.options[e(a.getAttribute("id"))] : void 0;
            }, b.instances = [], b.forElement = function(a) {
                if ("string" == typeof a && (a = document.querySelector(a)), null == (null != a ? a.dropzone : void 0)) throw new Error("No Dropzone found for given element. This is probably because you're trying to access it before Dropzone had the time to initialize. Use the `init` option to setup any additional observers on your Dropzone.");
                return a.dropzone;
            }, b.autoDiscover = !0, b.discover = function() {
                var a, c, d, e, f, g;
                for (document.querySelectorAll ? d = document.querySelectorAll(".dropzone") : (d = [],
                a = function(a) {
                    var b, c, e, f;
                    for (f = [], c = 0, e = a.length; e > c; c++) b = a[c], /(^| )dropzone($| )/.test(b.className) ? f.push(d.push(b)) : f.push(void 0);
                    return f;
                }, a(document.getElementsByTagName("div")), a(document.getElementsByTagName("form"))),
                g = [], e = 0, f = d.length; f > e; e++) c = d[e], b.optionsForElement(c) !== !1 ? g.push(new b(c)) : g.push(void 0);
                return g;
            }, b.blacklistedBrowsers = [ /opera.*Macintosh.*version\/12/i ], b.isBrowserSupported = function() {
                var a, c, d, e, f;
                if (a = !0, window.File && window.FileReader && window.FileList && window.Blob && window.FormData && document.querySelector) if ("classList" in document.createElement("a")) for (f = b.blacklistedBrowsers,
                d = 0, e = f.length; e > d; d++) c = f[d], c.test(navigator.userAgent) && (a = !1); else a = !1; else a = !1;
                return a;
            }, j = function(a, b) {
                var c, d, e, f;
                for (f = [], d = 0, e = a.length; e > d; d++) c = a[d], c !== b && f.push(c);
                return f;
            }, e = function(a) {
                return a.replace(/[\-_](\w)/g, function(a) {
                    return a.charAt(1).toUpperCase();
                });
            }, b.createElement = function(a) {
                var b;
                return b = document.createElement("div"), b.innerHTML = a, b.childNodes[0];
            }, b.elementInside = function(a, b) {
                if (a === b) return !0;
                for (;a = a.parentNode; ) if (a === b) return !0;
                return !1;
            }, b.getElement = function(a, b) {
                var c;
                if ("string" == typeof a ? c = document.querySelector(a) : null != a.nodeType && (c = a),
                null == c) throw new Error("Invalid `" + b + "` option provided. Please provide a CSS selector or a plain HTML element.");
                return c;
            }, b.getElements = function(a, b) {
                var c, d, e, f, g, h, i, j;
                if (a instanceof Array) {
                    e = [];
                    try {
                        for (f = 0, h = a.length; h > f; f++) d = a[f], e.push(this.getElement(d, b));
                    } catch (k) {
                        c = k, e = null;
                    }
                } else if ("string" == typeof a) for (e = [], j = document.querySelectorAll(a),
                g = 0, i = j.length; i > g; g++) d = j[g], e.push(d); else null != a.nodeType && (e = [ a ]);
                if (null == e || !e.length) throw new Error("Invalid `" + b + "` option provided. Please provide a CSS selector, a plain HTML element or a list of those.");
                return e;
            }, b.confirm = function(a, b, c) {
                return window.confirm(a) ? b() : null != c ? c() : void 0;
            }, b.isValidFile = function(a, b) {
                var c, d, e, f, g;
                if (!b) return !0;
                for (b = b.split(","), d = a.type, c = d.replace(/\/.*$/, ""), f = 0, g = b.length; g > f; f++) if (e = b[f],
                e = e.trim(), "." === e.charAt(0)) {
                    if (-1 !== a.name.toLowerCase().indexOf(e.toLowerCase(), a.name.length - e.length)) return !0;
                } else if (/\/\*$/.test(e)) {
                    if (c === e.replace(/\/.*$/, "")) return !0;
                } else if (d === e) return !0;
                return !1;
            }, "undefined" != typeof jQuery && null !== jQuery && (jQuery.fn.dropzone = function(a) {
                return this.each(function() {
                    return new b(this, a);
                });
            }), "undefined" != typeof c && null !== c ? c.exports = b : window.Dropzone = b,
            b.ADDED = "added", b.QUEUED = "queued", b.ACCEPTED = b.QUEUED, b.UPLOADING = "uploading",
            b.PROCESSING = b.UPLOADING, b.CANCELED = "canceled", b.ERROR = "error", b.SUCCESS = "success",
            g = function(a) {
                var b, c, d, e, f, g, h, i, j, k;
                for (h = a.naturalWidth, g = a.naturalHeight, c = document.createElement("canvas"),
                c.width = 1, c.height = g, d = c.getContext("2d"), d.drawImage(a, 0, 0), e = d.getImageData(0, 0, 1, g).data,
                k = 0, f = g, i = g; i > k; ) b = e[4 * (i - 1) + 3], 0 === b ? f = i : k = i, i = f + k >> 1;
                return j = i / g, 0 === j ? 1 : j;
            }, h = function(a, b, c, d, e, f, h, i, j, k) {
                var l;
                return l = g(b), a.drawImage(b, c, d, e, f, h, i, j, k / l);
            }, f = function(a, b) {
                var c, d, e, f, g, h, i, j, k;
                if (e = !1, k = !0, d = a.document, j = d.documentElement, c = d.addEventListener ? "addEventListener" : "attachEvent",
                i = d.addEventListener ? "removeEventListener" : "detachEvent", h = d.addEventListener ? "" : "on",
                f = function(c) {
                    return "readystatechange" !== c.type || "complete" === d.readyState ? (("load" === c.type ? a : d)[i](h + c.type, f, !1),
                    !e && (e = !0) ? b.call(a, c.type || c) : void 0) : void 0;
                }, g = function() {
                    var a;
                    try {
                        j.doScroll("left");
                    } catch (b) {
                        return a = b, void setTimeout(g, 50);
                    }
                    return f("poll");
                }, "complete" !== d.readyState) {
                    if (d.createEventObject && j.doScroll) {
                        try {
                            k = !a.frameElement;
                        } catch (l) {}
                        k && g();
                    }
                    return d[c](h + "DOMContentLoaded", f, !1), d[c](h + "readystatechange", f, !1),
                    a[c](h + "load", f, !1);
                }
            }, b._autoDiscoverFunction = function() {
                return b.autoDiscover ? b.discover() : void 0;
            }, f(window, b._autoDiscoverFunction);
        }).call(this);
    }), "object" == typeof exports ? module.exports = a("dropzone") : "function" == typeof define && define.amd ? define("Dropzone", [], function() {
        return a("dropzone");
    }) : (this || window).Dropzone = a("dropzone");
}(), !function(a) {
    "use strict";
    function b(b, c) {
        this.itemsArray = [], this.$element = a(b), this.$element.hide(), this.isSelect = "SELECT" === b.tagName,
        this.multiple = this.isSelect && b.hasAttribute("multiple"), this.objectItems = c && c.itemValue,
        this.placeholderText = b.hasAttribute("placeholder") ? this.$element.attr("placeholder") : "",
        this.inputSize = Math.max(1, this.placeholderText.length), this.$container = a('<div class="bootstrap-tagsinput"></div>'),
        this.$input = a('<input type="text" placeholder="' + this.placeholderText + '"/>').appendTo(this.$container),
        this.$element.after(this.$container);
        var d = (this.inputSize < 3 ? 3 : this.inputSize) + "em";
        this.$input.get(0).style.cssText = "width: " + d + " !important;", this.build(c);
    }
    function c(a, b) {
        if ("function" != typeof a[b]) {
            var c = a[b];
            a[b] = function(a) {
                return a[c];
            };
        }
    }
    function d(a, b) {
        if ("function" != typeof a[b]) {
            var c = a[b];
            a[b] = function() {
                return c;
            };
        }
    }
    function e(a) {
        return a ? i.text(a).html() : "";
    }
    function f(a) {
        var b = 0;
        if (document.selection) {
            a.focus();
            var c = document.selection.createRange();
            c.moveStart("character", -a.value.length), b = c.text.length;
        } else (a.selectionStart || "0" == a.selectionStart) && (b = a.selectionStart);
        return b;
    }
    function g(b, c) {
        var d = !1;
        return a.each(c, function(a, c) {
            if ("number" == typeof c && b.which === c) return d = !0, !1;
            if (b.which === c.which) {
                var e = !c.hasOwnProperty("altKey") || b.altKey === c.altKey, f = !c.hasOwnProperty("shiftKey") || b.shiftKey === c.shiftKey, g = !c.hasOwnProperty("ctrlKey") || b.ctrlKey === c.ctrlKey;
                if (e && f && g) return d = !0, !1;
            }
        }), d;
    }
    var h = {
        tagClass: function() {
            return "label label-info";
        },
        itemValue: function(a) {
            return a ? a.toString() : a;
        },
        itemText: function(a) {
            return this.itemValue(a);
        },
        freeInput: !0,
        addOnBlur: !0,
        maxTags: void 0,
        maxChars: void 0,
        confirmKeys: [ 13, 44 ],
        onTagExists: function(a, b) {
            b.hide().fadeIn();
        },
        trimValue: !1,
        allowDuplicates: !1
    };
    b.prototype = {
        constructor: b,
        add: function(b, c) {
            var d = this;
            if (!(d.options.maxTags && d.itemsArray.length >= d.options.maxTags || b !== !1 && !b)) {
                if ("string" == typeof b && d.options.trimValue && (b = a.trim(b)), "object" == typeof b && !d.objectItems) throw "Can't add objects when itemValue option is not set";
                if (!b.toString().match(/^\s*$/)) {
                    if (d.isSelect && !d.multiple && d.itemsArray.length > 0 && d.remove(d.itemsArray[0]),
                    "string" == typeof b && "INPUT" === this.$element[0].tagName) {
                        var f = b.split(",");
                        if (f.length > 1) {
                            for (var g = 0; g < f.length; g++) this.add(f[g], !0);
                            return void (c || d.pushVal());
                        }
                    }
                    var h = d.options.itemValue(b), i = d.options.itemText(b), j = d.options.tagClass(b), k = a.grep(d.itemsArray, function(a) {
                        return d.options.itemValue(a) === h;
                    })[0];
                    if (!k || d.options.allowDuplicates) {
                        if (!(d.items().toString().length + b.length + 1 > d.options.maxInputLength)) {
                            var l = a.Event("beforeItemAdd", {
                                item: b,
                                cancel: !1
                            });
                            if (d.$element.trigger(l), !l.cancel) {
                                d.itemsArray.push(b);
                                var m = a('<span class="tag ' + e(j) + '">' + e(i) + '<span data-role="remove"></span></span>');
                                if (m.data("item", b), d.findInputWrapper().before(m), m.after(" "), d.isSelect && !a('option[value="' + encodeURIComponent(h) + '"]', d.$element)[0]) {
                                    var n = a("<option selected>" + e(i) + "</option>");
                                    n.data("item", b), n.attr("value", h), d.$element.append(n);
                                }
                                c || d.pushVal(), (d.options.maxTags === d.itemsArray.length || d.items().toString().length === d.options.maxInputLength) && d.$container.addClass("bootstrap-tagsinput-max"),
                                d.$element.trigger(a.Event("itemAdded", {
                                    item: b
                                }));
                            }
                        }
                    } else if (d.options.onTagExists) {
                        var o = a(".tag", d.$container).filter(function() {
                            return a(this).data("item") === k;
                        });
                        d.options.onTagExists(b, o);
                    }
                }
            }
        },
        remove: function(b, c) {
            var d = this;
            if (d.objectItems && (b = "object" == typeof b ? a.grep(d.itemsArray, function(a) {
                return d.options.itemValue(a) == d.options.itemValue(b);
            }) : a.grep(d.itemsArray, function(a) {
                return d.options.itemValue(a) == b;
            }), b = b[b.length - 1]), b) {
                var e = a.Event("beforeItemRemove", {
                    item: b,
                    cancel: !1
                });
                if (d.$element.trigger(e), e.cancel) return;
                a(".tag", d.$container).filter(function() {
                    return a(this).data("item") === b;
                }).remove(), a("option", d.$element).filter(function() {
                    return a(this).data("item") === b;
                }).remove(), -1 !== a.inArray(b, d.itemsArray) && d.itemsArray.splice(a.inArray(b, d.itemsArray), 1);
            }
            c || d.pushVal(), d.options.maxTags > d.itemsArray.length && d.$container.removeClass("bootstrap-tagsinput-max"),
            d.$element.trigger(a.Event("itemRemoved", {
                item: b
            }));
        },
        removeAll: function() {
            var b = this;
            for (a(".tag", b.$container).remove(), a("option", b.$element).remove(); b.itemsArray.length > 0; ) b.itemsArray.pop();
            b.pushVal();
        },
        refresh: function() {
            var b = this;
            a(".tag", b.$container).each(function() {
                var c = a(this), d = c.data("item"), f = b.options.itemValue(d), g = b.options.itemText(d), h = b.options.tagClass(d);
                if (c.attr("class", null), c.addClass("tag " + e(h)), c.contents().filter(function() {
                    return 3 == this.nodeType;
                })[0].nodeValue = e(g), b.isSelect) {
                    var i = a("option", b.$element).filter(function() {
                        return a(this).data("item") === d;
                    });
                    i.attr("value", f);
                }
            });
        },
        items: function() {
            return this.itemsArray;
        },
        pushVal: function() {
            var b = this, c = a.map(b.items(), function(a) {
                return b.options.itemValue(a).toString();
            });
            b.$element.val(c, !0).trigger("change");
        },
        build: function(b) {
            var e = this;
            if (e.options = a.extend({}, h, b), e.objectItems && (e.options.freeInput = !1),
            c(e.options, "itemValue"), c(e.options, "itemText"), d(e.options, "tagClass"), e.options.typeahead) {
                var i = e.options.typeahead || {};
                d(i, "source"), e.$input.typeahead(a.extend({}, i, {
                    source: function(b, c) {
                        function d(a) {
                            for (var b = [], d = 0; d < a.length; d++) {
                                var g = e.options.itemText(a[d]);
                                f[g] = a[d], b.push(g);
                            }
                            c(b);
                        }
                        this.map = {};
                        var f = this.map, g = i.source(b);
                        a.isFunction(g.success) ? g.success(d) : a.isFunction(g.then) ? g.then(d) : a.when(g).then(d);
                    },
                    updater: function(a) {
                        e.add(this.map[a]);
                    },
                    matcher: function(a) {
                        return -1 !== a.toLowerCase().indexOf(this.query.trim().toLowerCase());
                    },
                    sorter: function(a) {
                        return a.sort();
                    },
                    highlighter: function(a) {
                        var b = new RegExp("(" + this.query + ")", "gi");
                        return a.replace(b, "<strong>$1</strong>");
                    }
                }));
            }
            if (e.options.typeaheadjs) {
                var j = e.options.typeaheadjs || {};
                e.$input.typeahead(null, j).on("typeahead:selected", a.proxy(function(a, b) {
                    e.add(j.valueKey ? b[j.valueKey] : b), e.$input.typeahead("val", "");
                }, e));
            }
            e.$container.on("click", a.proxy(function() {
                e.$element.attr("disabled") || e.$input.removeAttr("disabled"), e.$input.focus();
            }, e)), e.options.addOnBlur && e.options.freeInput && e.$input.on("focusout", a.proxy(function() {
                0 === a(".typeahead, .twitter-typeahead", e.$container).length && (e.add(e.$input.val()),
                e.$input.val(""));
            }, e)), e.$container.on("keydown", "input", a.proxy(function(b) {
                var c = a(b.target), d = e.findInputWrapper();
                if (e.$element.attr("disabled")) return void e.$input.attr("disabled", "disabled");
                switch (b.which) {
                  case 8:
                    if (0 === f(c[0])) {
                        var g = d.prev();
                        g && e.remove(g.data("item"));
                    }
                    break;

                  case 46:
                    if (0 === f(c[0])) {
                        var h = d.next();
                        h && e.remove(h.data("item"));
                    }
                    break;

                  case 37:
                    var i = d.prev();
                    0 === c.val().length && i[0] && (i.before(d), c.focus());
                    break;

                  case 39:
                    var j = d.next();
                    0 === c.val().length && j[0] && (j.after(d), c.focus());
                }
                var k = c.val().length;
                Math.ceil(k / 5), c.attr("size", Math.max(this.inputSize, c.val().length));
            }, e)), e.$container.on("keypress", "input", a.proxy(function(b) {
                var c = a(b.target);
                if (e.$element.attr("disabled")) return void e.$input.attr("disabled", "disabled");
                var d = c.val(), f = e.options.maxChars && d.length >= e.options.maxChars;
                e.options.freeInput && (g(b, e.options.confirmKeys) || f) && (e.add(f ? d.substr(0, e.options.maxChars) : d),
                c.val(""), b.preventDefault());
                var h = c.val().length;
                Math.ceil(h / 5), c.attr("size", Math.max(this.inputSize, c.val().length));
            }, e)), e.$container.on("click", "[data-role=remove]", a.proxy(function(b) {
                e.$element.attr("disabled") || e.remove(a(b.target).closest(".tag").data("item"));
            }, e)), e.options.itemValue === h.itemValue && ("INPUT" === e.$element[0].tagName ? e.add(e.$element.val()) : a("option", e.$element).each(function() {
                e.add(a(this).attr("value"), !0);
            }));
        },
        destroy: function() {
            var a = this;
            a.$container.off("keypress", "input"), a.$container.off("click", "[role=remove]"),
            a.$container.remove(), a.$element.removeData("tagsinput"), a.$element.show();
        },
        focus: function() {
            this.$input.focus();
        },
        input: function() {
            return this.$input;
        },
        findInputWrapper: function() {
            for (var b = this.$input[0], c = this.$container[0]; b && b.parentNode !== c; ) b = b.parentNode;
            return a(b);
        }
    }, a.fn.tagsinput = function(c, d) {
        var e = [];
        return this.each(function() {
            var f = a(this).data("tagsinput");
            if (f) if (c || d) {
                if (void 0 !== f[c]) {
                    var g = f[c](d);
                    void 0 !== g && e.push(g);
                }
            } else e.push(f); else f = new b(this, c), a(this).data("tagsinput", f), e.push(f),
            "SELECT" === this.tagName && a("option", a(this)).attr("selected", "selected"),
            a(this).val(a(this).val());
        }), "string" == typeof c ? e.length > 1 ? e : e[0] : e;
    }, a.fn.tagsinput.Constructor = b;
    var i = a("<div />");
    a(function() {
        a("input[data-role=tagsinput], select[multiple][data-role=tagsinput]").tagsinput();
    });
}(window.jQuery), function(a, b) {
    "use strict";
    "undefined" != typeof module && module.exports ? module.exports = b(require("jquery")(a)) : "function" == typeof define && define.amd ? define("bootstrap3-typeahead", [ "jquery" ], function(a) {
        return b(a);
    }) : b(a.jQuery);
}(this, function(a) {
    "use strict";
    var b = function(b, c) {
        this.$element = a(b), this.options = a.extend({}, a.fn.typeahead.defaults, c), this.matcher = this.options.matcher || this.matcher,
        this.sorter = this.options.sorter || this.sorter, this.select = this.options.select || this.select,
        this.autoSelect = "boolean" == typeof this.options.autoSelect ? this.options.autoSelect : !0,
        this.highlighter = this.options.highlighter || this.highlighter, this.render = this.options.render || this.render,
        this.updater = this.options.updater || this.updater, this.source = this.options.source,
        this.delay = "number" == typeof this.options.delay ? this.options.delay : 250, this.$menu = a(this.options.menu),
        this.shown = !1, this.listen(), this.showHintOnFocus = "boolean" == typeof this.options.showHintOnFocus ? this.options.showHintOnFocus : !1;
    };
    b.prototype = {
        constructor: b,
        select: function() {
            var a = this.$menu.find(".active").data("value");
            return (this.autoSelect || a) && this.$element.val(this.updater(a)).change(), this.hide();
        },
        updater: function(a) {
            return a;
        },
        setSource: function(a) {
            this.source = a;
        },
        show: function() {
            var b, c = a.extend({}, this.$element.position(), {
                height: this.$element[0].offsetHeight
            });
            return b = "function" == typeof this.options.scrollHeight ? this.options.scrollHeight.call() : this.options.scrollHeight,
            this.$menu.insertAfter(this.$element).css({
                top: c.top + c.height + b,
                left: c.left
            }).show(), this.shown = !0, this;
        },
        hide: function() {
            return this.$menu.hide(), this.shown = !1, this;
        },
        lookup: function(b) {
            var c;
            if ("undefined" != typeof b && null !== b ? this.query = b : this.query = this.$element.val() || "",
            this.query.length < this.options.minLength && !this.showHintOnFocus) return this.shown ? this.hide() : this;
            var d = a.proxy(function() {
                c = a.isFunction(this.source) ? this.source(this.query, a.proxy(this.process, this)) : this.source,
                c && this.process(c);
            }, this);
            clearTimeout(this.lookupWorker), this.lookupWorker = setTimeout(d, this.delay);
        },
        process: function(b) {
            var c = this;
            return b = a.grep(b, function(a) {
                return c.matcher(a);
            }), b = this.sorter(b), b.length ? "all" == this.options.items ? this.render(b).show() : this.render(b.slice(0, this.options.items)).show() : this.shown ? this.hide() : this;
        },
        matcher: function(a) {
            return ~a.toLowerCase().indexOf(this.query.toLowerCase());
        },
        sorter: function(a) {
            for (var b, c = [], d = [], e = []; b = a.shift(); ) b.toLowerCase().indexOf(this.query.toLowerCase()) ? ~b.indexOf(this.query) ? d.push(b) : e.push(b) : c.push(b);
            return c.concat(d, e);
        },
        highlighter: function(b) {
            var c, d, e, f, g, h = a("<div></div>"), i = this.query, j = b.indexOf(i);
            if (c = i.length, 0 == c) return h.text(b).html();
            for (;j > -1; ) d = b.substr(0, j), e = b.substr(j, c), f = b.substr(j + c), g = a("<strong></strong>").text(e),
            h.append(document.createTextNode(d)).append(g), b = f, j = b.indexOf(i);
            return h.append(document.createTextNode(b)).html();
        },
        render: function(b) {
            var c = this;
            return b = a(b).map(function(b, d) {
                return b = a(c.options.item).data("value", d), b.find("a").html(c.highlighter(d)),
                b[0];
            }), this.autoSelect && b.first().addClass("active"), this.$menu.html(b), this;
        },
        next: function(b) {
            var c = this.$menu.find(".active").removeClass("active"), d = c.next();
            d.length || (d = a(this.$menu.find("li")[0])), d.addClass("active");
        },
        prev: function(a) {
            var b = this.$menu.find(".active").removeClass("active"), c = b.prev();
            c.length || (c = this.$menu.find("li").last()), c.addClass("active");
        },
        listen: function() {
            this.$element.on("focus", a.proxy(this.focus, this)).on("blur", a.proxy(this.blur, this)).on("keypress", a.proxy(this.keypress, this)).on("keyup", a.proxy(this.keyup, this)),
            this.eventSupported("keydown") && this.$element.on("keydown", a.proxy(this.keydown, this)),
            this.$menu.on("click", a.proxy(this.click, this)).on("mouseenter", "li", a.proxy(this.mouseenter, this)).on("mouseleave", "li", a.proxy(this.mouseleave, this));
        },
        destroy: function() {
            this.$element.data("typeahead", null), this.$element.off("focus").off("blur").off("keypress").off("keyup"),
            this.eventSupported("keydown") && this.$element.off("keydown"), this.$menu.remove();
        },
        eventSupported: function(a) {
            var b = a in this.$element;
            return b || (this.$element.setAttribute(a, "return;"), b = "function" == typeof this.$element[a]),
            b;
        },
        move: function(a) {
            if (this.shown) {
                switch (a.keyCode) {
                  case 9:
                  case 13:
                  case 27:
                    a.preventDefault();
                    break;

                  case 38:
                    a.preventDefault(), this.prev();
                    break;

                  case 40:
                    a.preventDefault(), this.next();
                }
                a.stopPropagation();
            }
        },
        keydown: function(b) {
            this.suppressKeyPressRepeat = ~a.inArray(b.keyCode, [ 40, 38, 9, 13, 27 ]), this.shown || 40 != b.keyCode ? this.move(b) : this.lookup("");
        },
        keypress: function(a) {
            this.suppressKeyPressRepeat || this.move(a);
        },
        keyup: function(a) {
            switch (a.keyCode) {
              case 40:
              case 38:
              case 16:
              case 17:
              case 18:
                break;

              case 9:
              case 13:
                if (!this.shown) return;
                this.select();
                break;

              case 27:
                if (!this.shown) return;
                this.hide();
                break;

              default:
                this.lookup();
            }
            a.stopPropagation(), a.preventDefault();
        },
        focus: function(a) {
            this.focused || (this.focused = !0, (0 === this.options.minLength && !this.$element.val() || this.options.showHintOnFocus) && this.lookup());
        },
        blur: function(a) {
            this.focused = !1, !this.mousedover && this.shown && this.hide();
        },
        click: function(a) {
            a.stopPropagation(), a.preventDefault(), this.select(), this.$element.focus();
        },
        mouseenter: function(b) {
            this.mousedover = !0, this.$menu.find(".active").removeClass("active"), a(b.currentTarget).addClass("active");
        },
        mouseleave: function(a) {
            this.mousedover = !1, !this.focused && this.shown && this.hide();
        }
    };
    var c = a.fn.typeahead;
    a.fn.typeahead = function(c) {
        var d = arguments;
        return this.each(function() {
            var e = a(this), f = e.data("typeahead"), g = "object" == typeof c && c;
            f || e.data("typeahead", f = new b(this, g)), "string" == typeof c && (d.length > 1 ? f[c].apply(f, Array.prototype.slice.call(d, 1)) : f[c]());
        });
    }, a.fn.typeahead.defaults = {
        source: [],
        items: 8,
        menu: '<ul class="typeahead dropdown-menu"></ul>',
        item: '<li><a href="#"></a></li>',
        minLength: 1,
        scrollHeight: 0,
        autoSelect: !0
    }, a.fn.typeahead.Constructor = b, a.fn.typeahead.noConflict = function() {
        return a.fn.typeahead = c, this;
    }, a(document).on("focus.typeahead.data-api", '[data-provide="typeahead"]', function(b) {
        var c = a(this);
        c.data("typeahead") || c.typeahead(c.data());
    });
}), $(function() {
    function a() {
        if (0 != k) {
            b = !0;
            var a = $("#title").val(), c = $("#category-input").val(), d = $("#monthly-theme-id").val(), f = $("#language_code").val(), h = $("#share-by-url").val(), i = $("#group-id").val(), j = $("#group-post-privacy").val();
            if ("undefined" != typeof g) var l = g.getValue();
            if ($("#encrypted_id").length > 0) var m = $("#encrypted_id").val(); else var m = "";
            e && "" != l && "" != a && (k = !1, $.ajax({
                type: "POST",
                url: baseURL + "/posts/autoSaveDraft",
                data: {
                    encrypted_id: m,
                    title: a,
                    category: c,
                    content: l,
                    monthly_theme_id: d,
                    language_code: f,
                    share_by_url: h,
                    group_id: i,
                    privacy_flag: j,
                    autoSaveRunning: !0
                },
                success: function(a) {
                    $("#encrypted_id").val(a.encrypted_id),
                    a.saved === !0 && $.notify({
                        message: "Auto saved at " + a.saved_time
                    }), k = !0, e = !1, b = !1;
                },
                error: function() {
                    k = !0, b = !1;
                }
            }).done(function() {
                k = !0, e = !1, b = !1;
            }));
        }
    }
    stock(), $(".display-comment").processText();
    var b = !1, c = {
        buttons: {
            bold: {
                text: "**",
                close: !0
            },
            italic: {
                text: "_",
                close: !0
            },
            heading: {
                text: "\n ### ",
                close: !1
            },
            order_list: {
                text: "- ",
                close: !1
            },
            code: {
                text: "`",
                close: !0
            },
            quote: {
                text: "> ",
                close: !1
            }
        }
    }, d = !1, e = !1;

    var h = $("#language-selector");
    for (var i in supportedLanguages) h.append($("<option>").html(supportedLanguages[i]));
    h.change(function() {
        var a = getSupportedLanguagesIndex(h.val());
        if (-1 !== a) {
            var b = supportedLanguages[a], c = g.getSelection(), d = "```" + b + "\n";
            c ? (c = d + c + "\n```", g.replaceSelection(c)) : g.replaceSelection(d), g.focus(),
            h.val($("#language-selector option:first").val());
        }
    });
    var j = $("#theme-selector");
    j.change(function() {
        var a = $(this).val();
        if (-1 !== editorThemes.indexOf(a)) {
            var b = a.toLowerCase().split(" ").join("-");
            g.setOption("theme", b);
        }
    }), "undefined" != typeof userTheme && $("#theme-selector option").eq(userTheme + 1).prop("selected", !0).trigger("change");
    var k = !0, l = $(".post.create-post").data("is-published");
    if (!l) var m = setInterval(a, 1e4);
    if ($(".btn.btn-detail, .btn.btn-draft").on("click", function() {
        b || ($(this).addClass("disabled"), clearInterval(m));
    }), initLinkTarget(), initTocTree(), $("#menuTocTree a").on("click", function(a) {
        a.preventDefault();
        var b = $(this).attr("href");
        $("html, body").stop().animate({
            scrollTop: $(b).offset().top - 100
        }, 900, "swing", function() {
            window.location.hash = b;
        }), console.log($(b).offset().top);
    }), null != $("#category-input").val() && initCategoryInput(), null != $("#email-input").val() && initEmailInput(), null != $("#image-uploader").val()) {

    }
    if ($.isFunction($.fn.dropzone) && $(".thumbnail-uploader").dropzone({
        url: baseURL + "/images/upload",
        maxFilesize: MAX_IMAGE_SIZE,
        paramName: "image",
        acceptedFiles: "image/*",
        headers: {
            "X-CSRF-Token": $('meta[name="_token"]').attr("content")
        },
        previewTemplate: '<div style="display:none"></div>',
        init: function() {
            this.on("success", function(a, b) {
                "![" + b.original_name + "](" + b.url + ") \n";
                $("#thumbnail").val(b.url), $(".thumb-preview").css("background", "url('" + b.url + "') center"),
                $(".thumb-preview").css("background-size", "cover"), $(".remove-thumb").addClass("btn-remove-thumb glyphicon glyphicon-remove-sign"),
                a.previewElement.addEventListener("click", function() {});
            }), this.on("error", function(a, b) {
                var c = b;
                "undefined" != typeof b.status && "error" === b.status && (c = b.message), swal(c);
            });
        }
    }), $("#delete-post").click(function() {}), loadMoreUserStock(), "undefined" != typeof ref) {
    }
    $(".post-content img, .comment-content img").each(function() {

    }), $(".markdownContent").processText(), processImageInPost(), $(".show-clipped-users").click(function() {
        $.ajax({
            type: "GET",
            url: baseURL + "/posts/getListUserStockModal",
            data: {
                postId: post_id
            },
            success: function(a) {
                a.result ? ($("#modal-list-stocked-users").empty().append(a.modal), $("#modalUserStock").modal("show")) : swal({
                    title: errorLabel,
                    text: errorMsg,
                    type: "error"
                });
            }
        });
    });
    $("#post_filter").change(function() {
        var a = $(this).val();
    }), $("#seeMorePost").click(function() {
        var a = r.data("message");
        r.attr("disabled", "disabled").html(a), $("#seeMorePost").removeClass("origin-load-more"),
        $.ajax({
            type: "GET",
            data: {
                pageCount: p,
                wall: wall,
                filterBy: q,
                lang: seoLang
            },
        });
    }), $(".themes-in-month").on("change", "#monthly-theme-id", function() {
        $("#hidden-theme-id").val($(this).val());
    }), $(".helpful-question .helpful-button").click(function() {
        var a = $(this).data("helpful");
        $.ajax({
            type: "POST",
            data: {
                post_id: post_id,
                helpful: a
            },
        });
    });
    var s = $("#hidden-theme-id").val();
    "undefined" != typeof s && getMonthlyThemes(s), initShareButton(), $(window).scroll(function() {
        initShareButton();
    }), $(".bootstrap-tagsinput input").attr("style", "width: 16em !important;"), $(".join-this-group").click(function(a) {
        var b = $(this), c = b.data("id"), d = b.data("flag");
        $.ajax({
            type: "POST",
            url: baseURL + "/groups/join",
            data: {
                groupId: c,
                joinFlag: d
            },
        });
    });
});
