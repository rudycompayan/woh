@extends('admin_dashboard')
@section('content')
        <!-- /subnavbar -->
<div class="main clearfix">
    <div class="main-inner">
        <div class="container">
            <div class="row">
                <div class="span12">
                    <div class="widget widget-nopad">
                        <div class="widget-header"> <i class="icon-list-alt"></i>
                            <h3>Change Password</h3>
                        </div>
                        <!-- /widget-header -->
                        <div class="widget-content">
                            <ul class="news-items">
                                <div id="form1_error" class="alert alert-danger" role="alert" style="display: none"></div>
                                <form action="{{action('AdminController@post_change_password')}}" method="post">
                                    <li style="width: 100%">
                                        <div class="news-item-detail">
                                            <table cellpadding="5">
                                                <tr>
                                                    <td>Current Password:</td>
                                                    <td><b>{!! $user[0]['password'] !!}</b></td>
                                                </tr>
                                                <tr>
                                                    <td>New Password:</td>
                                                    <td><input type="password" id="password" name="password"></td>
                                                </tr>
                                                <tr>
                                                    <td>Retype New Password:</td>
                                                    <td><input type="password" id="re-password" name="re-password"></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </li>
                                    <li style="width: 100%">
                                        <div class="news-item-detail">
                                            <button class="button btn btn-primary btn-large" id="generate" onclick="this.form.submit()">Save</button>
                                        </div>
                                    </li>
                                    <input type="hidden" value="{!! $user[0]['woh_admin'] !!}" name="woh_admin">
                                </form>
                            </ul>
                        </div>
                        <!-- /widget-content -->
                    </div>
                </div>
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
<style>
    .alert.alert-danger li {
        margin-bottom: 0px!important;
        padding: 0px!important;
    }
    @media print
    {
        #print-element { display: block; }
        table {
            width:53%
        }
    }
</style>
<script src="admin_page/js/jquery-1.7.2.min.js"></script>
<script src="admin_page/js/excanvas.min.js"></script>
<script src="admin_page/js/chart.min.js" type="text/javascript"></script>
<script src="admin_page/js/bootstrap.js"></script>
<script language="javascript" type="text/javascript" src="admin_page/js/full-calendar/fullcalendar.min.js"></script>

<script src="admin_page/js/base.js"></script>
<script>
    $(document).ready(function () {
        $('#re-password').blur(function()
        {
            if($(this).val() != $('#password').val())
            {
                $('#form1_error').html('<p>Password does not match.</p>');
                $('#form1_error').fadeIn();
            }
            else
            {
                $('#form1_error').html('');
                $('#form1_error').fadeOut();
            }
        });

        $('#password').blur(function(){
            if($('#re-password').val() && $(this).val() != $('#re-password').val())
            {
                $('#form1_error').html('<p>Password does not match.</p>');
                $('#form1_error').fadeIn();
            }
            else
            {
                $('#form1_error').html('');
                $('#form1_error').fadeOut();
            }
        });
    });
</script>
@endsection