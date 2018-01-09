<!DOCTYPE html>
<html>

@include('include/head')
<script src="http://www.chartjs.org/dist/2.7.1/Chart.bundle.js"></script>
<script src="http://www.chartjs.org/samples/latest/utils.js"></script>

{{--<script src="../js/coin_rate_chart.js"></script>--}}

<body>
@include('include/header')

@include('include/sidebar')


<div class="main-contents">

    <div id="canvas-holder" style="width:100%">
        <canvas id="chart-area" />
    </div>


    <div class="main-contents__body">

        <div id="canvas-holder" style="width:100%">
            <canvas id="chart-area" />
        </div>

    </div>
</div>
<script src="./bower_components/jquery/dist/jquery.min.js"></script>
<script src="./bower_components/adminize/js/adminize.min.js"></script>

<script src="../js/coin_rate_chart.js"></script>
</body>

</html>