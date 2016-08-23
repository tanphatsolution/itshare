@extends('layouts.default')

@section('main') 
<div id="container" class="container">
  <div class="user-profile-detail row">
    <div class="col-md-9 col-lg-9 post-left">
       <div class="user-profile-detail-top">
          <div class="user-profile-avatar"><img src="../img/user-profile-avatar.jpg"></div>
           <div class="user-profile-intro">
             <div class="user-profile-links">
                <button>Follow</button>
                <ul class="user-profile-social">
                   <li><a href="#" class="profile-social-facebook"></a></li>
                   <li><a href="#" class="profile-social-google"></a></li>
                   <li><a href="#" class="profile-social-github"></a></li>
                </ul>
             </div>
             <h2 class="user-profile-name">Nguyen Van Vuong</h2>
             <ul class="user-profile-info-detail">
                <li class="user-profile-job">Web Developer <span>-</span> Framgia Vietnam</li>
                <li class="user-profile-website">&nbsp;&nbsp;<a href="#">http://framgia.com</a></li>
                <li class="user-profile-email no-bg">&nbsp;&nbsp;<a href="#">gmail123@gmail.com</a></li>
                <div class="clr"></div>
             </ul>
             <span class="user-profile-address">Hanoi,Vietnam</span>
             <div class="user-profile-description">
                <p>Hi! It’s me.</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce et nisl mi. Suspendisse blandit quis erat a facilisis. Phasellus vitae egestas quam, id rutrum turpis. Duis quis eleifend neque. Curabitur dictum, nunc nec interdum imperdiet, urna nibh commodo ligula, vel efficitur turpis.</p>
             </div>
           </div>
       </div>
       <div class="user-profile-detail-middle">
          <div class="user-profile-detail-tabs1">
             <a href="#" class="active">Article</a>
             <a href="#">Group</a>
             <a href="#">Q&A</a>
             <a href="#">Others</a>
          </div>
          <div class="user-profile-detail-tabs2">
             <a href="#" class="active">Posts</a>
             <a href="#">My Clips</a>
             <a href="#">Clupped</a>
             <a href="#">Get Helpful</a>
          </div>
          <div class="user-profile-articles">
              <div class="col-lg-12">
                <div class="thumbnail">
                  <div class="box-top">
                    <img src="../img/blank.png" style="background: url(../img/3.jpg) center">
                    <!-- <div class="mask">
                      <a href="top.html">view</a>
                      </div> -->
                    <div class="mini-post-list">
                      <span class="post-view">78</span>
                      <span class="post-com">129</span>
                      <span class="post-favou">10</span>
                    </div>
                  </div>
                  <div class="caption">
                    <div class="item-info">
                      <a class="name-title" href="#">Giới thiệu về mô hình phát triển phần mềm theo phương pháp Agile (scrum)</a>
                      <div class="author">
                        <a href="#">
                        <img src="../img/blank.png" style="background: url(../img/3.jpg) center no-repeat;">
                        <span>Nguyen Van Tien</span>
                        </a>
                        <div class="mini-date">
                          <span>posted on</span>
                          <span class="date">May 11, 2015</span>
                        </div>
                      </div>
                      <p class="detail">Photon Server là một dịch vụ được cung cấp bởi Exitgames nhằm hỗ trợ người phát triển game khai triển server riêng, có khả năng thiết kế luồng hoạt động sao cho phù hợp với từng game riêng</p>
                      <div class="tags">
                        <a class="php" href="#">php</a>
                        <a class="html" href="#">html</a>
                        <a class="ios" href="#">.ios</a>
                        <a class="css3" href="#">css3</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-12">
                <div class="thumbnail">
                  <div class="box-top">
                    <img src="../img/blank.png" style="background: url(../img/3.jpg) center">
                    <!-- <div class="mask">
                      <a href="top.html">view</a>
                      </div> -->
                    <div class="mini-post-list">
                      <span class="post-view">78</span>
                      <span class="post-com">129</span>
                      <span class="post-favou">10</span>
                    </div>
                  </div>
                  <div class="caption">
                    <div class="item-info">
                      <a class="name-title" href="#">Giới thiệu về mô hình phát triển phần mềm theo phương pháp Agile (scrum)</a>
                      <div class="author">
                        <a href="#">
                        <img src="../img/blank.png" style="background: url(../img/3.jpg) center no-repeat;">
                        <span>Nguyen Van Tien</span>
                        </a>
                        <div class="mini-date">
                          <span>posted on</span>
                          <span class="date">May 11, 2015</span>
                        </div>
                      </div>
                      <p class="detail">Photon Server là một dịch vụ được cung cấp bởi Exitgames nhằm hỗ trợ người phát triển game khai triển server riêng, có khả năng thiết kế luồng hoạt động sao cho phù hợp với từng game riêng</p>
                      <div class="tags">
                        <a class="php" href="#">php</a>
                        <a class="html" href="#">html</a>
                        <a class="ios" href="#">.ios</a>
                        <a class="css3" href="#">css3</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-12">
                <div class="thumbnail">
                  <div class="box-top">
                    <img src="../img/blank.png" style="background: url(../img/3.jpg) center">
                    <!-- <div class="mask">
                      <a href="top.html">view</a>
                      </div> -->
                    <div class="mini-post-list">
                      <span class="post-view">78</span>
                      <span class="post-com">129</span>
                      <span class="post-favou">10</span>
                    </div>
                  </div>
                  <div class="caption">
                    <div class="item-info">
                      <a class="name-title" href="#">Giới thiệu về mô hình phát triển phần mềm theo phương pháp Agile (scrum)</a>
                      <div class="author">
                        <a href="#">
                        <img src="../img/blank.png" style="background: url(../img/3.jpg) center no-repeat;">
                        <span>Nguyen Van Tien</span>
                        </a>
                        <div class="mini-date">
                          <span>posted on</span>
                          <span class="date">May 11, 2015</span>
                        </div>
                      </div>
                      <p class="detail">Photon Server là một dịch vụ được cung cấp bởi Exitgames nhằm hỗ trợ người phát triển game khai triển server riêng, có khả năng thiết kế luồng hoạt động sao cho phù hợp với từng game riêng</p>
                      <div class="tags">
                        <a class="php" href="#">php</a>
                        <a class="html" href="#">html</a>
                        <a class="ios" href="#">.ios</a>
                        <a class="css3" href="#">css3</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-12">
                <div class="thumbnail">
                  <div class="box-top">
                    <img src="../img/blank.png" style="background: url(../img/3.jpg) center">
                    <!-- <div class="mask">
                      <a href="top.html">view</a>
                      </div> -->
                    <div class="mini-post-list">
                      <span class="post-view">78</span>
                      <span class="post-com">129</span>
                      <span class="post-favou">10</span>
                    </div>
                  </div>
                  <div class="caption">
                    <div class="item-info">
                      <a class="name-title" href="#">Giới thiệu về mô hình phát triển phần mềm theo phương pháp Agile (scrum)</a>
                      <div class="author">
                        <a href="#">
                        <img src="../img/blank.png" style="background: url(../img/3.jpg) center no-repeat;">
                        <span>Nguyen Van Tien</span>
                        </a>
                        <div class="mini-date">
                          <span>posted on</span>
                          <span class="date">May 11, 2015</span>
                        </div>
                      </div>
                      <p class="detail">Photon Server là một dịch vụ được cung cấp bởi Exitgames nhằm hỗ trợ người phát triển game khai triển server riêng, có khả năng thiết kế luồng hoạt động sao cho phù hợp với từng game riêng</p>
                      <div class="tags">
                        <a class="php" href="#">php</a>
                        <a class="html" href="#">html</a>
                        <a class="ios" href="#">.ios</a>
                        <a class="css3" href="#">css3</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-12">
                <div class="thumbnail">
                  <div class="box-top">
                    <img src="../img/blank.png" style="background: url(../img/3.jpg) center">
                    <!-- <div class="mask">
                      <a href="top.html">view</a>
                      </div> -->
                    <div class="mini-post-list">
                      <span class="post-view">78</span>
                      <span class="post-com">129</span>
                      <span class="post-favou">10</span>
                    </div>
                  </div>
                  <div class="caption">
                    <div class="item-info">
                      <a class="name-title" href="#">Giới thiệu về mô hình phát triển phần mềm theo phương pháp Agile (scrum)</a>
                      <div class="author">
                        <a href="#">
                        <img src="../img/blank.png" style="background: url(../img/3.jpg) center no-repeat;">
                        <span>Nguyen Van Tien</span>
                        </a>
                        <div class="mini-date">
                          <span>posted on</span>
                          <span class="date">May 11, 2015</span>
                        </div>
                      </div>
                      <p class="detail">Photon Server là một dịch vụ được cung cấp bởi Exitgames nhằm hỗ trợ người phát triển game khai triển server riêng, có khả năng thiết kế luồng hoạt động sao cho phù hợp với từng game riêng</p>
                      <div class="tags">
                        <a class="php" href="#">php</a>
                        <a class="html" href="#">html</a>
                        <a class="ios" href="#">.ios</a>
                        <a class="css3" href="#">css3</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-12">
                <div class="thumbnail">
                  <div class="box-top">
                    <img src="../img/blank.png" style="background: url(../img/3.jpg) center">
                    <!-- <div class="mask">
                      <a href="top.html">view</a>
                      </div> -->
                    <div class="mini-post-list">
                      <span class="post-view">78</span>
                      <span class="post-com">129</span>
                      <span class="post-favou">10</span>
                    </div>
                  </div>
                  <div class="caption">
                    <div class="item-info">
                      <a class="name-title" href="#">Giới thiệu về mô hình phát triển phần mềm theo phương pháp Agile (scrum)</a>
                      <div class="author">
                        <a href="#">
                        <img src="../img/blank.png" style="background: url(../img/3.jpg) center no-repeat;">
                        <span>Nguyen Van Tien</span>
                        </a>
                        <div class="mini-date">
                          <span>posted on</span>
                          <span class="date">May 11, 2015</span>
                        </div>
                      </div>
                      <p class="detail">Photon Server là một dịch vụ được cung cấp bởi Exitgames nhằm hỗ trợ người phát triển game khai triển server riêng, có khả năng thiết kế luồng hoạt động sao cho phù hợp với từng game riêng</p>
                      <div class="tags">
                        <a class="php" href="#">php</a>
                        <a class="html" href="#">html</a>
                        <a class="ios" href="#">.ios</a>
                        <a class="css3" href="#">css3</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-12">
                <div class="thumbnail">
                  <div class="box-top">
                    <img src="../img/blank.png" style="background: url(../img/3.jpg) center">
                    <!-- <div class="mask">
                      <a href="top.html">view</a>
                      </div> -->
                    <div class="mini-post-list">
                      <span class="post-view">78</span>
                      <span class="post-com">129</span>
                      <span class="post-favou">10</span>
                    </div>
                  </div>
                  <div class="caption">
                    <div class="item-info">
                      <a class="name-title" href="#">Giới thiệu về mô hình phát triển phần mềm theo phương pháp Agile (scrum)</a>
                      <div class="author">
                        <a href="#">
                        <img src="../img/blank.png" style="background: url(../img/3.jpg) center no-repeat;">
                        <span>Nguyen Van Tien</span>
                        </a>
                        <div class="mini-date">
                          <span>posted on</span>
                          <span class="date">May 11, 2015</span>
                        </div>
                      </div>
                      <p class="detail">Photon Server là một dịch vụ được cung cấp bởi Exitgames nhằm hỗ trợ người phát triển game khai triển server riêng, có khả năng thiết kế luồng hoạt động sao cho phù hợp với từng game riêng</p>
                      <div class="tags">
                        <a class="php" href="#">php</a>
                        <a class="html" href="#">html</a>
                        <a class="ios" href="#">.ios</a>
                        <a class="css3" href="#">css3</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-12">
                <div class="thumbnail">
                  <div class="box-top">
                    <img src="../img/blank.png" style="background: url(../img/3.jpg) center">
                    <!-- <div class="mask">
                      <a href="top.html">view</a>
                      </div> -->
                    <div class="mini-post-list">
                      <span class="post-view">78</span>
                      <span class="post-com">129</span>
                      <span class="post-favou">10</span>
                    </div>
                  </div>
                  <div class="caption">
                    <div class="item-info">
                      <a class="name-title" href="#">Giới thiệu về mô hình phát triển phần mềm theo phương pháp Agile (scrum)</a>
                      <div class="author">
                        <a href="#">
                        <img src="../img/blank.png" style="background: url(../img/3.jpg) center no-repeat;">
                        <span>Nguyen Van Tien</span>
                        </a>
                        <div class="mini-date">
                          <span>posted on</span>
                          <span class="date">May 11, 2015</span>
                        </div>
                      </div>
                      <p class="detail">Photon Server là một dịch vụ được cung cấp bởi Exitgames nhằm hỗ trợ người phát triển game khai triển server riêng, có khả năng thiết kế luồng hoạt động sao cho phù hợp với từng game riêng</p>
                      <div class="tags">
                        <a class="php" href="#">php</a>
                        <a class="html" href="#">html</a>
                        <a class="ios" href="#">.ios</a>
                        <a class="css3" href="#">css3</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-12">
                <div class="thumbnail">
                  <div class="box-top">
                    <img src="../img/blank.png" style="background: url(../img/3.jpg) center">
                    <!-- <div class="mask">
                      <a href="top.html">view</a>
                      </div> -->
                    <div class="mini-post-list">
                      <span class="post-view">78</span>
                      <span class="post-com">129</span>
                      <span class="post-favou">10</span>
                    </div>
                  </div>
                  <div class="caption">
                    <div class="item-info">
                      <a class="name-title" href="#">Giới thiệu về mô hình phát triển phần mềm theo phương pháp Agile (scrum)</a>
                      <div class="author">
                        <a href="#">
                        <img src="../img/blank.png" style="background: url(../img/3.jpg) center no-repeat;">
                        <span>Nguyen Van Tien</span>
                        </a>
                        <div class="mini-date">
                          <span>posted on</span>
                          <span class="date">May 11, 2015</span>
                        </div>
                      </div>
                      <p class="detail">Photon Server là một dịch vụ được cung cấp bởi Exitgames nhằm hỗ trợ người phát triển game khai triển server riêng, có khả năng thiết kế luồng hoạt động sao cho phù hợp với từng game riêng</p>
                      <div class="tags">
                        <a class="php" href="#">php</a>
                        <a class="html" href="#">html</a>
                        <a class="ios" href="#">.ios</a>
                        <a class="css3" href="#">css3</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-12">
                <div class="thumbnail">
                  <div class="box-top">
                    <img src="../img/blank.png" style="background: url(../img/3.jpg) center">
                    <!-- <div class="mask">
                      <a href="top.html">view</a>
                      </div> -->
                    <div class="mini-post-list">
                      <span class="post-view">78</span>
                      <span class="post-com">129</span>
                      <span class="post-favou">10</span>
                    </div>
                  </div>
                  <div class="caption">
                    <div class="item-info">
                      <a class="name-title" href="#">Giới thiệu về mô hình phát triển phần mềm theo phương pháp Agile (scrum)</a>
                      <div class="author">
                        <a href="#">
                        <img src="../img/blank.png" style="background: url(../img/3.jpg) center no-repeat;">
                        <span>Nguyen Van Tien</span>
                        </a>
                        <div class="mini-date">
                          <span>posted on</span>
                          <span class="date">May 11, 2015</span>
                        </div>
                      </div>
                      <p class="detail">Photon Server là một dịch vụ được cung cấp bởi Exitgames nhằm hỗ trợ người phát triển game khai triển server riêng, có khả năng thiết kế luồng hoạt động sao cho phù hợp với từng game riêng</p>
                      <div class="tags">
                        <a class="php" href="#">php</a>
                        <a class="html" href="#">html</a>
                        <a class="ios" href="#">.ios</a>
                        <a class="css3" href="#">css3</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-12">
                <div class="thumbnail">
                  <div class="box-top">
                    <img src="../img/blank.png" style="background: url(../img/3.jpg) center">
                    <!-- <div class="mask">
                      <a href="top.html">view</a>
                      </div> -->
                    <div class="mini-post-list">
                      <span class="post-view">78</span>
                      <span class="post-com">129</span>
                      <span class="post-favou">10</span>
                    </div>
                  </div>
                  <div class="caption">
                    <div class="item-info">
                      <a class="name-title" href="#">Giới thiệu về mô hình phát triển phần mềm theo phương pháp Agile (scrum)</a>
                      <div class="author">
                        <a href="#">
                        <img src="../img/blank.png" style="background: url(../img/3.jpg) center no-repeat;">
                        <span>Nguyen Van Tien</span>
                        </a>
                        <div class="mini-date">
                          <span>posted on</span>
                          <span class="date">May 11, 2015</span>
                        </div>
                      </div>
                      <p class="detail">Photon Server là một dịch vụ được cung cấp bởi Exitgames nhằm hỗ trợ người phát triển game khai triển server riêng, có khả năng thiết kế luồng hoạt động sao cho phù hợp với từng game riêng</p>
                      <div class="tags">
                        <a class="php" href="#">php</a>
                        <a class="html" href="#">html</a>
                        <a class="ios" href="#">.ios</a>
                        <a class="css3" href="#">css3</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-12">
                <div class="thumbnail">
                  <div class="box-top">
                    <img src="../img/blank.png" style="background: url(../img/3.jpg) center">
                    <!-- <div class="mask">
                      <a href="top.html">view</a>
                      </div> -->
                    <div class="mini-post-list">
                      <span class="post-view">78</span>
                      <span class="post-com">129</span>
                      <span class="post-favou">10</span>
                    </div>
                  </div>
                  <div class="caption">
                    <div class="item-info">
                      <a class="name-title" href="#">Giới thiệu về mô hình phát triển phần mềm theo phương pháp Agile (scrum)</a>
                      <div class="author">
                        <a href="#">
                        <img src="../img/blank.png" style="background: url(../img/3.jpg) center no-repeat;">
                        <span>Nguyen Van Tien</span>
                        </a>
                        <div class="mini-date">
                          <span>posted on</span>
                          <span class="date">May 11, 2015</span>
                        </div>
                      </div>
                      <p class="detail">Photon Server là một dịch vụ được cung cấp bởi Exitgames nhằm hỗ trợ người phát triển game khai triển server riêng, có khả năng thiết kế luồng hoạt động sao cho phù hợp với từng game riêng</p>
                      <div class="tags">
                        <a class="php" href="#">php</a>
                        <a class="html" href="#">html</a>
                        <a class="ios" href="#">.ios</a>
                        <a class="css3" href="#">css3</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="load-more">
                 <a href="#">Load More</a>
               </div>
          </div>
       </div>
    </div>
    <div class="col-md-3 col-lg-3 post-right">
        <div class="module user-ranking gray-box">
           <h4 class="module-title">Ranking Q&A</h4>
           <div class="module-content">
              <div class="user-ranking-info">
                    <span class="ranking-number ranking-one">1</span>
                    <a href="#">
                    <span class="thumb">
                    <img src="../img//va/user-ranking-1.png" alt="">
                    </span>
                    <span class="ovh">
                    <span class="user-name">Nguyen Van Vuong</span>
                    <span>20 Best Answer</span>
                    </span>
                    </a>
              </div>
           </div>
        </div>
        <div class="module user-skills gray-box">
           <h4 class="module-title">Skills</h4>
           <div class="module-content">
              <a class="user-skill-list" href="#">
                 <p>Constuctor</p>
                 <span>2 years</span>
              </a>
              <a class="user-skill-list" href="#">
                 <p>Design Pattern</p>
                 <span>4 years</span>
              </a>
              <a class="user-skill-list" href="#">
                 <p>Refactoring</p>
                 <span>1 years</span>
              </a>
              <a class="user-skill-list" href="#">
                 <p>Functional Programming</p>
                 <span>5 years</span>
              </a>
           </div>
        </div>
        <div class="module user-infomation gray-box">
           <h4 class="module-title">Infomation</h4>
           <div class="module-content">
              <h4>Article</h4>
              <ul class="user-infomation-list">
                 <li class="user-infomation-detail">
                    <a href="#">
                       <span class="info-text">Posts</span>
                       <span class="info-number">12</span>
                       <div class="clr"></div>
                    </a>
                 </li>
                 <li class="user-infomation-detail">
                    <a href="#">
                       <span class="info-text">My Clips</span>
                       <span class="info-number">3</span>
                       <div class="clr"></div>
                       </a>
                 </li>
                 <li class="user-infomation-detail">
                    <a href="#">
                       <span class="info-text">Clipped</span>
                       <span class="info-number">5</span>
                       <div class="clr"></div>
                    </a>
                 </li>
                 <li class="user-infomation-detail">
                    <a href="#">
                       <span class="info-text">Get Helpful</span>
                       <span class="info-number">5</span>
                       <div class="clr"></div>
                    </a>
                 </li>
              </ul>
              <h4>Q&A</h4>
              <ul class="user-infomation-list">
                 <li class="user-infomation-detail">
                    <a href="#">
                       <span class="info-text">Questions</span>
                       <span class="info-number">12</span>
                       <div class="clr"></div>
                    </a>
                 </li>
                 <li class="user-infomation-detail">
                    <a href="#">
                       <span class="info-text">Answers</span>
                       <span class="info-number">3</span>
                       <div class="clr"></div>
                       </a>
                 </li>
                 <li class="user-infomation-detail">
                    <a href="#">
                       <span class="info-text">My Clips</span>
                       <span class="info-number">5</span>
                       <div class="clr"></div>
                    </a>
                 </li>
                 <li class="user-infomation-detail">
                    <a href="#">
                       <span class="info-text">My Helpful</span>
                       <span class="info-number">5</span>
                       <div class="clr"></div>
                    </a>
                 </li>
                 <li class="user-infomation-detail">
                    <a href="#">
                       <span class="info-text">Best Answers</span>
                       <span class="info-number">5</span>
                       <div class="clr"></div>
                    </a>
                 </li>
                 <li class="user-infomation-detail">
                    <a href="#">
                       <span class="info-text">Get Helpful</span>
                       <span class="info-number">5</span>
                       <div class="clr"></div>
                    </a>
                 </li>
                 <li class="user-infomation-detail">
                    <a href="#">
                       <span class="info-text">Clipped</span>
                       <span class="info-number">5</span>
                       <div class="clr"></div>
                    </a>
                 </li>
              </ul>
              <h4>Other</h4>
              <ul class="user-infomation-list">
                 <li class="user-infomation-detail">
                    <a href="#">
                       <span class="info-text">Following Categories</span>
                       <span class="info-number">12</span>
                       <div class="clr"></div>
                    </a>
                 </li>
                 <li class="user-infomation-detail">
                    <a href="#">
                       <span class="info-text">Following Users</span>
                       <span class="info-number">3</span>
                       <div class="clr"></div>
                       </a>
                 </li>
                 <li class="user-infomation-detail">
                    <a href="#">
                       <span class="info-text">Followers</span>
                       <span class="info-number">5</span>
                       <div class="clr"></div>
                    </a>
                 </li>
              </ul>
           </div>
        </div>
    </div>
  </div>
</div>
@endsection