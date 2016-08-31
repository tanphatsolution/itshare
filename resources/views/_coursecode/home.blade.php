<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>

        <title>Học lập trình web tại Hà Nội | Diễn đàn chia sẻ kiến thức trực tuyến</title>

        <meta name="description" content="Học lập trình web tại Hà Nội. Diễn đàn chia sẻ kiến thức trực tuyến."/>
        <meta name="keywords" content="HTML,CSS,XML,JavaScript,PHP"/>

        {{ HTML::style('coursecode/img/logo.png', ['rel' => 'shortcut icon']) }}

        <!-- Bootstrap CSS, Font-awesome -->
        {{ HTML::style('coursecode/bootstrap/css/bootstrap.min.css') }}
        {{ HTML::style('coursecode/font-awesome/css/font-awesome.min.css') }}
        <!-- Customers -->
        {{ HTML::style('coursecode/customers/styles.css') }}
        {{ HTML::style('coursecode/customers/styles-responsive.css') }}
        {{ HTML::style('coursecode/customers/css/home.css') }}
    </head>

    <body>

        <!-- #menu -->
        <div id="menu">
            <nav class="navbar navbar-default" role="navigation">
                <div class="container">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#menuTop">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="#">
                            <img src="/coursecode/img/logo.png" alt="Logo" style="width: 75px; height: 75px;"/>
                        </a>
                    </div>

                    <!-- .navbar-collapse -->
                    <div class="collapse navbar-collapse" id="menuTop">
                        <ul class="nav navbar-nav navbar-right">
                            <li class="active"><a href="#">Trang chủ</a></li>
                            <li><a href="#">Giới thiệu</a></li>
                            <li><a href="#">Diễn đàn</a></li>
                            <li><a href="#">Liên hệ</a></li>
                            <li><a href="#">Đăng nhập</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>

        <!-- #banner -->
        <div id="banner">
            <div class="jumbotron" style="background: url('/coursecode/img/banner.jpg'); background-size: cover;">
                <h1>Bạn muốn trở thành lập trình viên?</h1>
                <p>Hãy tham gia khóa học của chúng tôi!</p>
                <a href="#" title="" class="btn btn-info btn-lg">Tham gia ngay</a>
            </div>
        </div>

        <!-- #slogan -->
        <div id="slogan" class="text-center">
            <div class="container">
                <div class="col-md-4">
                    <i class="glyphicon glyphicon-share"></i>
                    <h3>Itshare.asia</h3>
                    <p>Diễn đàn chia sẻ kiến thức</p>
                </div>
                <div class="col-md-4">
                    <i class="glyphicon glyphicon-question-sign"></i>
                    <h3>Itshare.asia/fqa</h3>
                    <p>Diễn đàn hỏi đáp</p>
                </div>
                <div class="col-md-4">
                    <i class="glyphicon glyphicon-facetime-video"></i>
                    <h3>Youtube.com</h3>
                    <p>Kênh video youtube.com</p>
                </div>
            </div>
        </div>

        <!-- #underSlogan -->
        <div id="underSlogan">
            <h3 class="text-center">Tham gia</h3>
        </div>

        <!-- #content -->
        <div id="content">
            <div class="container">
                <!-- Courses list -->
                <div class="row">
                    <div class="col-md-6">
                        <!-- .course -->
                        <div class="course">
                            <div class="course-img">
                                <a href="javascript:;" title="">
                                    <img src="/coursecode/img/html-course.png" alt="HTML-COURSE" class="img-responsive">
                                </a>
                            </div>
                            <div class="course-info">
                                <h3><a href="#" title="">Html 5</a></h3>
                                <p>Khóa học giúp bạn có những hiểu biết cơ bản, quan trọng
                                về html 5, về cách để tạo ra một trang web. Học tốt html 5
                                giúp cho bạn dễ dàng tiếp cận những ngôn ngữ lập trình sau
                                này. <a href="course.html">chi tiết</a></p>
                            </div>
                            <div class="course-footer">
                                <ul class="list-inline">
                                    <li>
                                        Thời gian<br/>
                                        <span>60 giờ</span>
                                    </li>
                                    <li>
                                        Bài giảng<br/>
                                        <span>100</span>
                                    </li>
                                    <li>
                                        Học phí<br/>
                                        <span>6,000,000</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <!-- .course -->
                        <div class="course">
                            <div class="course-img">
                                <a href="javascript:;" title="">
                                    <img src="/coursecode/img/css-course.png" alt="CSS-COURSE" class="img-responsive">
                                </a>
                            </div>
                            <div class="course-info">
                                <h3><a href="#" title="">CSS 3</a></h3>
                                <p>CSS là một phần không thể thiếu của website. Với CSS, bạn
                                có thể thoải mái điều chỉnh sự hiển thị của những phần tử
                                trên webiste. Điều này giúp trang web của bạn trở lên thật
                                sinh động. <a href="course.html">chi tiết</a></p>
                            </div>
                            <div class="course-footer">
                                <ul class="list-inline">
                                    <li>
                                        Thời gian<br/>
                                        <span>60 giờ</span>
                                    </li>
                                    <li>
                                        Bài giảng<br/>
                                        <span>100</span>
                                    </li>
                                    <li>
                                        Học phí<br/>
                                        <span>6,000,000</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <span class="clearfix"></span>

                    <div class="col-md-6">
                        <!-- .course -->
                        <div class="course">
                            <div class="course-img">
                                <a href="javascript:;" title="">
                                    <img src="/coursecode/img/js-course.png" alt="js-course" class="img-responsive">
                                </a>
                            </div>
                            <div class="course-info">
                                <h3><a href="#" title="">Javascript</a></h3>
                                <p>Javascript là ngôn ngữ "nhẹ", linh động, dùng để
                                xử lý các sự kiện trên trình duyệt của người dùng.
                                Hiện nay, tất cả các trình duyệt web đều hỗ trợ ngôn
                                ngữ này. <a href="course.html">chi tiết</a></p>
                            </div>
                            <div class="course-footer">
                                <ul class="list-inline">
                                    <li>
                                        Thời gian<br/>
                                        <span>60 giờ</span>
                                    </li>
                                    <li>
                                        Bài giảng<br/>
                                        <span>100</span>
                                    </li>
                                    <li>
                                        Học phí<br/>
                                        <span>6,000,000</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <!-- .course -->
                        <div class="course">
                            <div class="course-img">
                                <a href="javascript:;" title="">
                                    <img src="/coursecode/img/jquery-course.jpg" alt="JQUERY-COURSE" class="img-responsive">
                                </a>
                            </div>
                            <div class="course-info">
                                <h3><a href="#" title="">JQuery</a></h3>
                                <p>JQuery là một thư viện được viết ra bởi javascript,
                                tạo ra những hiệu ứng rất đẹp cho website. Hiện nay,
                                rất nhiều website đang sử dụng thư viện này. <a href="course.html">chi tiết</a></p>
                            </div>
                            <div class="course-footer">
                                <ul class="list-inline">
                                    <li>
                                        Thời gian<br/>
                                        <span>60 giờ</span>
                                    </li>
                                    <li>
                                        Bài giảng<br/>
                                        <span>100</span>
                                    </li>
                                    <li>
                                        Học phí<br/>
                                        <span>6,000,000</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <span class="clearfix"></span>

                    <div class="col-md-6">
                        <!-- .course -->
                        <div class="course">
                            <div class="course-img">
                                <a href="javascript:;" title="">
                                    <img src="/coursecode/img/php-course.png" alt="php-course" class="img-responsive">
                                </a>
                            </div>
                            <div class="course-info">
                                <h3><a href="#" title="">PHP Basic</a></h3>
                                <p>Hiện nay, 80% website trên thế giới đang chạy bằng php.
                                Điều này chỉ ra rằng nó vẫn đang là một ngôn ngữ vô cùng phổ
                                biến, được ưa chuộng hiện nay. <a href="course.html">chi tiết</a></p>
                            </div>
                            <div class="course-footer">
                                <ul class="list-inline">
                                    <li>
                                        Thời gian<br/>
                                        <span>60 giờ</span>
                                    </li>
                                    <li>
                                        Bài giảng<br/>
                                        <span>100</span>
                                    </li>
                                    <li>
                                        Học phí<br/>
                                        <span>6,000,000</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <!-- .course -->
                        <div class="course">
                            <div class="course-img">
                                <a href="javascript:;" title="">
                                    <img src="/coursecode/img/php-course.png" alt="php-course" class="img-responsive">
                                </a>
                            </div>
                            <div class="course-info">
                                <h3><a href="#" title="">Php framework</a></h3>
                                <p>Framework là công nghệ không thể thiếu trong lập trình
                                hiện nay. Cùng chúng tôi chinh phục các framework php đẳng
                                cấp để phát triển website của bạn. <a href="course.html">chi tiết</a></p>
                            </div>
                            <div class="course-footer">
                                <ul class="list-inline">
                                    <li>
                                        Thời gian<br/>
                                        <span>60 giờ</span>
                                    </li>
                                    <li>
                                        Bài giảng<br/>
                                        <span>100</span>
                                    </li>
                                    <li>
                                        Học phí<br/>
                                        <span>6,000,000</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <span class="clearfix"></span>
                </div> <!-- /.row -->
            </div> <!-- /.container -->

            <h2 class="category-heading text-center">
                <span class="text-uppercase">Các chủ đề công nghệ mới nhất</span>
            </h2>

            <!-- Categories list -->
            <div class="category bg-white">
                <div class="container">
                    <div class="row">
                        <div class="about col-md-6">
                            <h3 class="title text-uppercase">
                                <a href="#">Ruby</a>
                            </h3>
                            <p class="desc text-jutify">Đừng lo lắng khi gặp khó khăn trong việc lập trình.
                            Viblo là nơi các lập trình viên văn bản hóa
                            kiến thức mà họ tích lũy được thông qua
                            quá trình làm việc thực tế thành các bài viết.
                            Nhất định trong số đó sẽ có lời gợi ý
                            cho vấn đề của bạn.</p>
                        </div>

                        <div class="image col-md-6">
                            <img src="/coursecode/img/tpage-slide01.jpg" alt="" class="img-responsive"/>
                        </div>
                    </div> <!-- /.row -->
                </div> <!-- /.container -->
            </div> <!-- /.category -->

            <div class="category">
                <div class="container">
                    <div class="row">
                        <div class="image col-md-6">
                            <img src="/coursecode/img/tpage-slide02.jpg" alt="" class="img-responsive"/>
                        </div>
                        <div class="about col-md-6">
                            <h3 class="title text-uppercase">
                                <a href="#">Python</a>
                            </h3>
                            <p class="desc text-jutify">Đừng lo lắng khi gặp khó khăn trong việc lập trình.
                            Viblo là nơi các lập trình viên văn bản hóa
                            kiến thức mà họ tích lũy được thông qua
                            quá trình làm việc thực tế thành các bài viết.
                            Nhất định trong số đó sẽ có lời gợi ý
                            cho vấn đề của bạn.</p>
                        </div>
                    </div> <!-- /.row -->
                </div> <!-- /.container -->
            </div> <!-- /.category -->

            <div class="category bg-white">
                <div class="container">
                    <div class="row">
                        <div class="about col-md-6">
                            <h3 class="title text-uppercase">
                                <a href="#">Javascript</a>
                            </h3>
                            <p class="desc text-jutify">Đừng lo lắng khi gặp khó khăn trong việc lập trình.
                            Viblo là nơi các lập trình viên văn bản hóa
                            kiến thức mà họ tích lũy được thông qua
                            quá trình làm việc thực tế thành các bài viết.
                            Nhất định trong số đó sẽ có lời gợi ý
                            cho vấn đề của bạn.</p>
                        </div>

                        <div class="image col-md-6">
                            <img src="/coursecode/img/tpage-slide03.jpg" alt="" class="img-responsive"/>
                        </div>
                    </div> <!-- /.row -->
                </div> <!-- /.container -->
            </div> <!-- /.category -->
        </div> <!-- /#content -->

        <!-- #footer -->
        <div id="footer">
            <div class="container">
                <div class="row">
                    <div class="col-md-7">
                        <div class="row">
                            <div class="col-md-4">
                                <!-- Courses list -->
                                <h4>Khóa học</h4>
                                <ul class="list-unstyled">
                                    <li><a href="javascript:;">Html 5</a></li>
                                    <li><a href="javascript:;">Css 3</a></li>
                                    <li><a href="javascript:;">Javascript</a></li>
                                    <li><a href="javascript:;">Jquery</a></li>
                                    <li><a href="javascript:;">PHP Basic</a></li>
                                    <li><a href="javascript:;">PHP Framework</a></li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <!-- Services -->
                                <h4>Dịch vụ</h4>
                                <ul class="list-unstyled">
                                    <li>Đào tạo lập trình</li>
                                    <li>Thiết kế website</li>
                                    <li>Hỗ trợ thực tập</li>
                                    <li>Domain & Hosting</li>
                                    <li>SEO top Google</li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <!-- Support Online -->
                                <h4>Hỗ trợ trực tuyến</h4>
                                <ul class="list-unstyled">
                                    <li>Phone: <a href="javascript:;">0912 123 123</a></li>
                                    <li>Skype: <a href="javascript:;">suntech@skype</a></li>
                                    <li>Email: <a href="javascript:;">suntech@gmail.com</a></li>

                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <!-- Information -->
                        <h4 style="margin-top: 9px;">SUNTECH.COM.VN</h4>
                        <p style="color: #899b9f;"> <b> Suntech</b> là một trung tâm
                        đào tạo lập trình được thành lập từ năm 2014. Trung tâm tập
                        trung phát triển các khóa học dạy lập trình website bằng ngôn
                        ngữ php. Ngoài ra, trung tâm còn là cầu nối giúp học viên có
                        thể tìm kiếm việc làm phù hợp.</p>
                        <h4>Thông tin chung</h4>
                        <ul class="list-unstyled">
                            <li>Số 15, ngách 15/24, đường Hồ Tùng Mậu, Cầu Giấy, Hà Nội</li>
                            <li>Email: daotaolaptrinhsuntech@gmail.com</li>
                            <li>Phone: 0942 668 586</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div id="footerBottom">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-6">
                            <div style="padding-top: 8px;">
                                <span style="color: #ffffff;">
                                    Copyright &copy; suntech.com.vn - All right reservied
                                </span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="socail-networks text-right">
                                <a href="javascript:;" class="fb">
                                    <i class="fa fa-facebook"></i>
                                </a>
                                <a href="javascript:;" class="tw">
                                    <i class="fa fa-twitter"></i>
                                </a>
                                <a href="javascript:;" class="gp">
                                    <i class="fa fa-google-plus"></i>
                                </a>
                                <a href="javscript:;" class="yt">
                                    <i class="fa fa-youtube"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{ HTML::script('coursecode/customers/js/jquery.js') }}
        {{ HTML::script('coursecode/bootstrap/js/bootstrap.min.js') }}
        {{ HTML::script('coursecode/customers/js/main.js') }}
    </body>
</html>
