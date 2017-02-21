@extends('admin_dashboard')
@section('content')
        <!-- /subnavbar -->
<div class="main clearfix">
    <div class="main-inner">
        <div class="container">
            <div class="row">
                <div class="span4">
                    <div class="widget widget-nopad">
                        <div class="widget-header"> <i class="icon-list-alt"></i>
                            <h3>Redeem Gift Certificate</h3>
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
                                @if(!empty($error_msg))
                                    <div class="alert alert-danger">
                                        <ul>
                                            <li>{!! $error_msg !!}</li>
                                        </ul>
                                    </div>
                                @endif
                                @if(!empty($success_msg))
                                    <div class="alert alert-success">
                                        <ul>
                                            <li>{!! $success_msg !!}</li>
                                        </ul>
                                    </div>
                                @endif
                                <form action="{{action('AdminController@redeem_gc')}}" method="post">
                                    <li style="width: 100%;  border: none; margin-bottom: 0px">
                                        <div class="news-item-detail">
                                            <label href="#" class="news-item-title" target="_blank">Please Enter Barcode</label>
                                            <p class="news-item-preview">
                                                <input type="text" name="barcode" style="width: 86%;" value="">
                                            </p>
                                        </div>
                                    </li>
                                    <li style="width: 100%">
                                        <div class="news-item-detail">
                                            <button class="button btn btn-primary btn-large" onclick="this.form.submit()">Redeem</button>
                                        </div>
                                    </li>
                                </form>
                            </ul>
                        </div>
                        <!-- /widget-content -->
                    </div>
                </div>
                <div class="span8">
                    <div class="widget widget-nopad">
                        <div class="widget-header"> <i class="icon-list-alt"></i>
                            <h3>Gift Certificate Preview</h3>
                        </div>
                        <!-- /widget-header -->
                        <div class="widget-content" style="overflow: auto; height: 340px" id="print-element">
                            @if(!empty($gc))
                                @foreach($gc as $g)
                                    <table width="100%" height="0%" border="0" style="background-size: 98%;background-image: url('admin_page/img/gc_mockup2.png'); background-repeat: no-repeat; background-position: center center;">
                                        <tr>
                                            <td align="center" style="border-bottom: 1px dotted #000000">
                                                <table border="0" style="width: 95%!important;font-size: 16px;margin: 63px 0px;">
                                                    <tr>
                                                        <td colspan="2"><img src="data:image/png;base64, {!! \Milon\Barcode\Facades\DNS1DFacade::getBarcodePNG($g['bar_code'], "C39",1,33) !!}" alt="barcode" /></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" align="left" style="letter-spacing: 11px; text-align: left"><small> {!! $g['bar_code'] !!}</small></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" style="height: 10px"></td>
                                                    </tr>
                                                    <tr>
                                                        @if(isset($g['entry_code']))
                                                            <td colspan="2"><font style="color: #ff2d2d;-webkit-text-stroke: 0.5px #ffffff;"><b>ENTRY CODE:</b> </font><b>{!! $g['entry_code'] !!}</b></td>
                                                        @elseif(isset($g['cd_code']))
                                                            <td colspan="2"><font style="color: #ff2d2d;-webkit-text-stroke: 0.5px #ffffff"><b>CD CODE:</b> </font> <b>{!! $g['cd_code'] !!}</b></td>
                                                        @elseif(isset($g['product_code']))
                                                            <td colspan="2"><font style="color: #ff2d2d;-webkit-text-stroke: 0.5px #ffffff"><b>PRODUCT CODE:</b> </font> <b>{!! $g['product_code'] !!}</b></td>
                                                        @else
                                                            <td colspan="2">&nbsp;</td>
                                                        @endif
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2"><?= (isset($g['pin_code']) ? " <font style=\"color: #ff2d2d;-webkit-text-stroke: 0.5px #ffffff\"><b>PIN CODE:</b> </font> <b>".$g['pin_code']."</b>"  : "&nbsp;") ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2"><font style="color: #ff2d2d;-webkit-text-stroke: 0.5px #ffffff"><b>To:</b></font> <b>{!! $g['to'] !!}</b></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" align="center" style="font-size: 80px;padding-bottom: 30px;-webkit-text-stroke: 1px #000000; color:#ff2d2d; font-family: 'Times New Roman'"><b>&#8369;{!! number_format($g['amount']) !!}</b></td>
                                                    </tr>
                                                    <!--tr>
                                                        <td colspan="2">From: <b>Windows Of Heaven</b></td>
                                                    </tr-->
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                            @endif
                        </div>
                        <ul class="news-items">
                            <li style="width: 100%">
                                <div class="news-item-detail">
                                    <button class="button btn btn-primary btn-large" id="print">Print</button>
                                </div>
                            </li>
                        </ul>
                        <!-- /widget-content -->
                    </div>
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

        $("#print").click(function(e){
            e.preventDefault();
            printContent2('print-element');
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
    });
</script>
@endsection