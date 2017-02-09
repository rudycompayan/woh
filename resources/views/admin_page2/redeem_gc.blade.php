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
                        <div class="widget-content" style="overflow: auto; height: 480px" id="print-element">
                            @if(!empty($gc))
                                @foreach($gc as $g)
                                    <table width="100%" height="90%" border="0" style="background-image: url('admin_page/img/gc_mockup.png'); background-repeat: no-repeat; background-position: center center;">
                                        <tr>
                                            <td align="center">
                                                <table border="0" style="width: 80%!important;font-size: 16px;margin-top: 160px;">
                                                    <tr>
                                                        <td colspan="2">To: <b>{!! $g['to'] !!}</b></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">From: <b>Windows Of Heaven</b></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">Amount: <b>&#8369; {!! $g['amount'] !!}</b></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" style="height: 40px"></td>
                                                    </tr>
                                                    <tr>
                                                        @if(isset($g['entry_code']))
                                                            <td>ENTRY CODE: <b>{!! $g['entry_code'] !!}</b></td>
                                                        @elseif(isset($g['cd_code']))
                                                            <td>CD CODE: <b>{!! $g['cd_code'] !!}</b></td>
                                                        @else
                                                            <td>&nbsp;</td>
                                                        @endif
                                                        <td align="right"><img src="data:image/png;base64, {!! \Milon\Barcode\Facades\DNS1DFacade::getBarcodePNG($g['bar_code'], "C39",1,33) !!}" alt="barcode" /></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?= (isset($g['pin_code']) ? " PIN CODE: <b>".$g['pin_code']."</b>"  : "&nbsp;") ?></td>
                                                        <td align="right" style="letter-spacing: 9px; text-align: right"><small> {!! $g['bar_code'] !!}</small></td>
                                                    </tr>
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