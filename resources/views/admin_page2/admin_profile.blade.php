@extends('admin_dashboard')
@section('content')
        <!-- /subnavbar -->
<div class="main clearfix">
    <div class="main-inner">
        <div class="container">
            <div class="row">
                <div class="span6">
                    <!-- /widget -->
                    <div class="widget widget-nopad">
                        <div class="widget-header"> <i class="icon-list-alt"></i>
                            <h3>Activity Notes</h3>
                        </div>
                        <!-- /widget-header -->
                        <div class="widget-content">
                            <div id='calendar'>
                            </div>
                        </div>
                        <!-- /widget-content -->
                    </div>
                    <!-- /widget -->
                </div>
                <!-- /span6 -->
                <div class="span6">
                    <div class="widget widget-nopad">
                        <div class="widget-header"> <i class="icon-list-alt"></i>
                            <h3> Today's Stats</h3>
                        </div>
                        <!-- /widget-header -->
                        <div class="widget-content">
                            <div class="widget big-stats-container">
                                <div class="widget-content">
                                    <h6 class="bigstats">Social Media and Websites Interaction Statistics</h6>
                                    <div id="big_stats" class="cf">
                                        <div class="stat"> <i class="icon-user"></i> <span class="value">{!! $member_all !!}</span> </div>
                                        <!-- .stat -->

                                        <div class="stat"> <i class="icon-thumbs-up-alt"></i> <span class="value">423</span> </div>
                                        <!-- .stat -->

                                        <div class="stat"> <i class="icon-file"></i> <span class="value">922</span> </div>
                                        <!-- .stat -->

                                        <div class="stat"> <i class="icon-bullhorn"></i> <span class="value">25%</span> </div>
                                        <!-- .stat -->
                                    </div>
                                </div>
                                <!-- /widget-content -->

                            </div>
                        </div>
                    </div>
                    <!-- /widget -->
                    <div class="widget">
                        <div class="widget-header"> <i class="icon-signal"></i>
                            <h3>KLP Member's Activity</h3>
                        </div>
                        <!-- /widget-header -->
                        <div class="widget-content">
                            <canvas id="area-chart" class="chart-holder" height="250" width="538"> </canvas>
                            <!-- /area-chart -->
                        </div>
                        <!-- /widget-content -->
                    </div>
                    <!-- /widget -->
                </div>
                <!-- /span6 -->
            </div>
            <!-- /row -->
        </div>
        <!-- /container -->
    </div>
    <!-- /main-inner -->
</div>
<!-- /main -->
<!-- /extra -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="admin_page/js/jquery-1.7.2.min.js"></script>
<script src="admin_page/js/excanvas.min.js"></script>
<script src="admin_page/js/chart.min.js" type="text/javascript"></script>
<script src="admin_page/js/bootstrap.js"></script>
<script language="javascript" type="text/javascript" src="admin_page/js/full-calendar/fullcalendar.min.js"></script>

<script src="admin_page/js/base.js"></script>
<script>

    var lineChartData = {
        labels: ["January", "February", "March", "April", "May", "June", "July"],
        datasets: [
            {
                fillColor: "rgba(220,220,220,0.5)",
                strokeColor: "rgba(220,220,220,1)",
                pointColor: "rgba(220,220,220,1)",
                pointStrokeColor: "#fff",
                data: [65, 59, 90, 81, 56, 55, 40]
            },
            {
                fillColor: "rgba(151,187,205,0.5)",
                strokeColor: "rgba(151,187,205,1)",
                pointColor: "rgba(151,187,205,1)",
                pointStrokeColor: "#fff",
                data: [28, 48, 40, 19, 96, 27, 100]
            }
        ]

    }

    var myLine = new Chart(document.getElementById("area-chart").getContext("2d")).Line(lineChartData);

    $(document).ready(function() {
        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();
        var calendar = $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            selectable: true,
            selectHelper: true,
            select: function(start, end, allDay) {
                var title = prompt('Event Title:');
                if (title) {
                    calendar.fullCalendar('renderEvent',
                            {
                                title: title,
                                start: start,
                                end: end,
                                allDay: allDay
                            },
                            true // make the event "stick"
                    );
                }
                calendar.fullCalendar('unselect');
            },
            editable: true,
            events: [
                {
                    title: 'All Day Event',
                    start: new Date(y, m, 1)
                },
                {
                    title: 'Long Event',
                    start: new Date(y, m, d+5),
                    end: new Date(y, m, d+7)
                }
            ]
        });
    });
</script><!-- /Calendar -->
@endsection