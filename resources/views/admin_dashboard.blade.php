<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Dashboard - WOH Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <link href="admin_page/css/bootstrap.min.css" rel="stylesheet">
    <link href="admin_page/css/bootstrap-responsive.min.css" rel="stylesheet">
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600"
          rel="stylesheet">
    <link href="admin_page/css/font-awesome.css" rel="stylesheet">
    <link href="admin_page/css/style.css" rel="stylesheet">
    <link href="admin_page/css/pages/dashboard.css" rel="stylesheet">
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>
<body>
<div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container"> <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"><span
                        class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span> </a><a class="brand" href="index.html">WOH Hypermart Admin</a>
            <div class="nav-collapse">
                <ul class="nav pull-right">
                    <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i
                                    class="icon-cog"></i> Account <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="javascript:;">Settings</a></li>
                            <li><a href="javascript:;">Help</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i
                                    class="icon-user"></i> {{ ucfirst(Session::get('woh_admin_user')[0]['user_type']) }}<b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="javascript:;">Profile</a></li>
                            <li><a href="{!! action('AdminController@change_password') !!}">Change Password</a></li>
                            <li><a href="{!! action('AdminController@admin_logout') !!}" onclick="return confirm('Are you sure you want to logout account?');">Logout</a></li>
                        </ul>
                    </li>
                </ul>
                <form class="navbar-search pull-right">
                    <input type="text" class="search-query" placeholder="Search">
                </form>
            </div>
            <!--/.nav-collapse -->
        </div>
        <!-- /container -->
    </div>
    <!-- /navbar-inner -->
</div>
<!-- /navbar -->
<div class="subnavbar">
    <div class="subnavbar-inner">
        <div class="container">
            <ul class="mainnav">
                <li @if(Request::segment(1) == "admin_profile") class="active" @endif><a href="{{action('AdminController@admin_profile')}}"><i class="icon-dashboard"></i><span>Dashboard</span> </a> </li>
                <li @if(Request::segment(1) == "admin_reports") class="active" @endif><a href="{{action('AdminController@admin_reports')}}"><i class="icon-list-alt"></i><span>Reports</span> </a> </li>
                <li @if(Request::segment(1) == "ssssss-hhhhh-wwwwww--dbxxxx_xxxxbackupxxxx---setttingssxxxxx") class="active" @endif><a href="{{action('AdminController@db_backup')}}"><i class="icon-user"></i><span>DB Back-up</span> </a> </li>
                <li @if(Request::segment(1) == "klp_members" || Request::segment(1) == "klp_members_account") class="active" @endif><a href="{{action('AdminController@klp_members')}}"><i class="icon-signal"></i><span>KLP Members</span> </a></li>
                <li @if(Request::segment(1) == "gift_certificates") class="active" @endif><a href="{{action('AdminController@gift_certificates')}}"><i class="icon-bar-chart"></i><span>GC's</span> </a> </li>
                <li @if(Request::segment(1) == "redeem_gc") class="active" @endif><a href="{{action('AdminController@redeem_gc')}}"><i class="icon-tag"></i><span>Redeem GC</span> </a></li>
                <li @if(Request::segment(1) == "short_codes") class="active" @endif><a href="{{action('AdminController@short_codes')}}"><i class="icon-code"></i><span>Shortcodes</span> </a> </li>
                <li @if(Request::segment(1) == "withdrawal_request") class="active" @endif><a href="{{action('AdminController@withdrawal_request')}}"> <i class="icon-long-arrow-down"></i><span>Withdrawal Request</span> <b class="caret"></b></a>
                <li @if(Request::segment(1) == "gc_claim_request") class="active" @endif><a href="{{action('AdminController@gc_claim_request')}}"> <i class="icon-long-arrow-down"></i><span>GC Claim Request</span> <b class="caret"></b></a>
                {{--    <ul class="dropdown-menu">
                        <li><a href="icons.html">Icons</a></li>
                        <li><a href="faq.html">FAQ</a></li>
                        <li><a href="pricing.html">Pricing Plans</a></li>
                        <li><a href="login.html">Login</a></li>
                        <li><a href="signup.html">Signup</a></li>
                        <li><a href="error.html">404</a></li>
                    </ul>--}}
                </li>
            </ul>
        </div>
        <!-- /container -->
    </div>
    <!-- /subnavbar-inner -->
</div>
<!-- /subnavbar -->
@yield('content')
<div class="footer clearfix">
    <div class="footer-inner">
        <div class="container">
            <div class="row">
                <div class="span12"> &copy; 2017 <a href="http://www.wohhypermart.com/">WOH Hypermart Administrator</a>. </div>
                <!-- /span12 -->
            </div>
            <!-- /row -->
        </div>
        <!-- /container -->
    </div>
    <!-- /footer-inner -->
</div>
<!-- /footer -->
<!-- Le javascript
================================================== -->
</body>
</html>
