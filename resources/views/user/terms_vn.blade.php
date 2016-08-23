{{ HTML::style('css_min/user_terms.min.css') }}
{{ HTML::style('https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,300,600,700&amp;subset=latin,vietnamese') }}
{{ HTML::style('css/terms.css') }}

<title>{{ isset($title) ? $title : Config::get('app.app_name') }}</title>
<div class="container">
<div class="page-header"><h1>Terms of Service</h1> (In <a href="/terms/en">English</a>) (In <a href="/terms/jp">Japanese</a>)</div>
    <div class="row">
        <div class="col-sm-4">
            <ul class="list-unstyled js-toc">
                <li><a href="#1">Điều 1 (Giới thiệu)</a></li>
                <li><a href="#2">Điều 2 (Đăng ký sử dụng)</a></li>
                <li><a href="#3">Điều 3 (Định nghĩa người dùng và đăng ký)</a></li>
                <li><a href="#4">Điều 4 (Tên người dùng và mật khẩu)</a></li>
                <li><a href="#5">Điều 5 (Tước bỏ tư cách người dùng)</a></li>
                <li><a href="#6">Điều 6 (Sự riêng tư)</a></li>
                <li><a href="#7">Điều 7 (Các mục Cấm)</a></li>
                <li><a href="#8">Điều 8 (Sử dụng nội dung bài viết của người dùng)</a></li>
                <li><a href="#9">Điều 9 (Sử dụng các thông tin về người dùng)</a></li>
                <li><a href="#10">Điều 10 (Dịch vụ bên ngoài)</a></li>
                <li><a href="#11">Điều 11 (Thay đổi hoặc ngừng cung cấp dịch vụ)</a></li>
                <li><a href="#12">Điều 12 (Kết thúc dịch vụ từ phía người dùng)</a></li>
                <li><a href="#13">Điều 13 (Xử lý sau khi kết thúc hợp đồng)</a></li>
                <li><a href="#14">Điều 14 (Câu hỏi điều tra)</a></li>
                <li><a href="#15">Điều 15 (Phân tích và hiển thị quảng quảng cáo)</a></li>
                <li><a href="#16">Điều 16 (Quyền sở hữu của chúng tôi)</a></li>
                <li><a href="#17">Điều 17 (Từ chối các trách nhiệm)</a></li>
                <li><a href="#18">Điều 18 (Thay đổi điều khoản)</a></li>
                <li><a href="#19">Điều 19 (Phụ lục)</a></li>
            </ul>
        </div>
        <div class="col-sm-8">
            <div class="markdownContent js-tosContent">
                <h2><span class="fragment" id="1"></span>Điều 1 (Giới thiệu)</h2>
                <p>
                    Viblo là sản phẩm được cung cấp bởi Framgia, Inc. Viblo là platform nhằm thúc đẩy việc chia sẻ kỹ thuật, cũng như cộng đồng kỹ sư công nghệ thông tin, giúp mọi người nâng cao được năng lực kỹ thuật của mình.
                </p>
                <ol>
                    <li>
                        Dưới đây là phần Điều Khoản Sử Dụng. Khi bạn sử dụng Viblo có nghĩa là bạn đồng ý với những "Điều Khoản Sử Dụng Dịch Vụ" (từ dưới đây sẽ gọi tắt là Điều Khoản Sử Dụng) này.
                    </li>
                    <li>
                        Những Điều Khoản Sử Dụng này là những điều kiện liên quan đến việc sử dụng Viblo (từ dưới đây sẽ gọi là Dịch Vụ này), sản phẩm được cung cấp bởi Framgia, Inc (từ dưới đây sẽ gọi là Công Ty chúng tôi). Nó sẽ xác định quan hệ giữa khách hàng sử dụng dịch vụ (từ dưới đây sẽ gọi là Người Dùng) với Công Ty.
                    </li>
                    <li>
                        Người dùng sử dụng dịch vụ sau khi đã đồng ý với những Điều Khoản Sử Dụng này.
                    </li>
                    <li>
                        Công Ty Chúng Tôi có thể sửa đội nội dung Điều Khoản Sử Dụng này mà không cần thông báo trước đến người dùng. Về những thay đổi của Điều Khoản Sử Dụng này thì nó được ưu tiên hơn so với những Quy Định trong quá khứ, và những Điều Khoản được đăng trên dịch vụ sẽ là những điều có hiệu lực.
                    </li>
                </ol>
                <h2><span class="fragment" id="2"></span>Điều 2 (Đăng ký sử dụng)</h2>
                <ol>
                    <li>
                        Những người mong muốn sử dụng Dịch Vụ này (từ dưới đây sẽ gọi tắt là Người mong muốn sử dụng dịch vụ), trước khi đi vào sử dụng cần phải Đăng Ký Sử Dụng Dịch vụ. Người mong muốn sử dụng dịch vụ cần phải tạo cho mình một Viblo ID (dưới đây sẽ gọi là ID Người Dùng) và một Mật Khẩu tương ứng. 
                    </li>
                    <li>
                        Người mong muốn sử dụng dịch vụ khi đăng ký sử dụng dịch vụ này thì cần phải cung cấp một số thông tin người dùng mà phía công ty chúng tôi yêu cầu. Người mong muốn sử dụng dịch vụ cần phải đồng ý với những điều sau liên quan đến thông tin người dùng.
                    </li>
                    <li>
                        Người dùng sau khi đăng ký thông tin người dùng xong thì vẫn phải đảm bảo rằng thông tin người dùng luôn ở trong tình trạng hoàn chỉnh, chính xác, và mới nhất. Khi phát sinh thay đổi trong thông tin người dùng, thì người dùng cần phải nhanh chóng thực hiện các thủ tục thay đổi mà dịch vụ cung cấp. Nếu thông tin người dùng là không chính xác thì chúng tôi có thể tạm dừng việc cho phép người dùng sử dụng dịch vụ, hoặc là tước bỏ tư cách người dùng mà không cần thông báo trước. 
                    </li>
                    <li>
                        Trong trường hợp mà người dùng bị tước bỏ tư cách ở dịch vụ này, và ID Người Dùng đó còn đang được sử dụng cho các dịch vụ khác mà công ty chúng tôi cung cấp thì tư cách sử dụng dịch vụ ở các dịch vụ kia cũng sẽ bị tước bỏ.
                    </li>
                </ol>
                <h2><span class="fragment" id="3"></span>Điều 3 (Định nghĩa người dùng và đăng ký)</h2>
                <ol>
                    <li>
                        Người Dùng của dịch vụ này bao gồm người dùng đăng ký, và Khách vãng lai.
                    </li>
                    <li>
                        Người dùng đăng ký là những cá nhân đã điền đầy đủ thông tin cần thiết vào phần đăng ký người dùng rồi gửi về dịch vụ, sau đó được sự thừa nhận từ phía công ty chúng tôi. Người dùng đăng ký bao gồm cả những cá nhân sử dụng những dịch vụ chứng nhận bên ngoài được công ty chúng tôi đồng ý như Facebook ... 
                    </li>
                    <li>
                        Khách vãng lai là những cá nhân vào xem, hay những người không thực hiện việc đăng ký sử dụng đã đề cập ở trên, và chỉ sử dụng những dịch vụ có thể sử dụng được.
                    </li>
                </ol>
                <h2><span class="fragment" id="4"></span>Điều 4 (Tên người dùng và mật khẩu)</h2>
                <ol>
                    <li>
                        Người dùng đã thực hiện việc đăng ký sử dụng sẽ có một tài khoản bao gồm ID người dùng và mật khẩu đăng nhập. Người dùng có trách nhiệm quản lý ID và mật khẩu của cá nhân mình.
                    </li>
                    <li>
                        Hành động đăng nhập bằng ID người dùng và mật khẩu được cho là được thực hiện từ phía người dùng sở hữu ID đó.
                    </li>
                    <li>
                        Mỗi người dùng đăng ký chỉ được phép sở hữu 1 account duy nhất. Trừ những trường hợp đặc biệt được công ty chúng tôi cho phép.
                    </li>
                    <li>
                        Để có được ID người dùng và mật khẩu đăng nhập thì địa chỉ email đăng ký, và các thông tin khác cần phải chính xác, và không vi phạm pháp luật.
                    </li>
                    <li>
                        ID người dùng cũng như mật khẩu đăng nhập là thuộc sở hữu cá nhân, và không được phép cho mượn, hay chuyển nhượng dưới mọi hình thức.
                    </li>
                    <li>
                        Trong trường hợp ID người dùng và mật khẩu đăng nhập bị bên thứ 3 lấy được, hoặc bị bên thứ 3 sử dụng thì hãy liên lạc với công ty chúng tôi ngay. Ngoài ra, chúng tôi không chịu bất cứ trách nhiệm gì hay sẽ không bồi thường gì cho những tổn thất hay thiệt hại được gây ra bởi sơ suất của người dùng dẫn đến ID hay mật khẩu bất chính bị người khác sử dụng. 
                    </li>
                    <li>
                        Trong trường hợp người dùng sử dụng ID của dịch vụ bên ngoài để đăng ký dịch vụ này, thì những điều liên quan đến đăng ký ID hay những thứ khác của dịch vụ bên ngoài đó đều phải tuân theo những quy định mà dịch vụ bên ngoài họ đặt ra. Công ty chúng tôi không có bất kỳ trách nhiệm nào đối với những nội dung đó. 
                    </li>
                </ol>
                <h2><span class="fragment" id="5"></span>Điều 5 (Tước bỏ tư cách người dùng)</h2>
                    <ol>
                        <li>Trong trường hợp chứng mình được những lý do sau thì dựa theo đánh giá của công ty chúng mà tôi tư cách người dùng có thể bị hủy bỏ. 
                            <ul class="parenthesis">
                                <li>
                                    <span>(1)</span>Những nội dung được điền vào lúc đăng ký là không chính xác.
                                </li>
                                <li>
                                    <span>(2)</span>ID người dùng và mật khẩu đăng nhập được sử dụng một cách bất chính.
                                </li>
                                <li>
                                    <span>(3)</span>Sử dụng những thông tin mà dịch vụ này cung cấp một cách bất chính.
                                </li>
                                <li>
                                    <span>(4)</span>Sử dụng ID và mật khẩu đăng nhập cho những dịch vụ khác có thể sử dụng được mà công ty cung cấp, và bị hủy bỏ tư cách sử dụng ở những dịch vụ đó.
                                </li>
                                <li>
                                    <span>(5)</span>Có hành vi trái với điều khoản sử dụng này.
                                </li>
                                <li>
                                    <span>(6)</span>Ngoài ra, khi mà công ty chúng tôi nhận định rằng người dùng không phù hợp với việc sử dụng dịch vụ này.
                                </li>
                            </ul>
                        </li>
                        <li>
                            Trong trường hợp người dùng bị hủy bỏ tư cách ở dịch vù này, thì nếu ID người dùng đó đang được sử dụng ở những dịch vụ khác mà công ty chúng tôi cung cấp, thì ở những dịch vụ đó, tư cách sử dụng cũng sẽ bị hủy bỏ. 
                        </li>
                    </ol>
                <h2><span class="fragment" id="6"></span>Điều 6 (Sự riêng tư)</h2>
                    <p>
                        Trong quá trình cung cấp dịch vụ này thì công ty chúng tôi cần phải có được những thông tin từ phía người dùng ở mức độ ít nhất có thể. Công ty chúng tôi luôn chú trọng tối đa vào việc bảo vệ thông tin cá nhân thu thập được từ người dùng.
                    </p>
                <h2><span class="fragment" id="7"></span>Điều 7 (Các mục Cấm)</h2>
                <ol>
                    <li>Người dùng trong quá trình sử dụng dịch vụ thì không được phép thực hiện những hành vi trái với pháp luật như sau.
                        <ul class="parenthesis">
                            <li>
                                <span>(1)</span>Hành vi xâm hại đến quyền sở hữu trí tuệ của quyền tác giả, quyền sáng chế ...
                            </li>
                            <li>
                                <span>(2)</span>Hành vi xâm hại đến quyền riêng tư cá nhân 
                            </li>
                            <li>
                                <span>(3)</span>Hành vi phỉ bán, xúc phạm danh dự người khác, hay những hành vi cản trở công việc của người khác.
                            </li>
                            <li>
                                <span>(4)</span>Hành vi lừa đảo 
                            </li>
                            <li>
                                <span>(5)</span>Ngoài ra, những hành vi phạm tội, hay những hành vi trái với quy định của pháp luật.
                            </li>
                        </ul>
                    </li>
                    <li>Người dùng trong quá trình sử dụng dịch vụ thì không được phép thực hiện những hành vi không phù hợp với quy tắc xã hội như sau.
                        <ul class="parenthesis">
                            <li>
                                <span>(1)</span>Hành vi báo trước tội ác, hành vi hướng dẫn phạm tội, hay những hành động có khả năng dẫn đến phạm tội.
                            </li>
                            <li>
                                <span>(2)</span>Hành vi phân biệt chủng tộc, dân tộc, tín ngưỡng, giới tính, xuất thân xã hội, nơi cư trú, đặc điểm ngoại hình, bệnh tật, giáo dục, tài sản hay thu nhập ...
                            </li>
                            <li>
                                <span>(3)</span>Hành vi mang tính thô tục. Hành vi đăng tải những nội dung gây ác cảm đến người khác. Những hành vi đăng tải nội dung liên quan đến những vấn đề khiêu dâm, mại dâm ...
                            </li>
                            <li>
                                <span>(4)</span>Hành vi gây ra sự phiền hà, khó chịu với người khác, hành vi phỉ báng người khác. Hành vi gây ra tổn thất về tinh thần hay kinh tế đến người khác khi mà không có quyền thích đáng.
                            </li>
                            <li>
                                <span>(5)</span>Hành vi sử dụng tên của người khác, hay của công ty, đoàn thể khác, sử dụng tên của công ty, đoàn thể mà không có quyền, sử dụng tên cá nhân, tên công ty, đoàn thể không có thực, hay giả mạo rằng có quan hệ cộng tác với người, công ty, đoàn thể khác.
                            </li>
                            <li>
                                <span>(6)</span>Hành vi giả mạo người khác để sử dụng dịch vụ, xuyên tạc thông tin.
                            </li>
                            <li>
                                <span>(7)</span>Những hành vi khác, trái với đạo lý xã hội và trật tự công cộng.
                            </li>
                        </ul>
                    </li>
                    <li>Người dùng trong quá trình sử dụng dịch vụ thì không được phép thực hiện những hành vi không phù hợp việc sử dụng dịch vụ như sau.
                        <ul class="parenthesis">
                            <li>
                                <span>(1)</span>Hành vi quảng cáo, tuyên truyền với mục đích thương mại. Tuy nhiên trong một số trường hợp đặc biệt được công ty chúng tôi cho phép thì có thể thực hiện những điều này.
                            </li>
                            <li>
                                <span>(2)</span>Hành vi chia sẻ ID người dùng. Một người sở hữu nhiều tài khoản khi không được sự cho phép của công ty chúng tôi. Mặc dù được sự đồng ý của công ty, nhưng một người sở hữu nhiều tài khoản hơn so với số lượng mà công ty cho phép.
                            </li>
                            <li>
                                <span>(3)</span>Hành vi sử dụng ID người dùng gây khó chịu và hiểu nhầm cho người khác. Hành vi sử dụng ID người dùng giống, hoặc gần giống với tên những công ty, đoàn thể hay sản phẩm nổi tiếng được công ty chúng tôi hay nhiều người khác biết đến.
                            </li>
                            <li>
                                <span>(4)</span>Hành vi thu thập, đánh cắp, lưu trữ, chỉnh sửa, sử dụng thông tin cá nhân. Hành vi thay đổi thông tin cá nhân, thông tin người dùng của bản thân một cách bất chính.
                            </li>
                            <li>
                                <span>(5)</span>Hành vi làm ẩn đi những thành phần cơ bản của dịch vụ như Header, Footer, Quảng cáo, hay các ký hiệu Bản quyền tác giả ... mà không được sự cho phép của công ty chúng tôi.
                            </li>
                            <li>
                                <span>(6)</span>Hành vi buôn bán, chuyển nhượng dịch vụ mà không được sự đồng ý của công ty chúng tôi.
                            </li>
                        </ul>
                    </li>
                    <li>
                        Người dùng, ngoài những điều được kể trên ra, thì cũng không được phép thực hiện những hành động được công ty chúng tôi đánh giá là không thích hợp.
                    </li>
                </ol>
                <h2><span class="fragment" id="8"></span>Điều 8 (Sử dụng nội dung bài viết của người dùng)</h2>
                <ol>
                    <li>
                        Người dùng phải đảm bảo và thể hiện rõ rằng những dữ liệu dạng text như bài viết, comment, thông tin cá nhân công khai, hay giới thiệu bản thân, hay những dữ liệu đính kèm với phần text như hình ảnh, file âm thanh, file video (dưới đây sẽ gọi gộp lại là Nội dung đăng tải) mà mình tự đăng tải lên là những nội dung mà mình quyền hợp pháp với chúng, và không xâm hạn đến quyền của người thứ ba.
                    </li>
                    <li>
                        Người dùng cho phép công ty chúng tôi có toàn quyền sử dụng đối với nội dung đăng tải của mình (phục chế, lưu giữ, chỉnh sửa, trao quyền cho bên thứ ba hay những quyền sử dụng khác ...). Phạm vi của việc cho phép công ty chúng tôi quyền sử dụng nội dung đăng tải có bao gồm cả việc công ty chúng tôi cho phép người dùng khác sử dụng nội dung đăng tải thông qua dịch vụ này. Đặc biệt, những nội dung mà người dùng đăng tải có liên quan đến programming, chẳng hạn như source code, thì đều được cấp phép sử dụng cho những người dùng khác, bất kể nó có liên quan đến thương mại hay không, và mọi người dùng đều có thể sử dụng nó. Do đó, người dùng có thể sử dụng các phương pháp được công ty chúng tôi quy định để biên tập, chỉnh sửa, phục chế nội dung đăng tải của người dùng khác. Thế nhưng trong trường hợp source code được ghi License rõ ràng thì cần phải tuân theo nội dung của License đó.
                    </li>
                    <li>
                        Về phần cho phép sử dụng đề cập ở mục trước, nếu không có vấn đề gì về bổn phận hiển thị tác quyển hoặc các điều kiện đi kèm hoặc giới hạn về vị trí địa lý, thì quyền sử dụng mà người dùng giao cho công ty chúng tôi có thời hạn kéo dài cả đến sau khi người dùng bị tước bỏ tư cách sử dụng, và có hiệu lực chừng nào mà quyền sở hữu tài sản trí tuệ của nội dung đăng tải còn được duy trì.
                    </li>
                    <li>
                        Công ty chúng tôi, hoặc là bên thứ ba được công ty chúng tôi ủy thác có thể đưa nội dung đăng tải lên website của công ty chúng tôi hoặc bên thứ ba. Trong trường hợp đó có thể chúng tôi sẽ có những hành động như tóm tắt, trích dẫn, thay đổi kích thước, hoặc cắt bỏ một phần đối với nội dung đăng tải. Ngoài ra, người dùng cũng công nhận việc trong trường hợp chúng tôi sử dụng nội dung đăng tải thì có thể chúng tôi sẽ hiển thị ID người dùng, hay những thông tin đăng ký người của người dùng tại thời điểm mà người dùng gửi bài.
                    </li>
                    <li>
                        Công ty chúng tôi hoàn toàn không chịu trách nhiệm về việc những thiệt hại phát sinh cho người dùng trong quá trình công ty, người dùng, hoặc bên thứ ba sử dụng nội dung đăng tải.
                    </li>
                    <li>
                        Công ty chúng tôi có quyền tự do lưu trữ nội dung đăng tải. Trong trường hợp cần thiết, chúng tôi không cần đến sự đồng ý của người đăng tải mà vẫn có thể chỉnh sửa sửa hay xóa nội dung đăng tải. Người dùng không được có chút khiếu nại gì về vấn đề này.
                    </li>
                </ol>
                <h2><span class="fragment" id="9"></span>Điều 9 (Sử dụng các thông tin về người dùng)</h2>
                <p>
                    Về những thông tin người dùng nhập vào lúc đăng ký, nhưng không có tác dụng trong việc xác định xem người dùng đó là ai thì dựa vào quyết định của công ty mà chúng tôi có thể tự do sử dụng, hoặc hiển thị cho bên thứ ba.
                </p>
                <h2><span class="fragment" id="10"></span>Điều 10 (Dịch vụ bên ngoài)</h2>
                <ol>
                    <li>
                        Dịch vụ này có liên kết với các dịch vụ bên ngoài khác như Facebook, Github ... thế nhưng người dùng cần phải tự có trách nhiệm với việc sử dụng dịch vụ bên ngoài kia. Chúng tôi hoàn toàn không chịu bất cứ trách nhiệm gì trong việc có vấn đề phát sinh đến người dùng liên quan đến việc sử dụng những dịch vụ bên ngoài.
                    </li>
                    <li>
                        Người dùng trong quá trình sử dụng dịch vụ bên ngoài cần phải tuân thủ những quy tắc sử dụng của các dịch vụ bên ngoài đó.
                    </li>
                    <li>
                        Người dùng đồng ý với việc rằng trong trường hợp người dùng cho phép thì những nội dung đăng tải hay những thông tin khác có sẻ sẽ được đưa lên dịch vụ bên ngoài. Ngoài ra, chúng tôi cũng không có nghĩa vụ phải thực hiện việc xóa những nội dung đăng tải được đưa lên các dịch vụ bên ngoài.
                    </li>
                </ol>
                <h2><span class="fragment" id="11"></span>Điều 11 (Thay đổi hoặc ngừng cung cấp dịch vụ)</h2>
                <ol>
                    <li>
                        Công ty chúng tôi có thể thay đổi nội dung của của dịch vụ mà không cần báo trước đến người dùng. Người dùng cần đồng ý với việc đó. Và kể cả trong trường hợp những thay đổi mang đến những điều không có ích lợi, hay tổn hại đến cho người dùng thì chũng tôi cũng hoàn toàn không chịu trách nhiệm gì về việc đó.
                    </li>
                    <li>
                        Công ty chúng tôi có thể tạm dừng hoặc hủy bỏ dịch vụ sau khi đã báo trước đến người dùng trong khoảng thời gian tối thiểu là 10 ngày. Việc tạm dừng, hay hủy bỏ dịch vụ được thông báo trước trên trang web này, và sau khi đã thực hiện đủ những thủ tục đó, trong trường hợp chúng tôi tạm dừng hay hủy bỏ dịch vụ thì chúng tôi cũng sẽ hoàn toàn không chịu bất kỳ trách nhiệm gì trong việc bồi thường đến người dùng.
                    </li>
                </ol>
                <h2><span class="fragment" id="12"></span>Điều 12 (Kết thúc dịch vụ từ phía người dùng)</h2>
                <ol>
                    <li>
                        Người dùng, không quan trọng là vì lý do gì, có quyền được kết thúc việc sử dụng dịch vụ. Trong trường hợp đó, người dùng cần tuân theo những thủ tục mà chúng tôi cung cấp, để truyền đạt điều đó đến với chúng tôi.
                    </li>
                    <li>
                        Xin hãy lưu ý là ngay cả khi người dùng có khiếu nại về các mục của điều khoản sử dụng hoặc những thay đổi mà người dùng thấy bất thường trong điều khoản sử dụng này đến công ty chúng tôi chỉ có hiệu lực khi người dùng đã dừng sử dụng dịch vụ.
                    </li>
                </ol>
                <h2><span class="fragment" id="13"></span>Điều 13 (Xử lý sau khi kết thúc hợp đồng)</h2>
                <p>
                    Công ty chúng tôi có quyền sử dụng nội dung các bài viết cùng với thông tin người dùng đã đăng kí, và điều khoản số 8 & số 9 vẫn có hiệu lực ngay cả khi tư cách sử dụng dịch vụ của người dùng bị hủy bỏ vì bất kỳ lý do gì hay việc hủy bỏ chính dịch vụ này hoặc trường hợp người dùng không còn sử dụng dịch vụ này nữa.
                </p>
                <h2><span class="fragment" id="14"></span>Điều 14 (Câu hỏi điều tra)</h2>
                <p>
                    Công ty chúng tôi có thể gửi các bản điều tra ý kiến tới email của người dùng. Ngoài ra, chúng tôi có quyền gửi tới người dùng (bao gôm cả các liên kết ngoại) các thông tin quảng cáo hoặc sản phẩm & dịch vụ cung cấp bởi bên thứ 3 (gọi là [thông tin bổ trợ]). Hơn nữa việc trao đổi thông tin bổ trợ này được thực hiện dựa trên trách nhiệm của người dùng, Chúng tôi không có nghĩa vụ chịu trách nhiệm về các trao đổi với người sử dụng, dựa trên nội dung của các thông tin bổ sung.
                </p>
                <h2><span class="fragment" id="15"></span>Điều 15 (Phân tích và hiển thị quảng quảng cáo)</h2>
                <ol>
                    <li>
                        Công ty chúng tôi thu thập các thông tin sử dụng mang tính ẩn danh dựa trên cookie nhằm phục vụ việc nghiên cứu xu hướng hoạt động của người dùng trên dịch vụ.
                    </li>
                    <li>
                        Công ty chúng tôi có thể cho hiển thị quảng cáo dựa theo quy định từng phần trong điều khoản này trên dịch vụ này và người dùng sẽ phải chấp nhận điều này trước.
                    </li>
                </ol>
                <h2><span class="fragment" id="16"></span>Điều 16 (Quyền sở hữu của chúng tôi)</h2>
                <ol>
                    <li>
                        Chúng tôi giữ quyền sở hữu với các phần mềm, dịch vụ và các thông tin có trong dịch vụ.
                    </li>
                    <li>
                        Tất cả các phần mềm được sử dụng bởi dịch vụ này bao gồm các bí mật thương mại và quyền tài sản được bảo vệ bởi luật pháp liên quan đến quyền sở hữu trí tuệ.
                    </li>
                </ol>
                <h2><span class="fragment" id="17"></span>Điều 17 (Từ chối các trách nhiệm)</h2>
                <ol>
                    <li>
                        Người dùng phải đồng ý một cách rõ ràng với việc sử dụng dịch vụ này dựa trên trách nhiệm của bản thân. Chúng tôi cung cấp dịch vụ này với diều kiện [Phạm vi có thể áp dụng] và [Hoàn cảnh thực tế].
                    </li>
                    <li>Công ty chúng tôi hoàn toàn không chịu trách nhiệm về các nội dung bên dưới.
                        <ul class="parenthesis">
                            <li>
                                <span>(1)</span>Nội dung của dịch vụ mang lại lợi ích hoặc đáp ứng nhu cầu của người sử dụng
                            </li>
                            <li>
                                <span>(2)</span>Sự chính xác và thích hợp trong bài viết của người sử dụng trên dịch vụ này
                            </li>
                            <li>
                                <span>(3)</span>Sự gián đoạn, tạm ngưng hoặc hủy bỏ hoạt động của dịch vụ này
                            </li>
                            <li>
                                <span>(4)</span>Dịch vụ này được cung cấp đúng thời hạn
                            </li>
                            <li>
                                <span>(5)</span>Tính an toàn của dịch vụ
                            </li>
                            <li>
                                <span>(6)</span>Sẽ không phát sinh bất kỳ lỗi nào trong dịch vụ
                            </li>
                            <li>
                                <span>(7)</span>Các thông tin mà người dùng thu được từ dịch vụ này là chính xác, hợp pháp, phụ hợp với thuần phong mỹ tục, đảm bảo tính mới nhất, thích hợp hoặc có thể tin cậy được 
                            </li>
                            <li>
                                <span>(8)</span>Dịch vụ có thể có thiếu sót
                            </li>
                            <li>
                                <span>(9)</span>Hành vi mà người dùng sử dụng dịch vụ để đáp ứng mục đích cá nhân
                            </li>
                            <li>
                                <span>(10)</span>Sự không mất mát nội dung bài viết được đăng hoặc thông tin người sử dung được đăng ký thông qua dịch vụ này
                            </li>
                        </ul>
                    </li>
                    <li>
                        Công ty chúng tôi không chịu trách nhiệm về bất kỳ sự việc nào: khi người dùng sử dụng dịch vụ với trách nhiệm cá nhân mình mà có sự phát sinh hoặc liên quan tới việc tổn thất mất mát dữ liệu trên các thiết bị truyền thông liên lạc như máy tính, ...
                    </li>
                    <li>
                        Công ty chúng tôi không chịu trách nhiệm về việc có hay không hành vi của một người dùng đã sử dụng dịch vụ này để đáp ứng mục đích cụ thể của người sử dụng (Bao gồm mục đích thương mại cũng như các mục đích khác).
                    </li>
                    <li>
                        Công ty chúng tôi không chịu trách nhiệm về các giao dịch liên quan tới dịch vụ và người dùng sử dụng dịch vụ hoặc các hàng hóa thu được hoặc được mua bằng cách sử dụng các chức năng của dịch vụ này.
                    </li>
                </ol>
                <h2><span class="fragment" id="18"></span>Điều 18 (Thay đổi điều khoản)</h2>
                <p>
                    Tùy vào hoàn cảnh của công ty mà điều khoản và điều kiện này có thể bị sửa đổi. Các sửa đổi sẽ được thông trên website này hoặc theo một cách nhất định bởi công ty. Kể từ sau khi thông báo, nếu người dùng vẫn sử dụng dịch vụ này hoặc là trong khoảng thời gian được chỉ định bởi công ty, dựa trên điều [12] nếu người dùng không kết thúc sử dụng dịch vụ sẽ được coi là đã đồng ý với những sửa đổi đó.
                </p>
                <h2><span class="fragment" id="19"></span>Điều 19 (Phụ lục)</h2>
                <ol>
                    <li>
                        Hiệu lực của điều khoản & điều kiện này được áp dụng từ thời điểm dịch vụ chính thức hoạt động.
                    </li>
                    <li>
                        Các cá nhân đã đồng ý với điều khoản & điều kiện này được coi như đã đồng ý với các điều khoản trước đó.
                    </li>
                </ol>
            </div>
        </div>
    </div>
</div>
<br>
