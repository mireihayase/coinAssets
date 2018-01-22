<!DOCTYPE html>
<html>

@include('include/head')
{{--<script src="http://www.chartjs.org/dist/2.7.1/Chart.bundle.js"></script>--}}

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.min.js"></script>
<script src="http://www.chartjs.org/samples/latest/utils.js"></script>
<body>
@include('include/header')

@include('include/sidebar')


<div class="main-contents">
    <div class="main-contents__body">

        <dl class="panel"><dt class="summary__head"><i class="fa fa-user"></i><span>総資産</span></dt>
            <dd class="summary__body"><span class="summary__num">{{number_format($total_amount)}} 円</span>
                <div id="canvas-holder" style="width:100%">
                    <canvas id="chart-area" style="width:100%"/>
                </div>
            </dd>
        </dl>

        <dl class="panel"><dt class="summary__head"><i class="fa fa-user"></i><span>保有資産</span></dt>
        <table class="table table--striped">
            <thead>
            <tr>
                <th>銘柄</th>
                <th>保有数</th>
                <th>JPY換算</th>
            </tr>
            </thead>
            <tbody>
            @foreach($amount as $coin_name => $assets)
                <tr>
                    <td>{{$coin_name}}</td>
                    <td>{{number_format($assets['amount'], 2)}}</td>
                    <td>
                        {{--@if(!empty($v['convert_JPY']))--}}
                            {{number_format($assets['convert_JPY'])}}
                        {{--@else--}}
                            {{--0--}}
                        {{--@endif--}}
                    </td>
                </tr>
            @endforeach

            </tbody>
        </table>
    </div>



</div>
<script src="./bower_components/jquery/dist/jquery.min.js"></script>
<script src="./bower_components/adminize/js/adminize.min.js"></script>

<script src="../js/coin_ratio.js"></script>
</body>

</html>