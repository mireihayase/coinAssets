$('.coin_rate').click(function() {
    $('#chart-area').remove();
    var id = $(this).attr("id");
    $('#set_terms').removeClass();
    $('#set_terms').addClass(id);
    $('#termsss').text(id);
    var id_array = id.split("_");
    var exchange_name = id_array[0];
    var coin_name = id_array[1];
    setCoinRateChart(exchange_name, coin_name, 'hourly');
});

$(".term").click(function() {
    $('#chart-area').remove();
    var id = $('#set_terms').attr('class');
    var term =$(this).attr("id");
    var id_array = id.split("_");
    var exchange_name = id_array[0];
    var coin_name = id_array[1];
    setCoinRateChart(exchange_name, coin_name, term);
});

function setCoinRateChart(exchange_name, coin_name, term) {
    var url = term == 'hourly' ? "/api/hourly_rate/" : "/api/daily_rate/";
    $.ajax({
        type: "GET",
        url: url + exchange_name + "/" + coin_name,
        dataType: "json"
    }).done(function (data, textStatus, jqXHR) { //成功した場合
        $('#canvas-holder').append('<canvas id="chart-area" style="width:100%"/>');
        var histories = data;
        var date_array = [];
        var rate_array = [];
        for (var date in histories) {
            rate_array.push(histories[date]);
            date_array.push(date);
        }
        setPieChart(date_array, rate_array);

    }).fail(function (jqXHR, textStatus, errorThrown) { //失敗した場合
        console.log("error", jqXHR);
    });
}

//version 2.7.1
/*
function setPieChart(date_array, rate_array) {
    var ctx = document.getElementById("chart-area");
    var myLineChart = new Chart(ctx, {
        //グラフの種類
        type: 'line',
        //データの設定
        data: {
            //データ項目のラベル
            labels: date_array,
            //データセット
            datasets: [{
                //凡例
                label: "価格推移",
                //背景色
                backgroundColor: window.chartColors.red,
                //枠線の色
                borderColor: 'red',
                //グラフのデータ
                data: rate_array
            }]
        },
        //オプションの設定
        options: {
            scales: {
                //縦軸の設定
                yAxes: [{
                    ticks: {
                        //最小値を0にする
                        beginAtZero: true
                    }
                }]
            }
        }
    });
}
*/

// version 1.0.2
function setPieChart(date_array, rate_array) {
    var barChartData = {
        labels : date_array,
        datasets : [
            {
                fillColor : window.chartColors.red, //"rgba(127,194,239,0.7)",
                strokeColor : window.chartColors.red,//"rgba(127,194,239,0.7)",
                highlightFill : "rgba(165,209,244,0.7)",
                highlightStroke : "rgba(165,209,244,0.7)",
                data : rate_array
            }
        ]
    }

    var ctx = document.getElementById("chart-area").getContext("2d");
    window.myBar = new Chart(ctx).Line(barChartData, {
        tooltipTemplate: "<%=value%>",
        responsive: true,
        // アニメーションを停止させる場合は下記を追加
        //animation : false
    });
}
