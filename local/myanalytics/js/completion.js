// This file is part of Moodle - http://moodle.com/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * javascript
 * @desc This javascript recive data from server and dispaly in tables.
 * 
 * @package    local_ereport
 * @copyright  2015
 * @author
 * @license
 */

function get_data() {
    document.getElementById('main-panel').innerHTML = '<div class="ajax-loader"><img src="../pix/ajaxloader.GIF"><p>Loading ...</p></div></div>';
    var username = document.getElementById('username').value;
    var id = $('#users option').filter(function () {
        return this.value === username;
    }).data('xyz');

    var xmlhttp;
    if (username === "") {
        document.getElementById("txtHint").innerHTML = "";
        return;
    }
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {

        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            document.getElementById('main-panel').innerHTML = '';
            var json = JSON.parse(xmlhttp.responseText);
            if (json.status) {
                var r = 0;
                var c = 0;

                var header = '';

                header += '<div class="row gridreport" style="margin-top:10px">';

                header += '<div class="span6">' +
                        '<div class="panel panel-warning">' +
                        '<div class="panel-heading">Courses overview</div>' +
                        '<div class="panel-body">' +
                        '<div id="coursegraph" style="width:100%; height: 300px;"></div>' +
                        '</div>' +
                        '</div>' +
                        '</div>';
                header += '<div class="span6">' +
                        '<div class="panel panel-warning">' +
                        '<div class="panel-heading">Courses overview</div>' +
                        '<div class="panel-body">' +
                        ' <ul class="list-group">';
                if (json.enrolled.length !== 0) {
                    for (var key in json.enrolled) {
                        header += '<li class="list-group-item">' + json.enrolled[key] + '</li>';
                    }
                } else {
                    header += '<li class="list-group-item">Course not found</li>';
                }
                header += '</ul>' +
                        '</div>' +
                        '</div>' +
                        '</div>';
                header += '</div><div class="row gridreport" style="margin-top:10px">';

                header += '<div class="span6">' +
                        '<div class="panel panel-warning">' +
                        '<div class="panel-heading">Completed Courses</div>' +
                        '<div class="panel-body">' +
                        ' <ul class="list-group">';
                if (json.completed.length !== 0) {
                    for (var key in json.completed) {
                        header += '<li class="list-group-item">' + json.completed[key] + '</li>';
                    }
                } else {
                    header += '<li class="list-group-item">No courses completed yet.</li>';
                }
                header += '</ul>' +
                        '</div>' +
                        '</div>' +
                        '</div>';

                header += '<div class="span6">' +
                        '<div class="panel panel-warning">' +
                        '<div class="panel-heading">Not completed Courses</div>' +
                        '<div class="panel-body">' +
                        '<ul class="list-group">';
                if (json.incompleted.length !== 0) {
                    for (var key in json.incompleted) {
                        header += '<li class="list-group-item">' + json.incompleted[key] + '</li>';
                    }
                } else {
                    header += '<li class="list-group-item">Course not found</li>';
                }
                header += '</ul>' +
                        '</div>' +
                        '</div>' +
                        '</div>';

                header += '<div>';
                document.getElementById('main-panel').innerHTML += header;

                var activitylist = json.activitylist;
                for (var key in activitylist) {
                    var head = '<thead><tr><th class="header c0">Activity Name</th><th class="header c1">Activity Type</th><th class="header c2">Completion Status</th><th class="header c3">Time Spent</th><th class="header c4">Completion Date</th></thead></tr>';
                    var content = '<tbody>';
                    var footer = '</tbody></table>';
                    if (typeof activitylist[key].activity[0] !== 'undefined') {
                        for (var skey in activitylist[key].activity) {
                            var icon = activitylist[key].activity[skey].completionstate ? '<i class="icon icon-ok"></i>' : '<i class="icon icon-remove"></i>';

                            content += '<tr class="r' + (r++) + '"><td class="c' + (c++) + ' cell">' + activitylist[key].activity[skey].name + '</td>' + '<td class="c' + (c++) + ' cell">' + activitylist[key].activity[skey].type + '</td>' + '<td class="c' + (c++) + ' cell">' + icon + '</td>' + '<td class="c' + (c++) + ' cell">' + activitylist[key].activity[skey].timespent + '</td><td class="c' + (c++) + ' cell">' + activitylist[key].activity[skey].date + '</td>' + '</tr>';

                        }
                    } else {
                        content += '<tr class="c1"><td colspan="5" style="text-align:center">' + 'No activity available' + '</td></tr>';
                    }
                    document.getElementById('main-panel').innerHTML += '<div class="row cdtlrow"><div class="panel panel-warning"><div class="panel-heading hd"><a href ="/course/view.php?id=' + activitylist[key].id + '">' + activitylist[key].fullname + '</a></div><table class="generaltable panel-body">' + head + content + footer + '</div></div>';
                }


                $.ajax({
                    url: 'https://www.google.com/jsapi?callback',
                    cache: true,
                    dataType: 'script',
                    success: function () {
                        google.load('visualization', '1', {packages: ['corechart'], 'callback': function () {

                                var json = $.ajax({
                                    url: path.pathurl + '/local/myanalytics/ajax/completion_ajax.php?callback=chartdata&user=' + id,
                                    dataType: "json",
                                    async: false
                                }).responseText;
                                var jsonobj = JSON.parse(json);
                                var data = google.visualization.arrayToDataTable([
                                    ['Name', 'Count', {role: 'style'}],
                                    ['Course Enrolled', jsonobj.enrolled, '#0000FF'],
                                    ['Course Completed', jsonobj.complete, '#008000'],
                                    ['Course Incomplete', jsonobj.incomplete, '#D03621']
                                ]);
                                var options = {
                                    'title': '',
                                    'width': '100%',
                                    'height': 290,
                                    bar: {groupWidth: "30%"},
                                    'chartArea': {'width': '100%', 'height': '80%'},
//                                    'legend': {'position': 'bottom'},
                                    animation: {duration: 1000, easing: 'out'}
                                };
                                var chart = new google.visualization.ColumnChart(document.getElementById("coursegraph"));
                                chart.draw(data, options);
                            }
                        });
                        return true;
                    }
                });
            } else {
                document.getElementById('main-panel').innerHTML = '<br/><div class="alert alert-warning"><strong>' + json.message + '<strong></div>';
            }
        }
    };
    xmlhttp.open("GET", url + "completion_ajax.php?callback=courseinfo&user=" + id, true);
    xmlhttp.send();
}