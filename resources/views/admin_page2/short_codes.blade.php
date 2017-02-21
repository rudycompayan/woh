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
                            <h3>Short Codes Generator</h3>
                        </div>
                        <!-- /widget-header -->
                        <div class="widget-content">
                            <div class="account-container register">
                                <label style="margin-left:10px"><h3>Choose items to generate</h3></label>
                                @if($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                @if(!empty($error_msg))
                                    <div class="alert alert-success">
                                        <ul>
                                            <li>{!! $error_msg !!}</li>
                                        </ul>
                                    </div>
                                @endif
                                <table class="table table-striped table-bordered">
                                    <tr>
                                        <td>
                                            <form action="{{action('AdminController@post_short_codes')}}" method="post" style="width: 100%">
                                                <div class="login-fields">
                                                    <span class="login-checkbox">
                                                        <input id="Field" name="type[1]" type="checkbox" class="field login-checkbox" value="1" tabindex="1" />
                                                        Entry Codes (<span style="color:#3c763d; font-weight: bold">{!! $short_codes_count['entry_count'] !!}</span>) <span style="color:#9d261d;">codes left</span><br>
                                                        <input id="Field" name="type[2]" type="checkbox" class="field login-checkbox" value="2" tabindex="2" />
                                                        Pin Codes (<span style="color:#3c763d; font-weight: bold">{!! $short_codes_count['pin_count'] !!}</span>) <span style="color:#9d261d;">codes left</span><br>
                                                        <input id="Field" name="type[3]" type="checkbox" class="field login-checkbox" value="3" tabindex="3" />
                                                        CD Codes (<span style="color:#3c763d; font-weight: bold">{!! $short_codes_count['cd_count'] !!}</span>) <span style="color:#9d261d;">codes left</span> <br>
                                                        <input id="Field" name="type[4]" type="checkbox" class="field login-checkbox" value="4" tabindex="3" />
                                                        Bar Codes (<span style="color:#3c763d; font-weight: bold">{!! $short_codes_count['bar_count'] !!}</span>) <span style="color:#9d261d;">codes left</span> <br>
                                                        <input id="Field" name="type[5]" type="checkbox" class="field login-checkbox" value="5" tabindex="4" />
                                                        Product Codes (<span style="color:#3c763d; font-weight: bold">{!! $short_codes_count['product_code_count'] !!}</span>) <span style="color:#9d261d;">codes left</span>
                                                    </span>
                                                    <div class="field">
                                                        <br>
                                                        <input type="text" id="firstname" name="max_no" tabindex="4" value="" placeholder="Max no." class="login" />
                                                    </div> <!-- /field -->
                                                </div> <!-- /login-fields -->
                                                <div class="login-actions">
                                                    <label></label>
                                                    <button class="button btn btn-primary btn-large">Generate</button>
                                                </div> <!-- .actions -->
                                            </form>
                                        </td>
                                    </tr>
                                </table>
                            </div> <!-- /account-container -->
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
<script>
    $(document).ready(function () {
        $('input[name="max_no"]').keyup(function(e)
        {
            var txtQty = $(this).val().replace(/[^0-9\.]/g,'');
            $(this).val(txtQty);
        });
    });
</script>
@endsection