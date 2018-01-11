$('.coin_rate').click(function(){
    var id = $(this).attr("id");
    var id_array = id.split("_");
    var exchange_name = id_array[0];
    var coin_name = id_array[1];
    setCoinRateChart(exchange_name, coin_name);
});

function setCoinRateChart(exchange_name, coin_name) {
    $.ajax({
        type: "GET",
        url: "/api/coin_rate_history/" + exchange_name + "/" + coin_name,
        dataType: "json"
    }).done(function (data, textStatus, jqXHR) { //成功した場合
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