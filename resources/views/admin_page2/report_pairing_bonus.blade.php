@extends('admin_dashboard')
@section('content')
    <!-- /subnavbar -->
    <div class="main clearfix">
        <div class="main-inner">
            <div class="container">
                <div class="row">
                    <div class="span12">
                        <div class="widget widget-table action-table" id="print-element">
                            <form action="{{action('AdminController@post_filter_pairing_bonus')}}" method="post">
                                <div class="widget-header"> <i class="icon-th-list"></i>
                                    <h3>Pairing Bonus Report</h3>
                                    <input type="text" style="margin-top: 6px;" name="name" placeholder="Search here..." value="{{ $name }}">
                                    <input type="submit" value="Search" style="margin-top: 6px;" name="submit">
                                    <input type="submit" value="Print Report" style="margin-top: 6px;" name="print" id="print">
                                </div>
                            </form>
                            <!-- /widget-header -->
                            <div class="widget-content">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th colspan="4">Member Details</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(isset($all_report))
                                        @foreach($all_report as $key => $member_report)
                                            @if(!empty($member_report[0]))
                                                <?php
                                                $earn = 0;
                                                $gc = 0;
                                                $level = 0;
                                                ?>
                                                <tr>
                                                    <th>{!! $key !!}</th>
                                                    <th>Paring Level</th>
                                                    <th>Amount Earn</th>
                                                    <th>GC Earn</th>
                                                </tr>
                                                @foreach($member_report[0] as $mr)
                                                    <tr>
                                                        <td></td>
                                                        <td>{{ $mr['level'] }}</td>
                                                        <td>&#8369; {!! $mr['woh_transaction_type'] == 5 ? number_format($mr['tran_amount'],2) : number_format(0,2) !!}</td>
                                                        <td>&#8369; {!! $mr['woh_transaction_type'] == 4 ? number_format($mr['tran_amount'],2) : number_format(0,2) !!}</td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    @endif
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