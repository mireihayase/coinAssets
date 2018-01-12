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
    <h1 class="page-header" style="margin-bottom: 0px;"><i class="fa fa-file-text"></i><span>{{$exchange}}</span></h1>

    <div class="summaries">
      <a href="/coin_ratio">
        <dl class="panel"><dt class="summary__head"><i class="fa fa-user"></i><span>総資産</span></dt>
          <dd class="summary__body"><span class="summary__num">{{number_format($assets['total'])}}</span>
          </dd>
        </dl>
      </a>
    </div>
    <div class="summaries">
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
          @foreach($assets['coin'] as $v)
          <tr>
            <td>{{$v['coin_name']}}</td>
            <td>{{number_format($v['amount'], 2)}}</td>
            <td>
              @if(!empty($v['convert_JPY']))
                {{number_format($v['convert_JPY'], 2)}}
              @else
                0
              @endif
            </td>
          </tr>
        @endforeach
        </tbody>
      </table>
    </div>

  </div>
</div>
<script src="../bower_components/jquery/dist/jquery.min.js"></script>
<script src="../../bower_components/adminize/js/adminize.min.js"></script>
</body>

</html>