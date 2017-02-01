@extends('dashboard')
@section('content')

        <!--content-->
<div class="content indent">
    <div class="container">
        <h2>Contact form</h2>
        <form id="contact-form">
            <div class="contact-form-loader"></div>
            <fieldset>
                <label class="name form-div-1">
                    <input type="text" name="name" placeholder="Name" value="" data-constraints="@Required @JustLetters"  />
                    <span class="empty-message">*This field is required.</span>
                    <span class="error-message">*This is not a valid name.</span>
                </label>
                <label class="phone form-div-2">
                    <input type="text" name="phone" placeholder="Phone" value="" data-constraints="@JustNumbers" />
                    <span class="empty-message">*This field is required.</span>
                    <span class="error-message">*This is not a valid phone.</span>
                </label>
                <label class="email form-div-3">
                    <input type="text" name="email" placeholder="Email" value="" data-constraints="@Required @Email" />
                    <span class="empty-message">*This field is required.</span>
                    <span class="error-message">*This is not a valid email.</span>
                </label>
                <label class="message form-div-4">
                    <textarea name="message" placeholder="Message" data-constraints='@Required @Length(min=20,max=999999)'></textarea>
                    <span class="empty-message">*This field is required.</span>
                    <span class="error-message">*The message is too short.</span>
                </label>
                <!-- <label class="recaptcha"><span class="empty-message">*This field is required.</span></label> -->
                <div class="btns">
                    <a href="#" data-type="submit" class="btn-default btn1" data-text="Send">Send</a>
                </div>
            </fieldset>
            <div class="modal fade response-message">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title">Modal title</h4>
                        </div>
                        <div class="modal-body">
                            You message has been sent! We will be in touch soon.
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <section class="content_map">
        <div class="google-map-api">
            <div id="map-canvas" class="gmap"></div>
        </div>
    </section>
    <div class="thumb-box6">
        <div class="container">
            <h2>Our Addresses</h2>
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4 center">
                    <h3>8901 Marmora Road,<br>Cebu City Philippines, D04 89GR.</h3>
                    <div class="info">
                        <p><span>Freephone:</span>+1 800 559 6580</p>
                        <p><span>Telephone:</span>+1 800 603 6035</p>
                        <p><span>FAX:</span>+1 800 889 9898</p>
                    </div>
                    <p>E-mail: <a href="#" class="mail1">mail@demolink.org</a></p>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 center">
                    <h3>9863 - 9867 Mill Road,<br>Cebu City Philippines, MG09 99HT.</h3>
                    <div class="info">
                        <p><span>Freephone:</span>+1 800 559 6580</p>
                        <p><span>Telephone:</span>+1 800 603 6035</p>
                        <p><span>FAX:</span>+1 800 889 9898</p>
                    </div>
                    <p>E-mail: <a href="#" class="mail1">mail@demolink.org</a></p>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 center">
                    <h3>9870 St Vincent Place,<br>Cebu City Philippines, DC 45 Fr 45.</h3>
                    <div class="info">
                        <p><span>Freephone:</span>+1 800 559 6580</p>
                        <p><span>Telephone:</span>+1 800 603 6035</p>
                        <p><span>FAX:</span>+1 800 889 9898</p>
                    </div>
                    <p>E-mail: <a href="#" class="mail1">mail@demolink.org</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
