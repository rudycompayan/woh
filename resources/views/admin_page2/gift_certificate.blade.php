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
                            <h3>Gift Certificate Generator</h3>
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
                                <form action="{{action('AdminController@gift_certificates')}}" method="post">
                                    <li style="width: 100%;  border: none; margin-bottom: 0px">
                                        <div class="news-item-detail">
                                            <label href="#" class="news-item-title" target="_blank">GC To</label>
                                            <p class="news-item-preview">
                                                <input type="text" name="gc_to" style="width: 86%;" value="Walk In">
                                            </p>
                                        </div>
                                    </li>
                                    <li style="width: 100%; border: none; margin-bottom: 0px;">
                                        <div class="news-item-detail">
                                            <label href="#" class="news-item-title" target="_blank">GC Name</label>
                                            <p class="news-item-preview"><input type="text" name="gc_name" style="width: 86%;"></p>
                                        </div>
                                    </li>
                                    <li style="width: 100%; border: none; margin-bottom: 0px">
                                        <div class="news-item-detail">
                                            <label href="#" class="news-item-title" target="_blank">GC Description</label>
                                            <p class="news-item-preview"><input type="text" name="gc_description" style="width: 86%;"></p>
                                        </div>
                                    </li>
                                    <li style="width: 100%; border: none; margin-bottom: 0px">
                                        <div class="news-item-detail">
                                            <label href="#" class="news-item-title" target="_blank">GC Amount</label>
                                            <p class="news-item-preview"><input type="text" name="gc_amount" style="width: 86%;"></p>
                                        </div>
                                    </li>
                                    <li style="width: 100%">
                                        <div class="news-item-detail">
                                            <label href="#" class="news-item-title" target="_blank"  class="entry">Append</label>
                                             <table cellpadding="5">
                                                 <tr>
                                                     <td class="checkbox entry"><input type="radio" class="code" name="code" value="1"> Entry Code</td>
                                                     <td class="checkbox entry"><input type="radio" class="code"  name="code" value="2"> CD Code</td>
                                                 </tr>
                                                 <tr>
                                                     <td colspan="3"><input type="text" name="gc_number" placeholder="No of GC to produce"></td>
                                                 </tr>
                                             </table>
                                        </div>
                                    </li>
                                    <li style="width: 100%">
                                        <div class="news-item-detail">
                                            <button class="button btn btn-primary btn-large" onclick="this.form.submit()">Generate</button>
                                            <a href="{{action('AdminController@gift_certificates')}}"><button class="button btn btn-primary btn-large">Refresh</button></a>
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
                            <h3>Gift Certificate Preview (Sample)</h3>
                        </div>
                        <!-- /widget-header -->
                        <div class="widget-content" style="overflow: auto; height: 480px">
                            @if(!empty($gc))
                                @foreach($gc as $g)
                                    <div style="width: 100%; height: 400px; background-image: url('admin_page/img/gc_mockup.png'); background-repeat: no-repeat; background-position: center center;">
                                        <img src="data:image/png;base64, {!! \Milon\Barcode\Facades\DNS1DFacade::getBarcodePNG($g['bar_code'], "C39+",1,33) !!}" alt="barcode"
                                             style="position: relative; left:490px; top: 310px" />
                                        <span style="position: relative; font-size: 16px; font-weight: bold;left: -75px; top: 186px;">{!! $g['to'] !!}</span>
                                        <span style="position: relative; font-size: 16px; font-weight: bold;left: -174px;top: 224px;">Windows of Heaven</span>
                                        <span style="position: relative; font-size: 16px; font-weight: bold;left: -329px;top: 261px;">&#8369; {!! $g['amount'] !!}</span>
                                        <span style="position: relative; font-size: 16px; font-weight: bold;left: -460px;top: 345px; color:#0d6895;">PIN CODE: {!! $g['pin_code'] !!}</span>
                                        <span style="position: relative; font-size: 18px; font-weight: bold;left: 75px;top: 290px; color: #0b791d">ENTRY CODE: {!! $g['entry_code'] !!}</span>
                                        <span style="left: 264px;letter-spacing: 15px;position: relative;top: 308px;"><small> {!! $g['bar_code'] !!}</small></span>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <ul class="news-items">
                            <li style="width: 100%">
                                <div class="news-item-detail">
                                    <button class="button btn btn-primary btn-large">Print</button>
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
    });
</script>
@endsection