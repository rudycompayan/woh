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
                            <h3>Gift Certificate Controller</h3>
                        </div>
                        <!-- /widget-header -->
                        <div class="widget-content">
                            <ul class="news-items">
                                @if($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <form action="{{action('AdminController@gc_set')}}" method="post">
                                    <li style="width: 100%">
                                        <div class="news-item-detail">
                                             <table cellpadding="5">
                                                 <tr>
                                                     <td class="checkbox entry"><input type="checkbox" class="code" name="entry_code" value="1"> Entry Code ({{ !empty($gc_set) ? $gc_set[0]['entry_code'] : 0}})</td>
                                                     <td class="checkbox entry"><input type="checkbox" class="code"  name="cd_code" value="1"> CD Code ({{ !empty($gc_set) ?  $gc_set[0]['cd_code'] :0  }})</td>
                                                     <td class="checkbox entry"><input type="checkbox" class="code"  name="product_code" value="1"> Product Code ({{ !empty($gc_set) ?  $gc_set[0]['product_code'] : 0}})</td>
                                                 </tr>
                                                 <tr>
                                                     <td colspan="3"><input type="text" name="max_number" placeholder="No of GC to produce"></td>
                                                 </tr>
                                             </table>
                                        </div>
                                    </li>
                                    <li style="width: 100%">
                                        <div class="news-item-detail">
                                            <button class="button btn btn-primary btn-large" id="generate" onclick="this.form.submit()">Create</button>
                                            <a href="{{action('AdminController@gc_set')}}"><button class="button btn btn-primary btn-large">Refresh</button></a>
                                        </div>
                                    </li>
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
        $('#gc_to').change(function(){
            if($(this).val() == 0)
            {
                $('.entry').hide();
            }
            else {
                $('.entry').show();
            }
        });

        $('input[name="max_no"]').keyup(function(e)
        {
            var txtQty = $(this).val().replace(/[^0-9\.]/g,'');
            $(this).val(txtQty);
        });

        $('input[name="gc_amount"]').keyup(function(e)
        {
            var txtQty = $(this).val().replace(/[^0-9\.]/g,'');
            $(this).val(txtQty);
        });

        $('.code').click(function () {
            $('#pin_code').prop('checked',true);
            $('#pin_code').prop('disabled',true);
            $('input[name="gc_number"]').val(4);
            $('input[name="gc_number"]').prop('readonly',true)
        });

        function printContent2(div_id)
        {
            var content = document.getElementById(div_id);
            var map_src = window.open("", "PRINT MAP", "width=800,height=600,top=0,left=0,toolbar=no,scrollbars=no,status=no,resizable=no");
            map_src.document.write('<html><head>');
            //map_src.document.write('<link rel="stylesheet"  href="/leaflet-0.5.1/0.7.3/leaflet.css"/>'); ** Remove this line
            map_src.document.write("</head><body><div style='height:400px; font-family: \"Helvetica Neue\", Helvetica, Arial, sans-serif'>");
            map_src.document.write(content.innerHTML);
            map_src.document.write('</div></body></html>');
            map_src.document.close();
            map_src.focus();
            map_src.print();
        }

        var err_msg ='';

        @if(!empty($short_codes_count) && $short_codes_count['entry_count'] == 0)
            err_msg = err_msg + "Entry code is running out. Please generate!\n";
        @endif
        @if(!empty($short_codes_count) && $short_codes_count['cd_count'] == 0)
            err_msg = err_msg + "CD code is running out. Please generate!\n";
        @endif
        @if(!empty($short_codes_count) && $short_codes_count['pin_count'] == 0)
            err_msg = err_msg + "Pin code is running out. Please generate!\n";
        @endif
        @if(!empty($short_codes_count) && $short_codes_count['bar_count'] < 4)
            err_msg = err_msg + "Bar code is running out. Please generate!\n";
        @endif
         @if(!empty($short_codes_count) && $short_codes_count['product_code_count'] == 0)
                err_msg = err_msg + "Product code is running out. Please generate!\n";
        @endif

        if(err_msg)
        {
            alert(err_msg);
            $('#generate').hide();
            location.href ='/short_codes';
        }
        else
            $('#generate').show();

    });
</script>
@endsection