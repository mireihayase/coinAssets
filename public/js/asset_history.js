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
    var barChartData = {
        labels : date_array,
        datasets : [
            {
                fillColor : /*"#7fc2ef"*/"rgba(127,194,239,0.7)",
                strokeColor : /*"#7fc2ef"*/"rgba(127,194,239,0.7)",
                highlightFill : /*"#a5d1f4"*/"rgba(165,209,244,0.7)",
                highlightStroke : /*"#a5d1f4"*/"rgba(165,209,244,0.7)",
                data : amount_array
            }
        ]
    }

    var ctx = document.getElementById("chart-area").getContext("2d");
    window.myBar = new Chart(ctx).Line(barChartData, {
        responsive: true,
        // アニメーションを停止させる場合は下記を追加
        /* animation : false */
    });
}