google.load("visualization", "1", {packages: ["corechart"]});
google.setOnLoadCallback(drawAreaChart);
function drawAreaChart() {
    var jsonData = $.ajax({
        url: area.url,
        dataType: "json",
        async: false
    }).responseText;
    var myData = JSON.parse(jsonData);
    if (typeof myData["rows"][0] !== 'undefined') {
        var data = new google.visualization.DataTable(jsonData);
        var options = {
            bar: {groupWidth: "20%"},
            'chartArea': {'width': '90%', 'height': '80%'},
            legend: {position: 'bottom'},
            colors: ['#4787ed', '#C0C0C0', '#8C7853'],
            pointSize: 5,
            series: {
                0: {pointShape: 'circle'},
                1: {pointShape: 'circle'}
            },
            animation: {duration: 1000, easing: 'out'},
        };
        var chart = new google.visualization.AreaChart(document.getElementById(area.chartdiv));
        chart.draw(data, options);
    } else {
        document.getElementById(area.chartdiv).innerHTML = '<p class="datanfound">Data Not Found</p>';
    }
}