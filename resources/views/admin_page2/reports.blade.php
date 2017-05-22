@extends('admin_dashboard')
@section('content')
    <!-- /subnavbar -->
    <div class="main clearfix">
        <div class="main-inner">
            <div class="container">
                <div class="row">
                    <div class="span12">
                        <div class="widget">
                            <div class="widget-header"> <i class="icon-bookmark"></i>
                                <h3>Admin Reports</h3>
                            </div>
                            <!-- /widget-header -->
                            <div class="widget-content">
                                <div class="shortcuts">
                                    <a href="{{action('AdminController@release_codes')}}" class="shortcut">
                                        <i class="shortcut-icon icon-file"></i>
                                        <span class="shortcut-label">Release Codes</span>
                                    </a>
                                    <a href="{{action('AdminController@unused_codes')}}" class="shortcut">
                                        <i class="shortcut-icon icon-file"></i>
                                        <span class="shortcut-label">Unused Codes</span>
                                    </a>
                                    <a href="{{action('AdminController@report_withdrawals')}}" class="shortcut">
                                        <i class="shortcut-icon icon-bookmark"></i>
                                        <span class="shortcut-label">Member Withdrawals</span>
                                    </a>
                                    <a href="{{action('AdminController@report_unilevel')}}" class="shortcut">
                                        <i class="shortcut-icon icon-list-alt"></i>
                                        <span class="shortcut-label">Unilevel Report</span>
                                    </a>
                                    <a href="{{action('AdminController@klp_member_list')}}" class="shortcut">
                                        <i class="shortcut-icon icon-signal"></i>
                                        <span class="shortcut-label">KLP Member List</span>
                                    </a>
                                    <a href="{{action('AdminController@report_member_earnings')}}" class="shortcut">
                                        <i class="shortcut-icon icon-tag"></i>
                                        <span class="shortcut-label">Members Earning Report</span>
                                    </a>
                                    <a href="{{action('AdminController@cd_accounts')}}" class="shortcut">
                                        <i class="shortcut-icon icon-user"></i>
                                        <span class="shortcut-label">CD Accounts</span>
                                    </a>
                                    <a href="{{action('AdminController@report_redeemed_gc')}}" class="shortcut">
                                        <i class="shortcut-icon icon-picture"></i>
                                        <span class="shortcut-label">Redeemed Gift Certificates</span>
                                    </a>
                                    <a href="{{action('AdminController@report_gc_withdrawals')}}" class="shortcut" style="float: left">
                                        <i class="shortcut-icon icon-picture"></i>
                                        <span class="shortcut-label">GC Withdrawals</span>
                                    </a>
                                    <a href="{{action('AdminController@report_pairing_bonus')}}" class="shortcut" style="float: left">
                                        <i class="shortcut-icon icon-picture"></i>
                                        <span class="shortcut-label">Pairing Bonus</span>
                                    </a>
                                </div>
                                <!-- /shortcuts -->
                            </div>
                            <!-- /widget-content -->
                        </div>
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


        var barChartData = {
            labels: ["January", "February", "March", "April", "May", "June", "July"],
            datasets: [
                {
                    fillColor: "rgba(220,220,220,0.5)",
                    strokeColor: "rgba(220,220,220,1)",
                    data: [65, 59, 90, 81, 56, 55, 40]
                },
                {
                    fillColor: "rgba(151,187,205,0.5)",
                    strokeColor: "rgba(151,187,205,1)",
                    data: [28, 48, 40, 19, 96, 27, 100]
                }
            ]

        }

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
                    },
                    {
                        id: 999,
                        title: 'Repeating Event',
                        start: new Date(y, m, d-3, 16, 0),
                        allDay: false
                    },
                    {
                        id: 999,
                        title: 'Repeating Event',
                        start: new Date(y, m, d+4, 16, 0),
                        allDay: false
                    },
                    {
                        title: 'Meeting',
                        start: new Date(y, m, d, 10, 30),
                        allDay: false
                    },
                    {
                        title: 'Lunch',
                        start: new Date(y, m, d, 12, 0),
                        end: new Date(y, m, d, 14, 0),
                        allDay: false
                    },
                    {
                        title: 'Birthday Party',
                        start: new Date(y, m, d+1, 19, 0),
                        end: new Date(y, m, d+1, 22, 30),
                        allDay: false
                    }
                ]
            });
        });
    </script><!-- /Calendar -->
@endsection