<link href="https://fonts.googleapis.com/css?family=Droid+Serif" rel="stylesheet">
<link rel="stylesheet" href="main_page/css/bootstrap.css" >
<link rel="stylesheet" href="main_page/css/style.css">
<link rel="stylesheet" href="main_page/css/animate.css">
<link rel="stylesheet" href="main_page/css/contact-form.css">
<link rel="stylesheet" href="fonts/font-awesome.css">
<script src='http://maps.googleapis.com/maps/api/js?key=AIzaSyB66kIZHfGP0FCDrEd3rIXegdiS0DXOO2w&v=3.exp&amp;sensor=false'></script>
<script type="text/javascript">
    google_api_map_init();
    function google_api_map_init(){
        var map;
        var coordData = new google.maps.LatLng(parseFloat(10.3317482), parseFloat(123.9326772));
        var markCoord1 = new google.maps.LatLng(parseFloat(10.3317482), parseFloat(123.9326772));
        var markCoord2 = new google.maps.LatLng(parseFloat(40.6422180), parseFloat(-73.9784068,14));
        var markCoord3 = new google.maps.LatLng(parseFloat(40.6482180), parseFloat(-73.9724068,14));
        var markCoord4 = new google.maps.LatLng(parseFloat(40.6442180), parseFloat(-73.9664068,14));
        var markCoord5 = new google.maps.LatLng(parseFloat(40.6379180), parseFloat(-73.9552068,14));
        var marker;

        var styleArray = []

        var markerIcon = {
            url: "main_page/img/gmap_marker.png",
            size: new google.maps.Size(42, 64),
            origin: new google.maps.Point(0,0),
            anchor: new google.maps.Point(21, 70)
        };
        function initialize() {
            var mapOptions = {
                zoom: 14,
                center: coordData,
                scrollwheel: false,
                styles: styleArray
            }

            var contentString = "<div></div>";
            var infowindow = new google.maps.InfoWindow({
                content: contentString,
                maxWidth: 200
            });

            var map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
            marker = new google.maps.Marker({
                map:map,
                position: markCoord1,
                icon: markerIcon,
            });

            /*marker1 = new google.maps.Marker({
             map:map,
             position: markCoord2,
             icon: markerIcon
             });

             marker2 = new google.maps.Marker({
             map:map,
             position: markCoord3,
             icon: markerIcon
             });

             marker3 = new google.maps.Marker({
             map:map,
             position: markCoord4,
             icon: markerIcon
             });

             marker4 = new google.maps.Marker({
             map:map,
             position: markCoord5,
             icon: markerIcon
             }); */

            var contentString = '<div id="content" style="float: left !important;">'+
                    '<div id="siteNotice">'+
                    '</div>'+
                    '<div id="bodyContent">'+
                    '<p>Windows of Heaven Hypermart</p>'+
                    '<p>Mac Briones St. Mandaue City Cebu <span>(032) 235-6789</span></p>'+
                    '</div>'+
                    '</div>';

            /*var contentString1 = '<div id="content">'+
             '<div id="siteNotice">'+
             '</div>'+
             '<div id="bodyContent">'+
             '<p>4578 Marmora Road, Glasgow D04 89GR <span>800 2345-6789</span></p>'+
             '</div>'+
             '</div>';

             var contentString2 = '<div id="content">'+
             '<div id="siteNotice">'+
             '</div>'+
             '<div id="bodyContent">'+
             '<p>4578 Marmora Road, Glasgow D04 89GR <span>800 2345-6789</span></p>'+
             '</div>'+
             '</div>';

             var contentString3 = '<div id="content">'+
             '<div id="siteNotice">'+
             '</div>'+
             '<div id="bodyContent">'+
             '<p>4578 Marmora Road, Glasgow D04 89GR <span>800 2345-6789</span></p>'+
             '</div>'+
             '</div>';

             var contentString4 = '<div id="content">'+
             '<div id="siteNotice">'+
             '</div>'+
             '<div id="bodyContent">'+
             '<p>4578 Marmora Road, Glasgow D04 89GR <span>800 2345-6789</span></p>'+
             '</div>'+
             '</div>';*/

            var infowindow = new google.maps.InfoWindow({
                content: contentString
            });

            /*var infowindow1 = new google.maps.InfoWindow({
             content: contentString1
             });

             var infowindow2 = new google.maps.InfoWindow({
             content: contentString2
             });

             var infowindow2 = new google.maps.InfoWindow({
             content: contentString3
             });

             var infowindow2 = new google.maps.InfoWindow({
             content: contentString4
             });*/



            google.maps.event.addListener(marker, 'click', function() {
             infowindow.open(map,marker);
             $('.gm-style-iw').parent().parent().addClass("gm-wrapper");
             });
            /*
             google.maps.event.addListener(marker1, 'click', function() {
             infowindow.open(map,marker1);
             $('.gm-style-iw').parent().parent().addClass("gm-wrapper");
             });

             google.maps.event.addListener(marker2, 'click', function() {
             infowindow.open(map,marker2);
             $('.gm-style-iw').parent().parent().addClass("gm-wrapper");
             });

             google.maps.event.addListener(marker3, 'click', function() {
             infowindow.open(map,marker3);
             $('.gm-style-iw').parent().parent().addClass("gm-wrapper");
             });

             google.maps.event.addListener(marker4, 'click', function() {
             infowindow.open(map,marker4);
             $('.gm-style-iw').parent().parent().addClass("gm-wrapper");
             });*/

            google.maps.event.addDomListener(window, 'resize', function() {

                map.setCenter(coordData);

                var center = map.getCenter();
            });
        }

        google.maps.event.addDomListener(window, "load", initialize);

    }
</script>
@endsection

