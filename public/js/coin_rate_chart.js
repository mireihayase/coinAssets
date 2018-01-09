$.ajax({
    type: "GET",
    url: "/api/coin_ratio",
    dataType: "json"
}).done(function( data, textStatus, jqXHR){ //成功した場合
    var ratio = data['ratio'];
    var coin_name_array = [];
    var amount_array = [];
    for(var coin_name in ratio){
        amount_array.push(ratio[coin_name]);
        coin_name_array.push(coin_name);
    }
    setPieChart(amount_array, coin_name_array);

}).fail(function(jqXHR, textStatus, errorThrown){ //失敗した場合
    console.log("error", jqXHR);
});


function setPieChart(amount_array, coin_name_array){
    var colorNames = Object.keys(window.chartColors);
    var config = {
        type: 'pie',
        data: {
            datasets: [{
                data: amount_array,
                backgroundColor: [
                    window.chartColors.red,
                    window.chartColors.orange,
                    window.chartColors.yellow,
                    window.chartColors.green,
                    window.chartColors.blue,
                    window.chartColors.purple,
                ],
                label: 'Dataset 1'
            }],
            labels: coin_name_array
        }
    }

    var ctx = document.getElementById("chart-area").getContext("2d");
    window.myPie = new Chart(ctx, config);
}