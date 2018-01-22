
var backgroundColor = [
    window.chartColors.red,
    window.chartColors.orange,
    window.chartColors.yellow,
    window.chartColors.green,
    window.chartColors.blue,
    window.chartColors.purple,
]

$.ajax({
    type: "GET",
    url: "/api/coin_ratio",
    dataType: "json"
}).done(function( data, textStatus, jqXHR){ //成功した場合
    var ratio = [];
    var i = 0;
    for(var coin_name in data){
        var coin_data = [];
        coin_data["label"] = coin_name;
        coin_data["value"] = data[coin_name];
        coin_data["color"] = backgroundColor[i];
        ratio.push(coin_data);
        i ++;
    }
    setPieChart(ratio);

}).fail(function(jqXHR, textStatus, errorThrown){ //失敗した場合
    console.log("error", jqXHR);
});

function setPieChart(data) {
    var ctx = document.getElementById("chart-area").getContext("2d");
    window.myPie = new Chart(ctx).Pie(data, {
        tooltipTemplate: "<%if (label){%><%=label%> : <%=value%>%<%}%>",
        onAnimationComplete: function () {
            this.showTooltip(this.segments, true);
        },
        tooltipEvents: [],
        showTooltips: true
    })
}

