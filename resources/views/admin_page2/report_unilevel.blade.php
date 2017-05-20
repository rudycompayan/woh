@extends('admin_dashboard')
@section('content')
    <!-- /subnavbar -->
    <div class="main clearfix">
        <div class="main-inner">
            <div class="container">
                <div class="row">
                    <div class="span12">
                        <div class="widget widget-table action-table">
                            <form action="{{action('AdminController@post_filter_unilevel')}}" method="post">
                                <div class="widget-header"> <i class="icon-th-list"></i>
                                    <h3>Member Unilevel Report</h3>
                                    <input type="text" style="margin-top: 6px;" name="name" placeholder="Search here..." value="{{ $name }}">
                                    <input type="submit" value="Search" style="margin-top: 6px;" name="submit">
                                </div>
                            </form>
                            <!-- /widget-header -->
                            <div class="widget-content"   style="overflow: scroll; max-height: 500px">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Member Details</th>
                                        <th>Period Cover</th>
                                        <th>Maintain?</th>
                                        <th>Level 1</th>
                                        <th>Level 2</th>
                                        <th>Level 3</th>
                                        <th>Level 4</th>
                                        <th>Level 5</th>
                                        <th style="font-weight: bold">Total</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $footer_total = 0;
                                    ?>
                                    @if(isset($unilevel_period))
                                        @foreach($unilevel_period as $up)
                                                <?php
                                                $footer_total = $footer_total + $up['level1_earn']+$up['level2_earn']+$up['level3_earn']+$up['level4_earn']+$up['level5_earn'];
                                                ?>
                                                <tr>
                                                    <td>{!! $up['woh_member']." - ".$up['first_name'].' '.$up['last_name']." (".$up['username'].")" !!}</td>
                                                    <td>{!! \Carbon\Carbon::parse($up['period_cover_start'])->format('F d, Y').' - '.\Carbon\Carbon::parse($up['period_cover_end'])->format('F d, Y') !!}</td>
                                                    <td>{!! $up['status'] == 1 ? 'Yes' : 'No' !!}</td>
                                                    <td>&#8369; {!! number_format($up['level1_earn'],2) !!}</td>
                                                    <td>&#8369; {!! number_format($up['level2_earn'],2) !!}</td>
                                                    <td>&#8369; {!! number_format($up['level3_earn'],2) !!}</td>
                                                    <td>&#8369; {!! number_format($up['level4_earn'],2) !!}</td>
                                                    <td>&#8369; {!! number_format($up['level5_earn'],2) !!}</td>
                                                    <td style="font-weight: bold">&#8369; {!! number_format($up['level1_earn']+$up['level2_earn']+$up['level3_earn']+$up['level4_earn']+$up['level5_earn'],2) !!}</td>
                                                </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="8" style="font-weight: bold">Totals ==></td>
                                            <td style="font-weight: bold">&#8369; {!! number_format($footer_total,2) !!}</td>
                                        </tr>
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
        });
    </script>
@endsection