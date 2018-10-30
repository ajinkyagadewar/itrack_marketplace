google.load("visualization", "1.1", {packages: ["corechart", "bar"]});
google.setOnLoadCallback(drawColumnChart);
function drawColumnChart() {
    var jsonData = $.ajax({
        url: stack_bar.url,
        dataType: "json",
        async: false
    }).responseText;
    // var myData = JSON.parse(jsonData);
    var data = new google.visualization.DataTable(jsonData);
    var myData = JSON.parse(jsonData);
    if (typeof myData["rows"][0] !== 'undefined') {
        var options = {
            title: stack_bar.title,
            width: '100%',
            height: 200,
            legend: {position: "bottom"},
            'vAxis': {textPosition: 'bottom'},
            bar: {groupWidth: "35%"},
            'chartArea': {'width': '90%', 'height': '60%'},
            animation: {duration: 1000, easing: 'out'}
        };
        var chart = new google.visualization.ColumnChart(document.getElementById(stack_bar.chartdiv));
        chart.draw(data, options);
    } else {
        document.getElementById(stack_bar.chartdiv).innerHTML = '<p class="datanfound">Data Not Found</p>';
    }
}