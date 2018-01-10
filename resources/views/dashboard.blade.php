<!DOCTYPE html>
<html>

@include('include/head')

  <body>
  @include('include/header')

  @include('include/sidebar')

    <div class="main-contents">
      <div class="main-contents__body">
        <ul class="breadcrumbs">
          <li class="breadcrumbs__list"><a href="#">Home</a></li>
          <li class="breadcrumbs__list">Dashboard template</li>
        </ul>
        <h1 class="page-header"><i class="fa fa-tachometer"></i><span>Dashboard template</span></h1>
        <div class="summaries">
          <dl class="summary"><dt class="summary__head"><i class="fa fa-user"></i><span>Users</span></dt>
            <dd class="summary__body"><span class="summary__num">264</span><span class="summary__num diff success">+264(+400%)</span></dd>
          </dl>
          <dl class="summary"><dt class="summary__head"><i class="fa fa-music"></i><span>Songs</span></dt>
            <dd class="summary__body"><span class="summary__num">1,000</span><span class="summary__num diff danger">-500(-200%)</span></dd>
          </dl>
          <dl class="summary"><dt class="summary__head"><i class="fa fa-film"></i><span>Movies</span></dt>
            <dd class="summary__body"><span class="summary__num">2,450</span><span class="summary__num diff danger">-1,000(-100%)</span></dd>
          </dl>
        </div>
      </div>
    </div>
    {{--<script src="./bower_components/jquery/dist/jquery.min.js"></script>--}}
    <script src="./bower_components/adminize/js/adminize.min.js"></script>
  </body>

</html>