<!DOCTYPE html>
<html>

  @include('include/head')


  <body>
    @include('include/header')

    @include('include/sidebar')

    <div class="main-contents">
      <div class="main-contents__body">
        <ul class="breadcrumbs">
          <li class="breadcrumbs__list"><a href="">Home</a></li>
        </ul>
        <h1 class="page-header" style="margin-bottom: 0px;"><i class="fa fa-file-text"></i><span>Top</span></h1>


        <div class="summaries">
          <dl class="panel"><dt class="summary__head"><i class="fa fa-user"></i><span>総資産</span></dt>
            <dd class="summary__body"><span class="summary__num">{{number_format($total_amount)}} 円</span>
              {{--<span class="summary__num diff success">+264(+400%)</span>--}}
            </dd>
          </dl>
          <dl class="panel"><dt class="summary__head"><i class="fa fa-film"></i><span>日次損益</span></dt>
            <dd class="summary__body"><span class="summary__num {{getPlusOrMinusClass($daily_gain)}}">{{number_format($daily_gain)}} 円</span>
              {{--<span class="summary__num diff danger">-1,000(-100%)</span>--}}
            </dd>
          </dl>
        </div>


        <p class="weight--bold">価格一覧</p>
          <div class="summaries">
            <table class="table table--striped">
              <thead>
                <tr>
                  <th>銘柄</th>
                  <th>JPY</th>
                  <th>昨日比</th>
                </tr>
              </thead>
              <tbody>
                @foreach($coin_rate_array as $exchange => $rate_array)
                  <tr style="background-color: gray; color:white;"><td>{{$exchange}}</td><td></td><td></td><tr>
                  @foreach($rate_array as $name => $rate)
                    <tr>
                      <td>{{$name}}<img src="../coin_img/{{$name}}.svg" style="width: 30px; height: 30px"></td>
                      <td id="{{$exchange.'_'.$exchange}}">{{number_format($rate, 2)}}</td>
                      <?php
						$yesterday_rate = $yesterday_rate_array[$exchange][$name];
						$class = getPlusOrMinusClass($yesterday_rate);
                        ?>
                      <td class="summary__num diff {{$class}}">{{$yesterday_rate}}%</td>
                    </tr>
                  @endforeach
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    <script src="./bower_components/jquery/dist/jquery.min.js"></script>
    <script src="./bower_components/adminize/js/adminize.min.js"></script>
  </body>

</html>