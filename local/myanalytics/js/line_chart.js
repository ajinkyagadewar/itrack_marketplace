google.load('visualization', '1.1', {packages: ["corechart", "line"]});
google.setOnLoadCallback(drawLineChart);
function drawLineChart() {
    var jsonData = $.ajax({
        url: line.url,
        dataType: "json",
        async: false
    }).responseText;
    var myData = JSON.parse(jsonData);
    if (typeof myData["rows"][0] !== 'undefined') {
        var data = new google.visualization.DataView(jsonData);

        var options = {
            title: line.title,
            width: '100%',
            height: 300,
            legend: 'bottom',
            bar: {groupWidth: "40%"},
            chartArea: {'width': '90%', 'height': '80%'},
            animation: {duration: 1000, easing: 'out', }
        };

        var chart = new google.visualization.LineChart(document.getElementById(line.chartdiv));

        chart.draw(data, options);
    } else {
        document.getElementById(line.chartdiv).innerHTML = '<p class="datanfound">Data Not Found</p>';
    }
}