<!DOCTYPE html>

<html lang="en">

<head>

   <title> @if(Request::segment(1) == "") Home @else {!! ucfirst(Request::segment(1)) !!} @endif</title>

   <meta charset="utf-8">

   <meta name="viewport" content="width=device-width, initial-scale=1.0">

   <link rel="icon" href="main_page/img/favicon.ico" type="image/x-icon">

   <link rel="shortcut icon" href="main_page/img/favicon.ico" type="image/x-icon" />

   <meta name="description" content="Your description">

   <meta name="keywords" content="Your keywords">

   <meta name="author" content="Your name">

   <meta name = "format-detection" content = "telephone=no" />

   <!--CSS-->

   <link rel="stylesheet" href="main_page/css/bootstrap.css" >

   <link rel="stylesheet" href="main_page/css/style.css">

   <link rel="stylesheet" href="main_page/css/animate.css">

   <link rel="stylesheet" href="main_page/css/touchTouch.css">

   <link rel="stylesheet" href="fonts/font-awesome.css">

   <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
   <link href="https://fonts.googleapis.com/css?family=Droid+Serif" rel="stylesheet">

   <!--JS-->
   <script src="main_page/js/jquery.js"></script>
   <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
   <script src="main_page/js/jquery-migrate-1.2.1.min.js"></script>

   <script src="main_page/js/jquery.easing.1.3.js"></script>

   <script src="main_page/js/jquery.mobilemenu.js"></script>

   <script src="main_page/js/jquery.equalheights.js"></script>

   <script src="main_page/js/touchTouch.jquery.js"></script>



   <script>

      $(window).load(function() {

         // Initialize the gallery

         $('.thumb-box6 figure a').touchTouch();

      });

   </script>

   <!--[if (gt IE 9)|!(IE)]><!-->

   <script src="main_page/js/wow/wow.js"></script>

   <script src="main_page/js/wow/device.min.js"></script>

   <script>

      $(document).ready(function () {

         if ($('html').hasClass('desktop')) {

            new WOW().init();

         }
      });

   </script>

   <!--<![endif]-->

</head>

<body>
<!--header-->

<div class="main">

   <header id="stuck_container">
      <div class="container">
         <h1 class="navbar-brand navbar-brand_">
            <a href="index.html">WINDOWS OF HEAVEN HYPERMART</a>
         </h1>

         <nav class="navbar navbar-default navbar-static-top tm_navbar clearfix" role="navigation">

            <ul class="nav sf-menu clearfix">

               <li @if(Request::segment(1) == "") class="active" @endif><a href="{{ action('HomepageController@index') }}">Home</a></li>

               <li class="@if(Request::segment(1) == "about") active @endif sub-menu"><a href="{{ action('HomepageController@about_page') }}">About</a><span class="fa fa-angle-down"></span>
                  <ul class="submenu">

                     <li><a href="#">Mission & Vision</a></li>
                     <li><a href="#">Product & Services</a><span class="fa fa-angle-right"></span>
                        <ul class="submenu">
                           <li><a href="#">Products</a></li>
                           <li><a href="#">Networking</a></li>
                           <li><a href="#">Gift Certificates</a></li>
                        </ul>
                     </li>
                     <li><a href="#">FAQ</a></li>

                  </ul>

               </li>

               <li @if(Request::segment(1) == "gallery") class="active" @endif><a href="{{ action('HomepageController@gallery_page') }}">Gallery</a></li>

               <li @if(Request::segment(1) == "contact") class="active" @endif><a href="{{ action('HomepageController@contact_page') }}">Contacts</a></li>
               @if(\Session::get('member'))
                  <li><a href="{{action('MemberController@member_profile')}}">My Profile</a></li>
               @else
                  <li><a href="" id="register">Login</a></li>
               @endif

            </ul>

         </nav>

      </div>

   </header>

</div>
@if(Request::segment(1) == "")
<div class="thumb-box1">

   <div class="container">

      <div class="row">

         <div class="col-lg-4 wow fadeInLeft" data-wow-delay="0.2s">

            <div class="box">

               <figure><img src="main_page/img/page1_icon1.png" alt=""></figure>

               <div class="extra-wrap">

                  <a href="http://shopping.woh-hypermart.com/">Shopping</a>

                  <p>Buy products online</p>

               </div>

            </div>

         </div>

         <div class="col-lg-4 wow fadeInLeft" data-wow-delay="0.1s">

            <div class="box">

               <figure><img src="main_page/img/page1_icon2.png" alt=""></figure>

               <div class="extra-wrap">

                  <a href="#">Marketing</a>

                  <p>Invite and earn</p>

               </div>

            </div>

         </div>

         <div class="col-lg-4 wow fadeInLeft">

            <div class="box">

               <figure><img src="main_page/img/page1_icon3.png" alt=""></figure>

               <div class="extra-wrap indent">

                  <a href="#">Gift Certificate</a>

                  <p>Get and purchase</p>

               </div>

            </div>

         </div>

      </div>

   </div>

