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
                                        <th>Account Type</th>
                                        <th>Requested On</th>
                                        <th>GC Quantity</th>
                                        <th>Notes</th>
                                        <th class="td-actions">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(isset($member_gc))
                                        @foreach($member_gc as $key => $mt)
                                            <tr>
                                                <td>{!! $mt['woh_member_gc'] !!}</td>
                                                <td>{!! $mt['first_name'] !!} {!! $mt['last_name'] !!}</td>
                                                <td><?= $mt['status'] == 0 ? 'CD' : 'Payin' ?></td>
                                                <td>{!! \Carbon\Carbon::parse($mt['date_claim'])->format('m/d/Y H:i A') !!}</td>
                                                <td>{!! $mt['gc_qty'] !!}</td>
                                                <td>{!! $mt['notes'] !!}</td>
                                                <td class="td-actions">
                                                    <a href="" class="btn btn-small btn-success approve"  data-woh_member_gc="{!! $mt['woh_member_gc'] !!}" data-action="approve">
                                                        <i class="btn-icon-only icon-ok"> </i>
                                                    </a>
                                                </td>
                                            </tr>
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
    <div id="dialog-form3" title="GC Claim Approval Form">
        {!! Form::open(['data-remote','url' => action('AdminController@post_withdrawal_request_update'), 'id' => 'login_form']) !!}
        <fieldset>
            <div id="form1_error" class="alert alert-danger" role="alert" style="display: none"></div>
            <div class="approve-form">
                <label for="amount">Enter Barcode</label>
                <input type="text" name="barcode" id="amount" placeholder="123456xxxx" class="text ui-widget-content ui-corner-all" style="width: 100%">
            </div>
            <label for="admin_notes" class="notes">Admin Notes</label>
            <textarea name="admin_notes" id="admin_notes" placeholder="Some text here." class="text ui-widget-content ui-corner-all notes" style="width:95%; height: 54px;"></textarea>
            <input type="hidden" name="woh_member_gc" value="" id="woh_member_transaction_input">
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
                $('#woh_member_transaction_input').val($(this).data('woh_member_gc'));
                $('#action_text').val($(this).data('action'));
                $('.notes').hide();
                $('.approve-form').show();
                dialog3.dialog( "open" );
            });

            $( ".cancel" ).on( "click", function(e) {
                e.preventDefault();
                var result = confirm("Are you want to cancel this GC claim request?");
                if (result) {
                    $('#woh_member_transaction_input').val($(this).data('woh_member_gc'));
                    $('#action_text').val($(this).data('action'));
                    $('.notes').show();
                    $('.approve-form').hide();
                    dialog3.dialog( "open" );
                }
            });

            dialog3 = $( "#dialog-form3" ).dialog({
                autoOpen: false,
                height: 205,
                width: 460,
                modal: true,
                buttons: {
                    "Save": function(){
                        if(!$('#amount').val())
                        {
                            alert('Barcode is required.');
                            return false;
                        }
                        $('#ajax-loader').fadeIn();
                        $.ajax({

                            type: "POST",

                            url: "{{action('AdminController@post_gc_claim_request')}}",

                            data: form3.serialize(),

                            success: function(data, NULL, jqXHR) {
                                $('#ajax-loader').fadeOut();
                                if(jqXHR.status === 200 ) {//redirect if  authenticated user.
                                    alert('Transaction is complete.');
                                    $( location ).prop( 'pathname', '/gc_claim_request');
                                }
                            },
                            error: function(data) {
                                $('#ajax-loader').fadeOut();
                                if( data.status === 401 ) {//redirect if not authenticated user
                                    alert("GC not found.");
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