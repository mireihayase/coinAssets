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
      <li class="breadcrumbs__list">Asset</li>
    </ul>
    <h1 class="page-header"><i class="fa fa-file-text"></i><span>Archive template</span></h1>

    <table class="table table--striped">
      <thead>
      <tr>
        <th>コイン種類</th>
        <th>数量</th>
        <th>円換算</th>
        {{--<th></th>--}}
      </tr>
      </thead>
      <tbody>
        @foreach($assets['funds'] as $k =>$v)
        <tr>
          <td>{{$k}}</td>
          <td>{{$v}}</td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>
</div>
<script src="../bower_components/jquery/dist/jquery.min.js"></script>
<script src="../../bower_components/adminize/js/adminize.min.js"></script>
</body>

</html>