</div>
@endif
@yield('content')
        <!--footer-->

<footer>

   <div class="container">

      <p>Windows of Heaven Hypermart &copy; <em id="copyright-year"></em> All Rights Reserved <br><span>|</span> <a href="index-5.html">Privacy Policy</a></p>

   </div>

   <!-- {%FOOTER_LINK} -->

</footer>

<script src="main_page/js/bootstrap.min.js"></script>
<script src="main_page/js/tm-scripts.js"></script>
<script src="main_page/js/jquery.cookie.js"></script>
<script src="main_page/js/device.min.js"></script>
<script src="main_page/js/tmstickup.js"></script>
<script src="main_page/js/superfish.js"></script>
<script src="main_page/js/jquery.mousewheel.min.js"></script>
<script src="main_page/js/jquery.simplr.smoothscroll.min.js"></script>
<script src="main_page/js/stellar/jquery.stellar.js"></script>
<script src="main_page/js/jquery.ui.totop.js"></script>
<meta content="width=device-width,initial-scale=1.0,user-scalable=0" name="viewport">

<script type="text/javascript">

   var _gaq = _gaq || [];

   _gaq.push(['_setAccount', 'UA-7078796-5']);

   _gaq.push(['_trackPageview']);

   (function() {

      var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;

      ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';

      var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);

   })();</script>
</body>
</html>
<div id="dialog-form" title="Member Login">
   {!! Form::open(['data-remote','url' => action('MemberController@post_member_login'), 'id' => 'login_form']) !!}
   <fieldset>
      <label for="username">Username</label>
      <input type="username" name="username" id="username" placeholder="username123" class="text ui-widget-content ui-corner-all">
      <label for="password">Password</label>
      <input type="password" name="password" id="password" placeholder="xxxxxxx" class="text ui-widget-content ui-corner-all">
      <!-- Allow form submission with keyboard without duplicating the dialog button -->
      <input type="submit" id="login-click" tabindex="-1" style="position:absolute; top:-1000px">
   </fieldset>
   {!! Form::close() !!}
   <button id="create-user" style="display: none">Login</button>
   <div id="ajax-loader" style="display:none;position: absolute;top:25px;left:150px"><img src="member_page/images/ajax-loader.gif"> </div>
</div>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<style>
   label, input { display:block; text-align: left}
   input.text { margin-bottom:12px; width:95%; padding: .4em; }
   fieldset { padding:0; border:0; margin-top:25px; }
   h1 { font-size: 1.2em; margin: .6em 0; }
   div#users-contain { width: 350px; margin: 20px 0; }
   div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
   div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
   .ui-dialog .ui-state-error { padding: .3em; }
   .validateTips { border: 1px solid transparent; padding: 0.3em; }
   .ui-widget-overlay
   {
      opacity: .70 !important; /* Make sure to change both of these, as IE only sees the second one */
      filter: Alpha(Opacity=70) !important;

      background-color: rgb(70, 70, 70) !important; /* This will make it darker */
   }
</style>

<script>
   $.fn.bootstrapBtn = $.fn.button.noConflict();
   $( function() {
      $('#register').click(function(e){
         e.preventDefault();
         $('#create-user').click();
      });

      $('#password').keypress(function (e) {
         if (e.which == 13) {
            $('.login').click();
            return false;
         }
      });


      var dialog, form;

      dialog = $( "#dialog-form" ).dialog({
         autoOpen: false,
         height: 300,
         width: 450,
         modal: true,
         buttons: {
            "Login":{
               click : function() {
                  $('#ajax-loader').fadeIn();
                  $.ajax({

                     type: "POST",

                     url: form.prop('action'),

                     data: form.serialize(),

                     success: function(data, NULL, jqXHR) {
                        $('#ajax-loader').fadeOut();
                        if(jqXHR.status === 200 ) {//redirect if  authenticated user.
                           $( location ).prop( 'pathname', '/member_profile');
                        }
                     },
                     error: function(data) {
                        $('#ajax-loader').fadeOut();
                        if( data.status === 401 ) {//redirect if not authenticated user
                           alert("Member not found!");
                           $('#username').val('');
                           $('#password').val('');
                           $('#username').focus();
                        }
                        if( data.status === 422 ) {
                           //process validation errors here.
                           alert("Username and password are required.");
                           $('#username').val('');
                           $('#password').val('');
                           $('#username').focus();
                        }
                     }
                  });
               },
               'class' :'ui-button ui-corner-all ui-widget login',
               text : "Login"
            },
            Cancel: {
               click : function() {
                  dialog.dialog( "close" );
               },
               'class' :'ui-button ui-corner-all ui-widget',
               text : "Cancel"
            }
         },
         close: function() {
            form[ 0 ].reset();
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

      form = dialog.find( "form" ).on( "submit", function( event ) {
         event.preventDefault();
      });

      $( "#create-user" ).button().on( "click", function() {
         dialog.dialog( "open" );
      });
   });
</script>


