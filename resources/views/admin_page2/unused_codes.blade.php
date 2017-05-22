@extends('admin_dashboard')
@section('content')
    <!-- /subnavbar -->
    <div class="main clearfix">
        <div class="main-inner">
            <div class="container">
                <div class="row">
                    <div class="span12">
                        <div class="widget widget-table action-table" id="print-element">
                            <form action="{{action('AdminController@post_filter_unused_codes')}}" method="post">
                            <div class="widget-header"> <i class="icon-th-list"></i>
                                <h3>Unused Codes Report</h3>
                                From: <input type="text" id="start_date" style="margin-top: 6px;" name="start_date" value="{{ $start_date ? \Carbon\Carbon::parse($start_date)->format('m/d/Y') : '' }}"> To:
                                <input type="text" id="end_date" style="margin-top: 6px;" name="end_date" value="{{ $end_date ? \Carbon\Carbon::parse($end_date)->format('m/d/Y') : '' }}">
                                <input type="submit" value="Filter Results" style="margin-top: 6px;" name="submit">
                                <input type="submit" value="Print Report" style="margin-top: 6px;" name="print" id="print">
                            </div>
                            </form>
                            <!-- /widget-header -->
                            <div class="widget-content">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Date Created</th>
                                        <th>Owner</th>
                                        <th>Entry Code</th>
                                        <th>CD Code</th>
                                        <th>Pin Code</th>
                                        <th>Remarks</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(isset($unused_codes))
                                        @foreach($unused_codes as $key => $mt)
                                            <tr>
                                                <td>{!! \Carbon\Carbon::parse($mt['date_created'])->format('m/d/Y H:i A') !!}</td>
                                                <td>{!! $mt['to'] !!}</td>
                                                <td>{!! !empty($mt['entry_code']) ? $mt['entry_code'] : '' !!}</td>
                                                <td>{!! !empty($mt['cd_code']) ? $mt['cd_code'] : '' !!}</td>
                                                <td>{!! !empty($mt['pin_code']) ? $mt['pin_code'] : '' !!}</td>
                                                <td>Unused</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    <tr>
                                        <td colspan="6"><b>Totals ==> {{ number_format(count($unused_codes)) }}</b></td>
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
            $("#print").click(function(e){
                e.preventDefault();
                printContent2('print-element');
            });
        });
    </script>
@endsection