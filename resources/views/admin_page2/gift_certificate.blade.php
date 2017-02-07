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
                                <li style="width: 100%;  border: none; margin-bottom: 0px">
                                    <div class="news-item-detail">
                                        <label href="#" class="news-item-title" target="_blank">GC To</label>
                                        <p class="news-item-preview"><input type="text" name="gc_to" style="width: 86%;"></p>
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
                                        <p class="news-item-preview"><input type="text" name="gc_desc" style="width: 86%;"></p>
                                    </div>
                                </li>
                                <li style="width: 100%; border: none; margin-bottom: 0px">
                                    <div class="news-item-detail">
                                        <label href="#" class="news-item-title" target="_blank">GC Amount</label>
                                        <p class="news-item-preview"><input type="text" name="gc_amount" style="width: 86%;"></p>
                                    </div>
                                </li>
                                <li style="width: 100%; border: none; margin-bottom: 0px">
                                    <div class="news-item-detail">
                                        <label href="#" class="news-item-title" target="_blank">Date Created</label>
                                        <p class="news-item-preview"><input type="text" name="gc_created" style="width: 86%;"></p>
                                    </div>
                                </li>
                                <li style="width: 100%">
                                    <div class="news-item-detail">
                                        <label href="#" class="news-item-title" target="_blank">Append</label>
                                         <table cellpadding="5">
                                             <tr>
                                                 <td class="checkbox"><input type="checkbox"> Entry Code</td>
                                                 <td class="checkbox"><input type="checkbox"> Pin Code</td>
                                                 <td class="checkbox"><input type="checkbox"> CD Code</td>
                                             </tr>
                                             <tr>
                                                 <td colspan="3"><input type="text" name="gc_amount" placeholder="No of GC to produce"></td>
                                             </tr>
                                         </table>
                                    </div>
                                </li>
                                <li style="width: 100%">
                                    <div class="news-item-detail">
                                        <button class="button btn btn-primary btn-large">Generate</button>
                                    </div>
                                </li>
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
                        <div class="widget-content">
                            <div style="width: 100%; height: 400px; background-image: url('admin_page/img/gc_mockup.png'); background-repeat: no-repeat; background-position: center center;">
                                <img src="data:image/png;base64, {!! \Milon\Barcode\Facades\DNS1DFacade::getBarcodePNG("Z151784404", "C39+",1,33) !!}" alt="barcode" style="position: absolute; right:80px; bottom: 110px" />
                                <span style="position: absolute; font-size: 16px; font-weight: bold;left: 135px;top: 230px;">Rudy Compayan</span>
                                <span style="position: absolute; font-size: 16px; font-weight: bold;left: 135px;top: 268px;">Windows of Heaven</span>
                                <span style="position: absolute; font-size: 16px; font-weight: bold;left: 135px;top: 304px;">&#8369; 500.00</span>
                            </div>
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