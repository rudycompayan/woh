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
                            <h3>KLP Members</h3>
                        </div>
                        <!-- /widget-header -->
                        <div class="widget-content"   style="overflow: scroll; max-height: 500px">
                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>ID #</th>
                                    <th>Member Name</th>
                                    <th>Username</th>
                                    <th>Registered On</th>
                                    <th>Account Type</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(isset($klp_member))
                                    @foreach($klp_member as $key => $mt)
                                        <tr>
                                            <td>{!! $mt['woh_member'] !!}</td>
                                            <td>{!! $mt['first_name'] !!} {!! $mt['last_name'] !!}</td>
                                            <td>{!! $mt['username'] !!}</td>
                                            <td>{!! \Carbon\Carbon::parse($mt['created_at'])->format('m/d/Y H:i A') !!}</td>
                                            <td>{!! isset($mt['cd_code']) ? 'Comission Deduction' : 'Payin' !!}</td>
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