$.ajax({
    type: "GET",
    url: "/api/daily_asset_history",
    dataType: "json"
}).done(function( data, textStatus, jqXHR){ //成功した場合
    var histories = data;
    var date_array = [];
    var amount_array = [];
    for(var date in histories){
        amount_array.push(histories[date]);
        date_array.push(date);
    }
    setPieChart(date_array, amount_array);

}).fail(function(jqXHR, textStatus, errorThrown){ //失敗した場合
    console.log("error", jqXHR);
});


function setPieChart(date_array, amount_array) {
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
                label: "総資産推移",
                //背景色
                backgroundColor: "rgba(75,192,192,0.4)",
                //枠線の色
                borderColor: "rgba(75,192,192,1)",
                //グラフのデータ
                data: amount_array
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