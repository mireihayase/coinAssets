<!DOCTYPE html>
<html>

  @include('include/head')
  <script src="http://www.chartjs.org/dist/2.7.1/Chart.bundle.js"></script>
  <script src="http://www.chartjs.org/samples/latest/utils.js"></script>

  <style>

    .table-header-fixed {
      min-width: calc(19em + 17px); /* カラム最小幅合計値+スクロールバーの幅+1px（誤差吸収用） */
      height: 320px;
      overflow-y: scroll;
      border-collapse: collapse;
    }

    .table-header-fixed thead,
    .table-header-fixed tbody,
    .table-header-fixed tr,
    .table-header-fixed th,
    .table-header-fixed td {
      display: block;
    }

    .table-header-fixed tbody {
      width: 100%;
      height: calc(100% - 2em); /* 100% - ヘッダの高さ */
      overflow-y: scroll;
    }

    .table-header-fixed tr:after { /* clearfix */
      content: "";
      clear: both;
      display: block;
    }

    .table-header-fixed th {
      float: left;
      height: 35px;
      overflow: hidden;
      padding: 0 calc((100% - 14em)/6); /* (100% - カラム最小幅合計値)/(カラム数*2) */
    }
    .table-header-fixed td {
      float: left;
      height: 35px;
      overflow: hidden;
      padding: 0 calc((100% - 14em)/6); /* (100% - カラム最小幅合計値)/(カラム数*2) */
      box-sizing: content-box;
    }

    .table-header-fixed th:first-child,
    .table-header-fixed td:first-child{
      /*text-align: right;*/
    }
  </style>
  <body>
    @include('include/header')

    @include('include/sidebar')

    <div class="main-contents">
      <div class="main-contents__body">

        <dl class="panel"><dt class="summary__head"><span>価格一覧</span></dt>

          {{--<dl class="summaries">--}}
            {{--<table class="table table--striped">--}}
            <table class="table table--striped table-header-fixed">
              <thead class="table_head">
                <tr>
                  <th>　銘柄</th>
                  <th>　JPY換算</th>
                  <th style="float: right;">昨日比</th>
                </tr>
              </thead>
              <tbody  class ="table_scroll">
              {{--<tbody style="overflow-x: hidden; overflow-y: scroll">--}}
                @foreach($coin_rate_array as $exchange => $rate_array)
                  <tr style="background-color: gray; color:white;"><td>{{$exchange}}</td><td></td><td></td><tr>
                  @foreach($rate_array as $name => $rate)
                    <tr id="{{$exchange . '_'. $name}}" class="coin_rate">
                      <td>{{$name}}
                        <img src="../coin_img/{{$name}}.svg" style="width: 30px; height: 30px">
                      </td>
                      <td id="{{$exchange.'_'.$name. '_price'}}">{{number_format($rate, 2)}}</td>
                      <?php
						$yesterday_rate = $yesterday_rate_array[$exchange][$name];
						$class = getPlusOrMinusClass($yesterday_rate);
                        ?>
                      <td class="summary__num diff {{$class}}" style="float: right;">{{$yesterday_rate}}%</td>
                    </tr>
                  @endforeach
                @endforeach
              </tbody>
            </table>
            <br />
            <dd class="summary__body">
              <div style="float: right;">
                <button class="term" id="hourly" style="border-radius: 3px;" >hourly</button>
                <button class="term" id="daily" style="border-radius: 3px;">daily</button>
              </div>
              <div style="display: none" class="" id="set_terms"></div>
              <div id="canvas-holder" style="width:100%">
                <canvas id="chart-area" />
              </div>
            </dd>
          </dl>

          </div>
        </div>
      </div>

    <script src="./bower_components/jquery/dist/jquery.min.js"></script>
    <script src="./bower_components/adminize/js/adminize.min.js"></script>
    <script src="../js/rate_history.js"></script>
  </body>

</html>