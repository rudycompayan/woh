<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Member Page/Transactions</title>
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
        .table-grid{
            width:90%; background-color: #ffffff; color: #000000; text-align: left;
            border:1px solid #ffffff; margin-left: 3%; margin-top: 50px; font-size: 16px;
        }
        .rows{
            border-bottom:1px solid lightgray; padding: 7px 5px;
        }
        .th-rows{
            border-bottom:1px solid lightgray; padding: 7px 5px; font-size: 18px;
        }

        .container{
            margin-left: 30px!important;
        }
    </style>

    <script type="text/javascript" src="member_page/prettify.js"></script>

    <!-- jQuery includes -->
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="member_page/jquery.jOrgChart.js"></script>
    <script>
        jQuery(document).ready(function() {

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
                <li><a href="{{action('MemberController@member_profile')}}">My Account</a></li>
                <li><a href="{{action('MemberController@member_profile')}}">Withdraw</a></li>
                <li>
                    <a href="{{action('MemberController@member_transactions')}}">Transactions & Ernings</a>
                </li>
                <li><a href="">Unilevel Commision</a></li>
                <li><a href="{{action('MemberController@member_logout')}}">Logout</a></li>
            </ul>
        </div>
    </div>
</div>

<div class="container" style="text-align: center; width:100%;">
    <table class="table-grid" cellspacing="0">
        <thead>
        <tr>
            <th class='th-rows' style="width: 15%">Transaction #</th>
            <th class='th-rows' style="width: 15%">Transaction Type</th>
            <th class='th-rows' style="width: 15%">Level</th>
            <th class='th-rows' style="width: 15%">Transaction Date</th>
            <th class='th-rows' style="width: 15%">Status</th>
            <th class='th-rows' style="width: 15%" align="right">Amount (&#8369;)</th>
        </tr>
        </thead>
        <tbody style="overflow: auto">
        <tr>
            <td colspan="6" style="padding: 0px;">
                <div style="width: 100%; max-height: 500px; overflow: auto;">
                    <table class="table-grid" cellspacing="0" style="width: 100%; margin: 0px">
                        <tbody>
                        @if(isset($member_tran))
                            <?php
                            $earn = 0;
                            $withdrawals = 0;
                            krsort($member_tran);
                            ?>
                            @foreach($member_tran as $key => $mt)
                                <?php
                                if($mt['woh_transaction_type'] == 1)
                                    $withdrawals += $mt['tran_amount'];
                                else
                                    $earn += $mt['tran_amount'];
                                ?>
                                <tr style="background-color: @if($key%2==0) #efefef @else #ffffff @endif;">
                                    <td class='rows' style="width: 15%">
                                        @if($mt['woh_transaction_type'] == 1)
                                            10029{!! $mt['woh_member_transaction'] !!}
                                        @else
                                            ----------
                                        @endif
                                    </td>
                                    <td class='rows' style="width: 15%">{!! $mt['transaction_type'] !!}</td>
                                    <td class='rows' style="width: 15%">
                                        @if(isset($mt['level']))
                                            {!! $mt['level'] !!}
                                        @else
                                            ----------
                                        @endif
                                    </td>
                                    <td class='rows' style="width: 15%">{!! \Carbon\Carbon::parse($mt['transaction_date'])->format('m/d/Y H:i A') !!}</td>
                                    <td class='rows' style="width: 15%">
                                        @if($mt['status'] == 1)
                                            Complete
                                        @else
                                            Disapproved
                                        @endif
                                    </td>
                                    <td class='rows' style="width: 15%" align="right">&#8369; {!! number_format($mt['tran_amount'],2) !!}</td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </td>
        </tr>
        <tr style="color: #2b542c">
            <td class='rows' colspan="4"  style="color: #2b542c; border-top: 1px solid lightgray">Total Earned ==></td>
            <td class='rows'  style="color: #2b542c; border-top: 1px solid lightgray"></td>
            <td class='rows' align="right" style="color: #2b542c; border-top: 1px solid lightgray"><b>&#8369; {{ number_format($earn,2) }}</b></td>
        </tr>
        <tr style="color: #761c19">
            <td class='rows' colspan="4">Total Withdrawals ==></td>
            <td class='rows'></td>
            <td class='rows' align="right"><b>&#8369; {{ number_format($withdrawals,2) }}</b></td>
        </tr>
        <tr style="color: #2a6496">
            <td class='rows' colspan="4">Current Balance ==></td>
            <td class='rows'></td>
            <td class='rows' align="right"><b>&#8369; {{ number_format(($earn-$withdrawals),2) }}</b></td>
        </tr>
        </tbody>
    </table>
</div>

</body>
</html>