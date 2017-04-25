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
                                <h3>Member's Withdrawal Report</h3>
                                From: <input type="text" id="start_date" style="margin-top: 6px;"> To:
                                <input type="text" id="end_date" style="margin-top: 6px;">
                            </div>
                            <!-- /widget-header -->
                            <div class="widget-content">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Request #</th>
                                        <th>Member's Name</th>
                                        <th>CD Account?</th>
                                        <th>Date Approved</th>
                                        <th>Status</th>
                                        <th style="text-align: right">Amount</th>
                                        <th style="text-align: right">Tax</th>
                                        <th style="text-align: right">CD Payment</th>
                                        <th style="text-align: right">Change</th>
                                        <th style="text-align: right">Check Amt</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(isset($member_tran))
                                        <?php $withdrawals = 0;  $tax = 0; $cd_payment = 0; $check_amt = 0; $change = 0; ?>
                                        @foreach($member_tran as $key => $mt)
                                            <?php $withdrawals += $mt['tran_amount']; ?>
                                            <?php $tax += $mt['tax']; ?>
                                            <?php $cd_payment += $mt['cd_payment']; ?>
                                            <?php $change += $mt['change']; ?>
                                            <?php
                                            if(isset($mt['cd_payment']) && $mt['cd_payment'] > 0)
                                                $check_amt += ($mt['tran_amount']-$mt['tax'])/2;
                                            else
                                                $check_amt += $mt['tran_amount']-$mt['tax'];
                                            ?>
                                            <tr>
                                                <td>{!! $mt['woh_member_transaction'] !!}</td>
                                                <td>{!! $mt['first_name'] !!} {!! $mt['last_name'] !!}</td>
                                                <td><?= $mt['m_status'] == 0 ? 'Yes' : 'No' ?></td>
                                                <td>{!! \Carbon\Carbon::parse($mt['transaction_date'])->format('m/d/Y H:i A') !!}</td>
                                                <td>
                                                    @if($mt['w_status'] == 1)
                                                        Complete
                                                    @elseif($mt['w_status'] == 2)
                                                        Pending
                                                    @else
                                                        Disapproved
                                                    @endif
                                                </td>
                                                <td style="text-align: right">&#8369; {!! number_format($mt['tran_amount'],2) !!}</td>
                                                <td style="text-align: right">&#8369; {!! number_format($mt['tax'],2) !!}</td>
                                                <td style="text-align: right">&#8369; {!! number_format($mt['cd_payment'],2) !!}</td>
                                                <td style="text-align: right">&#8369; {!! number_format($mt['change'],2) !!}</td>
                                                @if(isset($mt['cd_payment']) && $mt['cd_payment'] > 0)
                                                    <td style="text-align: right">&#8369; {!! number_format((($mt['tran_amount']-$mt['tax'])/2)+$mt['change'],2) !!}</td>
                                                @else
                                                    <td style="text-align: right">&#8369; {!! number_format(($mt['tran_amount']-$mt['tax']),2) !!}</td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    @endif
                                    <tr>
                                        <td colspan="5"><b>Totals ==></b></td>
                                        <td style="text-align: right"><b>&#8369; {{ number_format($withdrawals,2) }}</b></td>
                                        <td style="text-align: right"><b>&#8369; {{ number_format($tax,2) }}</b></td>
                                        <td style="text-align: right"><b>&#8369; {{ number_format($cd_payment,2) }}</b></td>
                                        <td style="text-align: right"><b>&#8369; {{ number_format($change,2) }}</b></td>
                                        <td style="text-align: right"><b>&#8369; {{ number_format($check_amt + $change,2) }}</b></td>
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
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"><link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
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