<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <title>Member Page</title>
    <link rel="stylesheet" href="member_page/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="member_page/css/jquery.jOrgChart.css"/>
    <link rel="stylesheet" href="member_page/css/custom.css"/>
    <link href="member_page/css/prettify.css" type="text/css" rel="stylesheet" />
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

        .span {
            background: #fff none repeat scroll 0 0;
            border: 2px solid #000;
            border-radius: 5px;
            display: inline-block;
            padding: 3px 10px;
        }

        .container{
            margin-left: 30px!important;
            width: 100%;
        }

        .alert-danger {
            background-color: #f2dede;
            border-color: #ebccd1;
            color: #a94442;
        }
        .alert {
            border: 1px solid transparent;
            border-radius: 4px;
            margin-bottom: 20px;
            padding: 15px;
        }

    </style>

    <script type="text/javascript" src="member_page/prettify.js"></script>

    <!-- jQuery includes -->
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script src="member_page/jquery.jOrgChart.js"></script>

    <script>
        jQuery(document).ready(function() {
            $("#org").jOrgChart({
                chartElement : '#chart',
                dragAndDrop  : true
            });
        });

        $(window).load(function () {
            $('.topbar-inner').css('width',$('#tree').outerWidth());
            $('.nav_chart').css('text-align','left');
        });

        $( function() {
            $('#register').click(function(e){
                e.preventDefault();
                //$('#create-user').click();
                dialog.dialog( "open" );
            });

            var dialog, dialog2, form, form2 ,choose_dialog, dialog4, form4;

            dialog = $( "#dialog-form" ).dialog({
                autoOpen: false,
                height: 520,
                width: 800,
                modal: true,
                buttons: {
                    "Submit": function(){
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
                                    var res = JSON.parse(data.responseText);
                                    $('#first_name').focus();
                                    $('#form1_error').html(res.msg);
                                    $('#form1_error').fadeIn();
                                }
                                if( data.status === 422 ) {
                                    //process validation errors here.
                                    var err_msg = '';
                                    var res = JSON.parse(data.responseText);
                                    for (var key in res) {
                                        if (res.hasOwnProperty(key)) {
                                            var obj = res[key];
                                            for (var prop in obj) {
                                                if (obj.hasOwnProperty(prop)) {
                                                    err_msg += '<p>'+obj[prop] + "</p>";
                                                }
                                            }
                                        }
                                    }
                                    $('#first_name').focus();
                                    $('#form1_error').html(err_msg);
                                    $('#form1_error').fadeIn();
                                }
                            }
                        });
                    },
                    Cancel: function() {
                        dialog.dialog( "close" );
                    }
                },
                close: function() {
                    $('#form1_error').html('');
                    $('#form1_error').fadeOut();
                    form[ 0 ].reset();
                    choose_dialog.dialog('close');
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

            /*$( "#create-user" ).button().on( "click", function(e) {
                e.preventDefault();
                dialog.dialog( "open" );
            });*/

            form = dialog.find( "form" ).on( "submit", function( event ) {
                event.preventDefault();
            });

            //---------- form2 ------------------------------------------//
            $('.empty_node').mouseover(function(e){
                $(this).css('cursor','pointer');
            });

            $('.empty_node').click(function(e){
                e.preventDefault();
                if($(this).data('woh_member')) {
                    $('#upline_selected').val($(this).data('woh_member'));
                    $('#tree_position').val($(this).data('position'));
                    $('#level').val($(this).data('cur_level'));
                    $('#downline_of option').remove();
                    $('#downline_of')
                            .append($("<option></option>")
                                    .attr("value",$(this).data('woh_member'))
                                    .text($(this).data('username')));
                }
                else
                    $('#upline_selected').val({!! $member[0]->woh_member !!});
                dialog.dialog( "close" );
                $('#register').hide();
                choose_dialog.dialog( "open" );
            });

            $('#add-account').click(function(e){
                e.preventDefault();
                $('#first_name').val('{!! $member[0]->first_name !!}');
                $('#last_name').val('{!! $member[0]->last_name !!}');
                $('#middle_name').val('{!! $member[0]->middle_name !!}');
                $('#address').val('{!! $member[0]->address !!}');
                $('#gender').val('{!! $member[0]->gender !!}');
                $('#bday').val('{!! $member[0]->bday !!}');
                $('#username').val('{!! $member[0]->username !!}{!! ($member_heads+1) !!}');
                $('#password').val('{!! $member[0]->password !!}');
                $('#re-password').val('{!! $member[0]->password !!}');
                $('#first_name').hide();
                $('#last_name').hide();
                $('#middle_name').hide();
                $('#address').hide();
                $('#gender').hide();
                $('#bday').hide();
                $('#username').hide();
                $('#password').hide();
                $('#re-password').hide();
                $('#lfname').hide();
                $('#llname').hide();
                $('#lmname').hide();
                $('#laddress').hide();
                $('#lgender').hide();
                $('#lbday').hide();
                $('#lusername').hide();
                $('#lpassword').hide();
                $('#lre-password').hide();
                dialog.dialog( "open" );
            });

            $('#new-account').click(function(e){
                e.preventDefault();
                dialog.dialog( "open" );
            });

            choose_dialog = $( "#choose_dialog" ).dialog({
                autoOpen: false,
                height: 100,
                width: 100,
                show: {
                    effect: "blind",
                    duration: 1000
                },
                hide: {
                    effect: "explode",
                    duration: 1000
                },
                close: function() {
                    form[ 0 ].reset();
                    location.reload();
                },
            });

            $( "#my-account" ).on( "click", function(e) {
                e.preventDefault();
                dialog2.dialog( "open" );
            });

            dialog2 = $( "#dialog-form2" ).dialog({
                autoOpen: false,
                height: 508,
                width: 800,
                modal: true,
                buttons: {
                    "Update": function(){
                        $('#ajax-loader').fadeIn();
                        $.ajax({

                            type: "POST",

                            url: form2.prop('action'),

                            data: form2.serialize(),

                            success: function(data, NULL, jqXHR) {
                                $('#ajax-loader').fadeOut();
                                if(jqXHR.status === 200 ) {//redirect if  authenticated user.
                                    $( location ).prop( 'pathname', '/member_profile');
                                }
                            },
                            error: function(data) {
                                $('#ajax-loader').fadeOut();
                                if( data.status === 422 ) {
                                    //process validation errors here.
                                    var err_msg = '';
                                    var res = JSON.parse(data.responseText);
                                    for (var key in res) {
                                        if (res.hasOwnProperty(key)) {
                                            var obj = res[key];
                                            for (var prop in obj) {
                                                if (obj.hasOwnProperty(prop)) {
                                                    err_msg += '<p>'+obj[prop] + "</p>";
                                                }
                                            }
                                        }
                                    }
                                    $('#first_name').focus();
                                    $('#form1_error').html(err_msg);
                                    $('#form1_error').fadeIn();
                                }
                            }
                        });
                    },
                    Cancel: function() {
                        dialog2.dialog( "close" );
                    }
                },
                close: function() {
                    $('#form1_error').html('');
                    $('#form1_error').fadeOut();
                    form2[ 0 ].reset();
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

            form2 = dialog2.find( "form" ).on( "submit", function( event ) {
                event.preventDefault();
            });

            dialog4 = $( "#dialog-form4" ).dialog({
                autoOpen: false,
                height: 250,
                width: 800,
                modal: true,
                buttons: {
                    "Submit": function(){

                        if(!$('#product_code').val())
                        {
                            alert('Please enter product code.');
                            $('#product_code').focus();
                            return false;
                        }

                        $('#ajax-loader').fadeIn();
                        $.ajax({

                            type: "POST",

                            url: form4.prop('action'),

                            data: form4.serialize(),

                            success: function(data, NULL, jqXHR) {
                                $('#ajax-loader').fadeOut();
                                if(jqXHR.status === 200 ) {//redirect if  authenticated user.
                                    alert("Product code successfully save.");
                                    dialog4.dialog( "close" );
                                    location.href="{{ action('MemberController@member_profile') }}";
                                }
                            },
                            error: function(data) {
                                $('#ajax-loader').fadeOut();
                                if( data.status === 401 ) {//redirect if not authenticated user
                                    alert("Product code not found!");
                                }
                                if( data.status === 422 ) {
                                    //process validation errors here.
                                    var err_msg = '';
                                    var res = JSON.parse(data.responseText);
                                    for (var key in res) {
                                        if (res.hasOwnProperty(key)) {
                                            var obj = res[key];
                                            for (var prop in obj) {
                                                if (obj.hasOwnProperty(prop)) {
                                                    err_msg += '<p>'+obj[prop] + "</p>";
                                                }
                                            }
                                        }
                                    }
                                    $('#first_name').focus();
                                    $('#form1_error').html(err_msg);
                                    $('#form1_error').fadeIn();
                                }
                            }
                        });
                    },
                    Cancel: function() {
                        dialog4.dialog( "close" );
                    }
                },
                close: function() {
                    $('#form1_error').html('');
                    $('#form1_error').fadeOut();
                    form4[ 0 ].reset();
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

            form4 = dialog4.find( "form" ).on( "submit", function( event ) {
                event.preventDefault();
            });

            $( "#unilevel-link" ).on( "click", function(e) {
                e.preventDefault();
                @if(!empty($unilevel_period))
                    dialog4.dialog("open");
                @else
                    alert('You have already maintained your account.');
                @endif
            });

        });
    </script>
</head>

<body onload="prettyPrint();">
<div class="topbar">
    <div class="topbar-inner">
        <div class="container">
            <a class="brand" href="{{action('MemberController@member_profile')}}">WOH - Geneology</a>
            <ul class="nav_chart">
                <li><a href="{{action('HomepageController@index')}}">Home</a></li>
                <li><a href="" id="my-account">My Account</a></li>
                <li><a href="{{action('MemberController@member_withdrawals')}}">Withdrawals</a></li>
                <li>
                    <a href="{{action('MemberController@member_transactions')}}">Transactions & Ernings</a>
                </li>
                <li><a href="" id="unilevel-link">Unilevel Commision</a></li>
                <li><a href="" id="register">Add Downline</a></li>
                <li><a href="{{action('MemberController@member_logout')}}" onclick="return confirm('Are you sure you want to logout?')">Logout</a></li>
            </ul>
            <div class="pull-left">
                <div class="alert-message info" id="show-list">Show underlying list.</div>
                <pre class="prettyprint lang-html" id="list-html" style="display:none"></pre>
            </div>
        </div>
    </div>
</div>

<!-- Chart starts here -->
<ul id="org" style="display:none;">
    <li>
        <a class='with_node'><span class="span">{!! $member[0]->woh_member !!}-{!! $member[0]->username !!}</span><img src="member_page/images/{!! $member[0]->picture !!}" alt="Raspberry"/></a>
        <ul>
            @if($downlines)
                {!! $downlines !!}
            @else
                <li><a class='empty_node' data-cur_level = '1'  data-woh_member='{!! $member[0]->woh_member !!}' data-position="left" data-level="0-l" data-username='{!! $member[0]->username !!}'><img src="member_page/images/offline.png" alt="Raspberry"/></a></li>
                <li><a class='empty_node' data-cur_level = '1'  data-woh_member='{!! $member[0]->woh_member !!}' data-position="right" data-level="0-r" data-username='{!! $member[0]->username !!}'><img src="member_page/images/offline.png" alt="Raspberry"/></a></li>
            @endif
        </ul>
    </li>
</ul>
<!-- Chart ends here -->

<div id="chart" class="orgChart" style="text-align: center;"></div>

<script>
    jQuery(document).ready(function() {
        $('.entry').hide();
        $('.cd').hide();

        /* Custom jQuery for the example */
        $("#show-list").click(function(e){
            e.preventDefault();

            $('#list-html').toggle('fast', function(){
                if($(this).is(':visible')){
                    $('#show-list').text('Hide underlying list.');
                    $(".topbar").fadeTo('fast',0.9);
                }else{
                    $('#show-list').text('Show underlying list.');
                    $(".topbar").fadeTo('fast',1);
                }
            });
        });

        $('#list-html').text($('#org').html());

        $("#org").bind("DOMSubtreeModified", function() {
            $('#list-html').text('');

            $('#list-html').text($('#org').html());

            prettyPrint();
        });

        $('#tree_position').change(function(e){
            $('#downline_of option').remove();
            $this = $(this);
            var options = [];
            $('.empty_node').each(function(){
                if($(this).data('position') === $this.val() && (options.indexOf($(this).data('woh_member')+'-'+$(this).data('username')) == -1))
                    options.push($(this).data('woh_member')+'-'+$(this).data('username'));
            });
            options.forEach(function(entry) {
                data = entry.split('-');
                $('#downline_of')
                        .append($("<option></option>")
                                .attr("value",data[0])
                                .text(data[1]));
            });
        });

        $('#re-password').blur(function()
        {
            if($(this).val() != $('#password').val())
            {
                $('#form1_error').html('<p>Password does not match.</p>');
                $('#form1_error').fadeIn();
            }
        });

        $('#password').blur(function(){
            if($(this).val() && $(this).val().length < 6)
            {
                $('#form1_error').html('<p>Password must be atleast 6 characters.</p>');
                $('#form1_error').fadeIn();
            }
            else if($('#re-password').val() && $(this).val() != $('#re-password').val())
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

        $('#username').blur(function(){
            if($(this).val() && $(this).val().length < 6)
            {
                $('#form1_error').html('<p>Username must be atleast 6 characters.</p>');
                $('#form1_error').fadeIn();
            }
            else
            {
                $('#form1_error').html('');
                $('#form1_error').fadeOut();
            }
        });

        $('#upassword').blur(function(){
            if($('#ure-password').val() && $(this).val() != $('#ure-password').val())
            {
                $('#form2_error').html('<p>Password does not match.</p>');
                $('#form2_error').fadeIn();
            }
            else
            {
                $('#form2_error').html('');
                $('#form2_error').fadeOut();
            }
        });

        $('#ure-password').blur(function()
        {
            if($(this).val() != $('#upassword').val())
            {
                $('#form2_error').html('<p>Password does not match.</p>');
                $('#form2_error').fadeIn();
            }
            else {
                $('#form2_error').html('');
                $('#form2_error').fadeOut();
            }
        });

        $('#sponsor').blur(function(){
            $.ajax({
                type: "POST",
                url: '{{ action('MemberController@post_member_search') }}',
                data: {'woh_member':$(this).val()},
                success: function(data, NULL, jqXHR) {
                    if(typeof data.woh_member[0] !== 'undefined' && data.woh_member[0] !== null)
                        $('#sponsor-display').text(data.woh_member[0].woh_member + ' - ' + data.woh_member[0].username)
                    else
                    {
                        alert("Sponsor not found!");
                        $('#sponsor-display').text('---');
                    }
                },
                error: function(data) {
                    alert("Sponsor not found!");
                    $('#sponsor-display').text('');
                }
            });
        });

        $('#re-password').focus(function(){
            $('#form1_error').html('');
            $('#form1_error').fadeOut();
        });

        $('#account_type').change(function(){
           if($(this).val() == 'entry_code')
           {
               $('.entry').fadeIn();
               $('.cd').fadeOut();
               $('#status').val(1);
               $('#picture').val('online.png');
           }
           else
           {
               $('.entry').fadeOut();
               $('.cd').fadeIn();
               $('#status').val(0);
               $('#picture').val('floating.png');
           }
        });
    });
</script>
<div id="dialog-form" title="Member Registration">
    {!! Form::open(['data-remote','url' => action('MemberController@post_member_add'), 'id' => 'login_form']) !!}
        <fieldset>
            <div id="form1_error" class="alert alert-danger" role="alert" style="display: none"></div>
            <label for="first_name" id="lfname">First Name</label>
            <input type="text" name="first_name" id="first_name" placeholder="Jane" class="text ui-widget-content ui-corner-all">
            <label for="middle_name" id="lmname">Middle Name</label>
            <input type="text" name="middle_name" id="middle_name" placeholder="Suarez" class="text ui-widget-content ui-corner-all">
            <label for="last_name" id="llname">Last Name</label>
            <input type="text" name="last_name" id="last_name" placeholder="Cruz" class="text ui-widget-content ui-corner-all">
            <label for="address" id="laddress">Address</label>
            <input type="text" name="address" id="address" placeholder="Mc Briones St. Tipolo, Mandaue City Cebu" class="text ui-widget-content ui-corner-all">
            <label for="gender" id="lgender">Gender</label>
            <select name="gender" id="gender" class="text ui-widget-content ui-corner-all"  style="width: 97%">
                <option value="male">Male</option>
                <option value="female">Female</option>
            </select>
            <label for="bday" id="lbday">Birthday</label>
            <input type="text" name="bday" id="bday" placeholder="1990-01-25" class="text ui-widget-content ui-corner-all">
            <label for="position">Position</label>
            <select name="tree_position" id="tree_position" class="text ui-widget-content ui-corner-all" style="width: 97%">
                <option value="">Select</option>
                <option value="left">Left</option>
                <option value="right">Right</option>
            </select>
            <label for="downline_of">Downline</label>
            <select name="downline_of" id="downline_of" class="text ui-widget-content ui-corner-all" style="width: 97%">
            </select>
            <label for="sponsor" style="width: 50px; margin-top: 10px">Sponsor</label>
            <table cellpadding="10" cellspacing="10">
                <tr>
                    <td><input type="text" id="sponsor" value="{!! $member[0]->woh_member !!}" name="sponsor" class="text ui-widget-content ui-corner-all"></td>
                    <td valign="center" style="color:green; padding-bottom: 12px;">
                        <img src="member_page/images/check.png" height="20px"> <i id="sponsor-display">{!! $member[0]->woh_member !!} - {!! $member[0]->username !!}</i>
                    </td>
                </tr>
            </table>
            <input type="hidden" name="picture" id="picture" placeholder="picture" value="online.png" class="text ui-widget-content ui-corner-all">
            <!--<label for="email">Email</label>
                <input type="text" name="email" id="email" value="jane@smith.com" class="text ui-widget-content ui-corner-all">-->
            <label for="username" id="lusername">Username</label>
            <input type="username" name="username" id="username" placeholder="username123" class="text ui-widget-content ui-corner-all">
            <label for="password" id="lpassword">Password</label>
            <input type="password" name="password" id="password" placeholder="xxxxxxx" class="text ui-widget-content ui-corner-all">
            <label for="re-password" id="lre-password">Re-type Password</label>
            <input type="password" name="re-password" id="re-password" placeholder="xxxxxxx" class="text ui-widget-content ui-corner-all">
            <hr>
            <label for="entry_code" class="entry">Choose Account</label>
            <select name="account_type" id="account_type" class="text ui-widget-content ui-corner-all" style="width: 97%">
                <option value="">Choose Account</option>
                <option value="entry_code">Pay-in Account</option>
                <option value="cd_code">CD Account</option>
            </select>
            <label for="pin_code">Pin Code</label>
            <input type="text" name="pin_code" id="pin_code" placeholder="xxxxxxxxxxxxxx" class="text ui-widget-content ui-corner-all">
            <label for="entry_code" class="entry">Entry Code</label>
            <input type="text" name="entry_code" id="entry_code" placeholder="xxxxxxxxxxxxxx" class="text ui-widget-content ui-corner-all entry">
            <label for="cd_code" class="cd">CD Code</label>
            <input type="text" name="cd_code" id="cd_code" placeholder="xxxxxxxxxxxxxx" class="text ui-widget-content ui-corner-all cd">
            <input type="hidden" id="upline_selected" value="{!! $member[0]->woh_member !!}">
            <input type="hidden" id="level" name="level">
            <input type="hidden" id="status" value="1">
            <!-- Allow form submission with keyboard without duplicating the dialog button -->
            <input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
        </fieldset>
    {!! Form::close() !!}
    <button id="create-user" style="display: none">Create new user</button>
</div>
<div id="dialog-form2" title="Account Details">
    {!! Form::open(['data-remote','url' => action('MemberController@post_member_update'), 'id' => 'login_form']) !!}
    <fieldset>
        <div id="form2_error" class="alert alert-danger" role="alert" style="display: none"></div>
        <label for="first_name" id="lfname">First Name</label>
        <input type="text" name="first_name" readonly id="first_name" placeholder="Jane" class="text ui-widget-content ui-corner-all" value="{!! $member[0]->first_name !!}">
        <label for="middle_name" id="lmname">Middle Name</label>
        <input type="text" name="middle_name" readonly id="middle_name" placeholder="Suarez" class="text ui-widget-content ui-corner-all" value="{!! $member[0]->middle_name !!}">
        <label for="last_name" id="llname">Last Name</label>
        <input type="text" name="last_name" readonly id="last_name" placeholder="Cruz" class="text ui-widget-content ui-corner-all" value="{!! $member[0]->last_name !!}">
        <label for="address" id="laddress">Address</label>
        <input type="text" name="address" id="address" placeholder="Mc Briones St. Tipolo, Mandaue City Cebu" class="text ui-widget-content ui-corner-all" value="{!! $member[0]->address !!}">
        <label for="gender" id="lgender">Gender</label>
        <select name="gender" id="gender" class="text ui-widget-content ui-corner-all"  style="width: 97%">
            <option value="male" <?= $member[0]->gender == "male" ? "selected" : "" ?>>Male</option>
            <option value="female" <?= $member[0]->gender == "female" ? "selected" : "" ?>>Female</option>
        </select>
        <label for="bday" id="lbday">Birthday</label>
        <input type="text" name="bday" id="bday" placeholder="1990-01-25" class="text ui-widget-content ui-corner-all" value="{!! $member[0]->bday !!}">
        <label for="password" id="password" style="width: 100%">Current Password: <b>{!! $member[0]->password!!}</b></label>
        <label for="password" id="password"  style="width: 100%">New Password (<b  style="color: red;">Change your password? Just type your new password below.</b>)</label>
        <input type="password" name="password" id="upassword" placeholder="xxxxxxx" class="text ui-widget-content ui-corner-all">
        <label for="re-password" id="re-password"  style="width: 100%">Re-type Password (<b style="color: red;">Change your password? Just re-type your new password below.</b>)</label>
        <input type="password" name="re-password" id="ure-password" placeholder="xxxxxxx" class="text ui-widget-content ui-corner-all">
        <input type="hidden" name="woh_member" value="{!! $member[0]->woh_member !!}">
    </fieldset>
    {!! Form::close() !!}
    <button id="create-user" style="display: none">Create new user</button>
</div>
<div id="choose_dialog" title="Select Downline" style="text-align: center">
    @if($member_heads < 7 && ($member[0]->first_name != 'Windows' && $member[0]->middle_name != 'Of' && $member[0]->last_name != 'Heaven'))
    <a id="add-account"><img src="member_page/images/plus.png" width="50"></a>
    @elseif($member_heads < 15 && ($member[0]->first_name == 'Windows' && $member[0]->middle_name == 'Of' && $member[0]->last_name == 'Heaven'))
        <a id="add-account"><img src="member_page/images/plus.png" width="50"></a>
    @endif
    <a id="new-account"><img src="member_page/images/downline.png" width="50"></a>
</div>
<div id="dialog-form4" title="Unilevel Commision">
    {!! Form::open(['data-remote','url' => action('MemberController@post_member_unilevel'), 'id' => 'unilevel_form']) !!}
    <fieldset>
        <label for="amount" style="font-size:14px;">Period Cover</label>
        <select name="period_cover" class="select ui-widget-content ui-corner-all" style="width:95%">
            @if(!empty($unilevel_period))
                @foreach($unilevel_period as $up)
                    <option value="{!! $up['woh_member_unilevel_earning'] !!}">{!! \Carbon\Carbon::parse($up['period_cover_start'])->format('F d, Y') !!} - {!! \Carbon\Carbon::parse($up['period_cover_end'])->format('F d, Y') !!}</option>
                @endforeach
            @endif
        </select> <br>
        <label for="amount" style="font-size:14px;">Enter Product Code</label>
        <input type="text" name="product_code" id="product_code" placeholder="XXXXXXXXXX" class="text ui-widget-content ui-corner-all">
        <input type="hidden" name="woh_member" value="{!! $member[0]->woh_member !!}">
    </fieldset>
    {!! Form::close() !!}
    <button id="create-user" style="display: none">Create new user</button>
</div>
</body>
</html>