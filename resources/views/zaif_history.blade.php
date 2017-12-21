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
    <h1 class="page-header"><i class="fa fa-file-text"></i><span>zaif 取引履歴</span></h1>

    <table class="table table--striped">
      <thead>
      <tr>
        <th>コイン種類</th>
        <th>売り/買い</th>
        <th>数量</th>
        <th>円換算</th>
        <th>date</th>
      </tr>
      </thead>
      <tbody>
      @foreach($history as $transaction)
        @foreach($transaction as $v)
          <tr>
            <td>{{$v['currency_pair']}}</td>
            <td>{{$v['your_action']}}</td>
            <td>{{$v['amount']}}</td>
            <td>{{$v['price']}}</td>
            <td>{{date( "Y-m-d H:i:s", $v['timestamp'])}}</td>
          </tr>
        @endforeach
      @endforeach
      </tbody>
    </table>
  </div>
</div>
<script src="../bower_components/jquery/dist/jquery.min.js"></script>
<script src="../../bower_components/adminize/js/adminize.min.js"></script>
</body>

</html>