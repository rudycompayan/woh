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
                            <h3>Member's Withdrawal Request</h3>
                        </div>
                        <!-- /widget-header -->
                        <div class="widget-content">
                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>Request #</th>
                                    <th>Requested By</th>
                                    <th>Requested On</th>
                                    <th>Notes</th>
                                    <th>Status</th>
                                    <th>Amount</th>
                                    <th class="td-actions">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(isset($member_tran))
                                    <?php $withdrawals = 0; ?>
                                    @foreach($member_tran as $key => $mt)
                                        <?php $withdrawals += $mt['tran_amount']; ?>
                                        <tr>
                                            <td>{!! $mt['woh_member_transaction'] !!}</td>
                                            <td>{!! $mt['first_name'] !!} {!! $mt['last_name'] !!}</td>
                                            <td>{!! \Carbon\Carbon::parse($mt['transaction_date'])->format('m/d/Y H:i A') !!}</td>
                                            <td>{!! $mt['notes'] !!}</td>
                                            <td>
                                                @if($mt['status'] == 1)
                                                    Complete
                                                @elseif($mt['status'] == 2)
                                                    Pending
                                                @else
                                                    Disapproved
                                                @endif
                                            </td>
                                            <td style="text-align: right">&#8369; {!! number_format($mt['tran_amount'],2) !!}</td>
                                            <td class="td-actions">
                                                <a href="" class="btn btn-small btn-success approve"  data-woh_member_transaction="{!! $mt['woh_member_transaction'] !!}" data-action="approve">
                                                    <i class="btn-icon-only icon-ok"> </i>
                                                </a>
                                                <a href="" class="btn btn-danger btn-small cancel" data-woh_member_transaction="{!! $mt['woh_member_transaction'] !!}" data-action="cancel">
                                                    <i class="btn-icon-only icon-remove"> </i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                <tr>
                                    <td colspan="5"><b>Totals ==></b></td>
                                    <td style="text-align: right"><b>&#8369; {{ number_format($withdrawals,2) }}</b></td>
                                    <td class="td-actions"> </td>
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
<div id="dialog-form3" title="Withdrawal Approval Form">
    {!! Form::open(['data-remote','url' => action('AdminController@post_withdrawal_request_update'), 'id' => 'login_form']) !!}
    <fieldset>
        <div id="form1_error" class="alert alert-danger" role="alert" style="display: none"></div>
        <label for="amount">Transaction No.</label>
        <input type="text" name="transaction_no" id="amount" placeholder="123456" class="text ui-widget-content ui-corner-all" style="width: 100%">
        <label for="amount">Check Number.</label>
        <input type="text" name="check_num" id="amount" placeholder="123456" class="text ui-widget-content ui-corner-all" style="width: 100%">
        <label for="amount">Date Issued</label>
        <input type="text" name="issuance_date" id="amount" placeholder="03/01/2017" class="text ui-widget-content ui-corner-all" style="width: 100%">
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
        $( ".approve" ).on( "click", function(e) {
            e.preventDefault();
            $('#woh_member_transaction_input').val($(this).data('woh_member_transaction'));
            $('#action_text').val($(this).data('action'));
            dialog3.dialog( "open" );
        });

        $( ".cancel" ).on( "click", function(e) {
            e.preventDefault();
            var result = confirm("Are you want to cancel this withdrawal request?");
            if (result) {
                $('#woh_member_transaction_input').val($(this).data('woh_member_transaction'));
                $('#action_text').val($(this).data('action'));
                $('#ajax-loader').fadeIn();
                alert(form3.prop('action'));
                $.ajax({

                    type: "POST",

                    url: "{{action('AdminController@post_withdrawal_request_update')}}",

                    data: form3.serialize(),

                    success: function (data, NULL, jqXHR) {
                        $('#ajax-loader').fadeOut();
                        if (jqXHR.status === 200) {//redirect if  authenticated user.
                            $(location).prop('pathname', '/withdrawal_request');
                        }
                    },
                    error: function (data) {
                        $('#ajax-loader').fadeOut();
                        if (data.status === 401) {//redirect if not authenticated user
                            alert("Member not found!");
                        }
                        if (data.status === 422) {
                            //process validation errors here.
                            var err_msg = '';
                            var res = JSON.parse(data.responseText);
                            for (var key in res) {
                                if (res.hasOwnProperty(key)) {
                                    var obj = res[key];
                                    for (var prop in obj) {
                                        if (obj.hasOwnProperty(prop)) {
                                            err_msg += '<p>' + obj[prop] + "</p>";
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