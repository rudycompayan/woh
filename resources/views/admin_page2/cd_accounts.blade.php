@extends('admin_dashboard')
@section('content')
    <!-- /subnavbar -->
    <div class="main clearfix">
        <div class="main-inner">
            <div class="container">
                <div class="row">
                    <div class="span12">
                        <div class="widget widget-table action-table">
                            <div class="widget-header"> <i class="icon-th-list"></i>
                                <h3>CD Account Report</h3>
                                From: <input type="text" id="start_date" style="margin-top: 6px;"> To:
                                <input type="text" id="end_date" style="margin-top: 6px;">
                            </div>
                            <!-- /widget-header -->
                            <div class="widget-content"   style="overflow: scroll; max-height: 500px">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Release Date</th>
                                        <th>KLP Member</th>
                                        <th>CD Code</th>
                                        <th>Pin Code</th>
                                        <th>Remaining Balance</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(isset($klp_member))
                                        @foreach($klp_member as $key => $mt)
                                            <tr>
                                                <td>{!! \Carbon\Carbon::parse($mt['created_at'])->format('m/d/Y H:i A') !!}</td>
                                                <td>{!! $mt['first_name'] !!} {!! $mt['last_name'] !!}</td>
                                                <td>{!! !empty($mt['cd_code']) ? $mt['cd_code'] : '' !!}</td>
                                                <td>{!! !empty($mt['pin_code']) ? $mt['pin_code'] : '' !!}</td>
                                                <td>&#8369; {!! number_format($mt['credit_amount'],2) !!}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    <tr>
                                        <td colspan="5"><b>Totals Member ==> {{ number_format(count($klp_member)) }}</b></td>
                                    </tr>
                                    </tbody>
                                </table>
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

    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $(document).ready(function(){
            $( "#start_date" ).datepicker();
            $( "#end_date" ).datepicker();
        });
    </script>
@endsection