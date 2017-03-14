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
                            <h3>KLP - {!! $member[0]->woh_member !!} {!! $member[0]->first_name !!} {!! $member[0]->last_name !!} / {!! $member[0]->username !!}</h3>
                        </div>
                        <!-- /widget-header -->
                        <div class="widget-content">
                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>Birthday</th>
                                    <th>Gender</th>
                                    <th>Registered On</th>
                                    <th>Total Earnings</th>
                                    <th>Credits</th>
                                    <th>Payments</th>
                                    <th>Total Withdrawals</th>
                                    <th>Current Savings</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(isset($member_tran))
                                    <?php
                                    $earn = 0;
                                    $withdrawals = 0;
                                    $gc = 0;
                                    krsort($member_tran);
                                    $unilevels_total = 0;
                                    $cd_payment = 0;
                                    ?>
                                    @foreach($member_tran as $key => $mt)
                                        <?php
                                        if($mt['woh_transaction_type'] == 1)
                                            if($mt['status'] !=3)
                                                $withdrawals += $mt['tran_amount'];
                                        if($mt['woh_transaction_type'] != 1 && $mt['woh_transaction_type'] != 4)
                                            $earn += $mt['tran_amount'];
                                        if($mt['woh_transaction_type'] == 4)
                                            $gc += $mt['tran_amount'];
                                        if($mt['woh_transaction_type'] == 8)
                                            $unilevels_total += $mt['tran_amount'];
                                        if(!empty($mt['cd_payment']))
                                            $cd_payment += $mt['cd_payment'];
                                        ?>
                                    @endforeach
                                @endif
                                    <tr>
                                        <td>{!! \Carbon\Carbon::parse($member[0]->bday)->format('m/d/Y') !!}</td>
                                        <td>{!! $member[0]['gender'] !!}</td>
                                        <td>{!! \Carbon\Carbon::parse($member[0]['created_at'])->format('m/d/Y H:i A') !!}</td>
                                        <td style="text-align: right">&#8369; {!! number_format(($earn+$unilevels_total),2) !!}</td>
                                        <td style="text-align: right">&#8369; {!! number_format((!empty($member_credit) ? $member_credit[0]['credit_amount'] : 0),2) !!}</td>
                                        <td style="text-align: right">&#8369; {!! number_format($cd_payment,2) !!}</td>
                                        <td style="text-align: right">&#8369; {!! number_format($withdrawals,2) !!}</td>
                                        <td style="text-align: right">&#8369; {!! number_format((($earn+$unilevels_total)-$withdrawals),2) !!}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- /widget-content -->
                        <br />
                        <h3><a href="{{action('AdminController@klp_members')}}">&laquo; Back to KLP Member List</a></h3>
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
<div id="dialog-form3" title="Withdrawal Approval Form">
    {!! Form::open(['data-remote','url' => action('AdminController@post_withdrawal_request_update'), 'id' => 'login_form']) !!}
    <fieldset>
        <div id="form1_error" class="alert alert-danger" role="alert" style="display: none"></div>
        <div class="approve-form">
            <label for="amount">Transaction No.</label>
            <input type="text" name="transaction_no" id="amount" placeholder="123456" class="text ui-widget-content ui-corner-all" style="width: 100%">
            <label for="amount">Check Number.</label>
            <input type="text" name="check_num" id="amount" placeholder="123456" class="text ui-widget-content ui-corner-all" style="width: 100%">
            <label for="amount">Date Issued</label>
            <input type="text" name="issuance_date" id="amount" placeholder="03/01/2017" class="text ui-widget-content ui-corner-all" style="width: 100%">
        </div>
        <label for="admin_notes" class="notes">Admin Notes</label>
        <textarea name="admin_notes" id="admin_notes" placeholder="Some text here." class="text ui-widget-content ui-corner-all notes" style="width:95%; height: 150px;"></textarea>
        <input type="hidden" name="woh_member_transaction" value="" id="woh_member_transaction_input">
        <input type="hidden" name="action" value="" id="action_text">
    </fieldset>
    {!! Form::close() !!}
    <button id="create-user" style="display: none">Create new user</button>
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
<script>
    $(document).ready(function(){
        $('.notes').hide();
        $( ".approve" ).on( "click", function(e) {
            e.preventDefault();
            $('#woh_member_transaction_input').val($(this).data('woh_member_transaction'));
            $('#action_text').val($(this).data('action'));
            $('.notes').hide();
            $('.approve-form').show();
            dialog3.dialog( "open" );
        });

        $( ".cancel" ).on( "click", function(e) {
            e.preventDefault();
            var result = confirm("Are you want to cancel this withdrawal request?");
            if (result) {
                $('#woh_member_transaction_input').val($(this).data('woh_member_transaction'));
                $('#action_text').val($(this).data('action'));
                $('.notes').show();
                $('.approve-form').hide();
                dialog3.dialog( "open" );
            }
        });

        dialog3 = $( "#dialog-form3" ).dialog({
            autoOpen: false,
            height: 305,
            width: 460,
            modal: true,
            buttons: {
                "Save": function(){
                    $('#ajax-loader').fadeIn();
                    $.ajax({

                        type: "POST",

                        url: "{{action('AdminController@post_withdrawal_request_update')}}",

                        data: form3.serialize(),

                        success: function(data, NULL, jqXHR) {
                            $('#ajax-loader').fadeOut();
                            if(jqXHR.status === 200 ) {//redirect if  authenticated user.
                                alert('Transaction is complete.');
                                $( location ).prop( 'pathname', '/withdrawal_request');
                            }
                        },
                        error: function(data) {
                            $('#ajax-loader').fadeOut();
                            if( data.status === 401 ) {//redirect if not authenticated user
                                alert("Member not found!");
                            }
                            if( data.status === 422 ) {
                                //process validation errors here.
                                var err_msg = '';
                                var res = JSON.parse(data.responseText);
                                for (var key in res) {
                                    if (res.hasOwnProperty(key)) {
                                        var obj = res[key];
                                        for (var prop in obj) {
                                            if (obj.hasOwnProperty(prop)) {
                                                err_msg += '<p>'+obj[prop] + "</p>";
                                            }
                                        }
                                    }
                                }
                                $('#first_name').focus();
                                $('#form1_error').html(err_msg);
                                $('#form1_error').fadeIn();
                            }
                        }
                    });
                },
                Cancel: function() {
                    dialog3.dialog( "close" );
                }
            },
            close: function() {
                $('#form1_error').html('');
                $('#form1_error').fadeOut();
                form3[ 0 ].reset();
            },
            show: {
                effect: "blind",
                duration: 1000
            },
            hide: {
                effect: "explode",
                duration: 1000
            }
        });

        form3 = dialog3.find( "form" ).on( "submit", function( event ) {
            event.preventDefault();
        });
    });
</script>
@endsection