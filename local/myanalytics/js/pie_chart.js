google.load("visualization", "1", {packages: ["corechart"]});
google.setOnLoadCallback(drawPieChart);
function drawPieChart() {
    var jsonData = $.ajax({
        url: pie.url,
        dataType: "json",
        async: false
    }).responseText;
    var myData = JSON.parse(jsonData);
    //conditionA if no json response found for first and second row.
    var conditionA = (typeof myData["rows"][0] !== 'undefined');
    var data = new google.visualization.DataTable(jsonData);
    if (myData["rows"][0]["c"][1]["v"] != 0) {
        var options = {
            width: '100%',
            height: 350,
            legend: 'bottom',
            bar: {groupWidth: "40%"},
            chartArea: {'width': '90%', 'height': '80%'},
            animation: {duration: 1000, easing: 'out', }
        };
        var chart = new google.visualization.PieChart(document.getElementById(pie.chartdiv));
        chart.draw(data, options);
    } else {
        document.getElementById(pie.chartdiv).innerHTML = '<p class="datanfound">Data Not Found</p>';
    }
}