google.load("visualization", "1", {packages: ["corechart"]});
google.setOnLoadCallback(drawColumnChart);
function drawColumnChart() {
    var jsonData = $.ajax({
        url: column.url,
        dataType: "json",
        async: false
    }).responseText;
    var myData = JSON.parse(jsonData);
    if (typeof myData["rows"][0] !== 'undefined') {
        var data = new google.visualization.DataTable(jsonData);

        var options = {
            'title': column.title,
            'width': '100%',
            'height': 300,
            bar: {groupWidth: "40%"},
            'chartArea': {'width': '90%', 'height': '80%'},
            'legend': 'none',
            animation: {duration: 1000, easing: 'out'}
        };
        var chart = new google.visualization.ColumnChart(document.getElementById(column.chartdiv));
        chart.draw(data, options);
    } else {
        document.getElementById(column.chartdiv).innerHTML = '<p class="datanfound">Data Not Found</p>';
    }
}