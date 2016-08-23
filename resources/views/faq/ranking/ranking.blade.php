@extends('layouts.default')

@section('main') 
    <div id="container" class="container">
      <div class="post-detail row">
        <div class="col-md-9 col-lg-9 post-left">
              <div class="user-profile-detail-tabs1">
                 <a href="user-profile-article.html" class="active">Article</a>
                 <a href="user-profile-group.html">Group</a>
                 <a href="user-profile-q&a.html">Q&A</a>
                 <a href="user-profile-others.html" >Others</a>
              </div>
              <div class="user-profile-detail-tabs2">
                 <a href="#" class="active">Posts</a>
                 <a href="#">My Clips</a>
                 <a href="#">Clipped</a>
                 <a href="#">Get Helpful</a>
              </div>
              <div class="content">
                 13234
              </div>

          <!-- /.wrap-questions -->
        </div>
        <div class="col-md-3 col-lg-3 post-right">

          <!-- /.response-time -->
          <div class="module latest-question gray-box">
            <h4 class="module-title">Latest Questions</h4>
            <div class="module-content">
              <ul class="list-items">
                <li class="item">
                  <div class="meta">
                    <span class="status solved">Solved</span>
                    <span class="count-clip">20 Clips</span>
                    <span class="count-answer">5 Answers</span>
                  </div>
                  <a href="#">Excepteur sint occaecat cupidatat non ea proident sunt in culpa?</a>
                </li>
                <li class="item">
                  <div class="meta">
                    <span class="status unsolved">Unsolved</span>
                    <span class="count-clip">20 Clips</span>
                    <span class="count-answer">5 Answers</span>
                  </div>
                  <a href="#">Excepteur sint occaecat cupidatat non ea proident sunt in culpa?</a>
                </li>
                <li class="item">
                  <div class="meta">
                    <span class="status solved">Solved</span>
                    <span class="count-clip">20 Clips</span>
                    <span class="count-answer">5 Answers</span>
                  </div>
                  <a href="#">Excepteur sint occaecat cupidatat non ea proident sunt in culpa?</a>
                </li>
                <li class="item">
                  <div class="meta">
                    <span class="status unsolved">Unsolved</span>
                    <span class="count-clip">20 Clips</span>
                    <span class="count-answer">5 Answers</span>
                  </div>
                  <a href="#">Excepteur sint occaecat cupidatat non ea proident sunt in culpa?</a>
                </li>
                <li class="item">
                  <div class="meta">
                    <span class="status solved">Solved</span>
                    <span class="count-clip">20 Clips</span>
                    <span class="count-answer">5 Answers</span>
                  </div>
                  <a href="#">Excepteur sint occaecat cupidatat non ea proident sunt in culpa?</a>
                </li>
              </ul>
            </div>
            <!-- /.module-content -->
          </div>

          <!-- /.Categories -->
          <div class="module categories gray-box">
            <h4 class="module-title">Categories</h4>
            <div class="module-content">
               <ul class="category">
                   <li><a href="#">HTML</a><span class="sum-article">2</span></li>
                   <li><a href="#">iOS</a><span class="sum-article">3</span></li>
                   <li><a href="#">php</a><span class="sum-article">5</span></li>
                   <li><a href="#">java</a><span class="sum-article">7</span></li>
                   <li><a href="#">MySql</a><span class="sum-article">8</span></li>
                   <li><a href="#">Agile</a><span class="sum-article">12</span></li>
                   <li><a href="#">Sql</a><span class="sum-article">12</span></li>
                   <li><a href="#">Testing</a><span class="sum-article">12</span></li>
                   <li><a href="#">Communication</a><span class="sum-article">12</span></li>
                   <li><a href="#">Web</a><span class="sum-article">12</span></li>
                   <li><a href="#">Geb</a><span class="sum-article">12</span></li>
                   <li><a href="#">Selenium</a><span class="sum-article">12</span></li>
                   <li><a href="#">GRAMGIA-QA</a><span class="sum-article">12</span></li>
               </ul>
            </div>
            <button class="viewall">View All</button>
            <!-- /.module-content -->
          </div>
        </div>
      </div>
    </div>
@endsection