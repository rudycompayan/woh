<?php

namespace App\Http\Controllers;

use App\Models\DownlineLevel;
use App\Models\Member;
use App\Models\MemberTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class MemberController extends Controller
{
    public function member_profile(Request $request)
    {
        if(!$request->session()->get('member'))
        {
            $redirect = action('HomepageController@index');
            return redirect($redirect);
        }
        $member = new Member;
        $member = $member->where('woh_member',$request->session()->get('member'))->get();
        $downlines = $this->downline($member[0]->woh_member);
        $downlines = $downlines ? $downlines : '';
        return view('member_page.member_profile', compact('member', 'downlines'));
    }

    public function downline($upline)
    {
        $downline_li = '';
        if($upline == 0)
            return 1;
        $downline = new Member;
        $downline = $downline->where('downline_of',$upline)->orderBy('tree_position', 'asc')->get()->toArray();
        $upline_data = new Member;
        $upline_data = $upline_data->where('woh_member',$upline)->get()->toArray();
        if(!empty($downline))
        {
            if(count($downline) > 1)
            {
                foreach($downline as $key => $dl)
                {
                    $downline_li .= "\n<li><a class='with_node' title=''><span class=\"span\">{$dl['woh_member']}-{$dl['username']}</span><img src=\"member_page/images/{$dl['picture']}\" alt=\"Raspberry\"/></a>";
                    $level_data = [
                        "parent_member" => $upline,
                        "downline_member" => $dl['woh_member'],
                        "main_position" => ($key == 0 ? 'left' : 'right'),
                        "sub_position" => ($key == 0 ? 'left' : 'right'),
                        "level" => 1,
                        'status' => 1
                    ];
                    if(empty(DownlineLevel::where(['parent_member'=>$upline, 'downline_member'=>$dl['woh_member']])->get()->toArray()))
                        DownlineLevel::create($level_data);
                    $downline_li .= $this->downline_all($dl['woh_member'], 1, $key == 0 ? 'left' : 'right', $upline);
                    $downline_li .= "\n</li>";
                }
            }
            else
            {
                if($downline[0]['tree_position'] == "right")
                {
                    $downline_li .= "\n<li><a class='empty_node' data-woh_member='{$upline}' data-position=\"left\" data-username=\"{$upline_data[0]['username']}\"><img src=\"member_page/images/offline.png\" alt=\"Raspberry\"/></a></li>";
                    $downline_li .= "\n<li><a class='with_node' data-level=\"1-r\"><span class=\"span\">{$downline[0]['woh_member']}-{$downline[0]['username']}</span><img src=\"member_page/images/{$downline[0]['picture']}\" alt=\"Raspberry\"/></a>";
                    $level_data = [
                        "parent_member" => $upline,
                        "downline_member" => $downline[0]['woh_member'],
                        "main_position" => 'right',
                        "sub_position" => 'right',
                        "level" => 1,
                        'status' => 1
                    ];
                    if(empty(DownlineLevel::where(['parent_member'=>$upline, 'downline_member'=>$downline[0]['woh_member']])->get()->toArray()))
                        DownlineLevel::create($level_data);
                    $downline_li .= $this->downline_all($downline[0]['woh_member'],1, 'right', $upline);
                    $downline_li .= "</li>";
                }
                else
                {
                    $downline_li .= "\n<li><a class='with_node' data-level=\"1-l\"><span class=\"span\">{$downline[0]['woh_member']}-{$downline[0]['username']}</span><img src=\"member_page/images/{$downline[0]['picture']}\" alt=\"Raspberry\"/></a>";
                    $level_data = [
                        "parent_member" => $upline,
                        "downline_member" => $downline[0]['woh_member'],
                        "main_position" => 'left',
                        "sub_position" => 'left',
                        "level" => 1,
                        'status' => 1
                    ];
                    if(empty(DownlineLevel::where(['parent_member'=>$upline, 'downline_member'=>$downline[0]['woh_member']])->get()->toArray()))
                        DownlineLevel::create($level_data);
                    $downline_li .= $this->downline_all($downline[0]['woh_member'], 1, 'left', $upline);
                    $downline_li .= "</li>";
                    $downline_li .= "\n<li><a class='empty_node' data-woh_member='{$upline}' data-position=\"right\" data-username=\"{$upline_data[0]['username']}\"><img src=\"member_page/images/offline.png\" alt=\"Raspberry\"/></a></li>";
                }
            }
        }
        return $downline_li;
    }

    public function downline_all($upline, $level=0, $main_pos = '', $sponsor)
    {
        $box = '';
        $level++;
        $downline = new Member;
        $downline = $downline->where(['downline_of' => $upline])->orderBy("tree_position","asc")->get()->toArray();
        $upline_data = new Member;
        $upline_data = $upline_data->where('woh_member',$upline)->get()->toArray();
        $box .= "\n<ul>";
        if(!empty($downline))
        {
            if(count($downline) > 1)
            {

                $box .= "\n<li><a class='with_node'><span class=\"span\">{$downline[0]['woh_member']}-{$downline[0]['username']}</span><img src=\"member_page/images/{$downline[0]['picture']}\" alt=\"Raspberry\"/></a>";
                $level_data = [
                    "parent_member" => $sponsor,
                    "downline_member" => $downline[0]['woh_member'],
                    "main_position" => $main_pos,
                    "sub_position" => 'left',
                    "level" => $level,
                    'status' => 1
                ];
                if(empty(DownlineLevel::where(['parent_member'=>$sponsor, 'downline_member'=>$downline[0]['woh_member']])->get()->toArray()))
                    DownlineLevel::create($level_data);
                $box .= $this->downline_all($downline[0]['woh_member'], $level, $main_pos, $sponsor);
                $box .= "</li>";
                $box .= "\n<li><a class='with_node'><span class=\"span\">{$downline[1]['woh_member']}-{$downline[1]['username']}</span><img src=\"member_page/images/{$downline[1]['picture']}\" alt=\"Raspberry\"/></a>";
                $level_data = [
                    "parent_member" => $sponsor,
                    "downline_member" => $downline[1]['woh_member'],
                    "main_position" => $main_pos,
                    "sub_position" => 'right',
                    "level" => $level,
                    'status' => 1 
                ];
                if(empty(DownlineLevel::where(['parent_member'=>$sponsor, 'downline_member'=>$downline[1]['woh_member']])->get()->toArray()))
                    DownlineLevel::create($level_data);
                $box .= $this->downline_all($downline[1]['woh_member'], $level, $main_pos, $sponsor);
                $box .= "</li>";
            }
            else
            {
                if($downline[0]['tree_position'] == "right")
                {
                    $box .= "\n<li><a class='empty_node' data-woh_member='{$upline}' data-position=\"left\" data-username=\"{$upline_data[0]['username']}\"><img src=\"member_page/images/offline.png\" alt=\"Raspberry\"/></a></li>";
                    $box .= "\n<li><a class='with_node'><span class=\"span\">{$downline[0]['woh_member']}-{$downline[0]['username']}</span><img src=\"member_page/images/{$downline[0]['picture']}\" alt=\"Raspberry\"/></a>";
                    $level_data = [
                        "parent_member" => $sponsor,
                        "downline_member" => $downline[0]['woh_member'],
                        "main_position" => $main_pos,
                        "sub_position" => 'right',
                        "level" => $level,
                        'status' => 1
                    ];
                    if(empty(DownlineLevel::where(['parent_member'=>$sponsor, 'downline_member'=>$downline[0]['woh_member']])->get()->toArray()))
                        DownlineLevel::create($level_data);
                    $box .= $this->downline_all($downline[0]['woh_member'], $level, $main_pos, $sponsor);
                    $box .= "</li>";
                }
                else
                {
                    $box .= "\n<li><a class='with_node'><span class=\"span\">{$downline[0]['woh_member']}-{$downline[0]['username']}</span><img src=\"member_page/images/{$downline[0]['picture']}\" alt=\"Raspberry\"/></a>";
                    $level_data = [
                        "parent_member" => $sponsor,
                        "downline_member" => $downline[0]['woh_member'],
                        "main_position" => $main_pos,
                        "sub_position" => 'left',
                        "level" => $level,
                        'status' => 1
                    ];
                    if(empty(DownlineLevel::where(['parent_member'=>$sponsor, 'downline_member'=>$downline[0]['woh_member']])->get()->toArray()))
                        DownlineLevel::create($level_data);
                    $box .= $this->downline_all($downline[0]['woh_member'], $level, $main_pos, $sponsor);
                    $box .= "</li>";
                    $box .= "\n<li><a class='empty_node' data-woh_member='{$upline}' data-position=\"right\" data-level='{$level}-{$main_pos}' data-username=\"{$upline_data[0]['username']}\"><img src=\"member_page/images/offline.png\" alt=\"Raspberry\"/></a></li>";
                }
            }

        }
        else
        {
            $box .= "\n<li><a class='empty_node' data-woh_member='{$upline}' data-position=\"left\" data-level='{$level}-{$main_pos}' data-username=\"{$upline_data[0]['username']}\"><img src=\"member_page/images/offline.png\" alt=\"Raspberry\"/></a></li>";
            $box .= "\n<li><a class='empty_node' data-woh_member='{$upline}' data-position=\"right\" data-level='{$level}-{$main_pos}' data-username=\"{$upline_data[0]['username']}\"><img src=\"member_page/images/offline.png\" alt=\"Raspberry\"/></a></li>";
        }
        $box .= "\n</ul>";
        return $box;
    }

    public function post_member_login(Request $request)
    {
        $this->validate($request, [
            'username' => 'required', 'password' => 'required',
        ]);// Returns response with validation errors if any, and 422 Status Code (Unprocessable Entity)
        $member = new Member;
        $member = $member->where(['username'=>$request->username, 'password' => $request->password])->first();

        if (!empty($member))
        {
            $request->session()->put('member', $member->woh_member);
            return response(['msg' => 'Login Successfull', 'member'=>$member->woh_member], 200) // 200 Status Code: Standard response for successful HTTP request
            ->header('Content-Type', 'application/json');
        }

        return response(['msg' => 'Member not found!'], 401) // 401 Status Code: Forbidden, needs authentication
        ->header('Content-Type', 'application/json');

    }

    public function member_logout(Request $request)
    {
        $request->session()->forget('member');
        $redirect = action('HomepageController@index');
        return redirect($redirect);
    }

    public function post_member_add(Request $request)
    {
        $this->validate($request, [
            "first_name" => 'required',
            "last_name" => 'required',
            "middle_name" => 'required',
            "address" => 'required',
            "bday" => 'required',
            "gender" => 'required',
            "tree_position" => 'required',
            "sponsor" => 'required',
            "downline_of" => 'required',
            "picture" => 'required',
            "username" => 'required',
            "password" =>'required',
            "re-password" =>'required',
            "entry_code" =>'required',
            "pin_code" =>'required',
        ]);// Returns response with validation errors if any, and 422 Status Code (Unprocessable Entity)

        $data = [
            "first_name" => $request->first_name,
            "last_name" => $request->last_name,
            "middle_name" => $request->middle_name,
            "address" => $request->address,
            "bday" => $request->bday,
            "gender" => $request->gender,
            "tree_position" => $request->tree_position,
            "sponsor" => $request->sponsor,
            "downline_of" => $request->downline_of,
            "picture" => $request->picture,
            "username" => $request->username,
            "password" => $request->password,
        ];
        $member = Member::create($data);
        if (!empty($member))
        {
            $tran_data = [
                "woh_member" => $request->sponsor,
                "woh_transaction_type" => 2,
                "transaction_date" => Carbon::now(),
                "tran_amount" => 200,
                "transaction_referred" => $member->woh_member,
                'status' => 1
            ];
            MemberTransaction::create($tran_data);

            $pair_dl = new Member;
            $pair_dl = $pair_dl->where('downline_of',$request->downline_of)->get()->toArray();
            if($pair_dl && count($pair_dl) == 2)
            {
                $tran_data = [
                    "woh_member" => $request->downline_of,
                    "woh_transaction_type" => 3,
                    "transaction_date" => Carbon::now(),
                    "tran_amount" => 200,
                    "transaction_referred" => null,
                    'status' => 1
                ];
                MemberTransaction::create($tran_data);
            }
            return response(['msg' => 'Login Successfull', 'member'=>$member->woh_member], 200) // 200 Status Code: Standard response for successful HTTP request
            ->header('Content-Type', 'application/json');
        }

        return response(['msg' => 'Member not found!'], 401) // 401 Status Code: Forbidden, needs authentication
        ->header('Content-Type', 'application/json');

    }

    public function member_transactions(Request $request)
    {
        $level_arr = [];
        if(!$request->session()->get('member'))
        {
            $redirect = action('HomepageController@index');
            return redirect($redirect);
        }
        $member_trans = new MemberTransaction;
        $member_tran = $member_trans->join('woh_transaction_type', 'woh_member_transaction.woh_transaction_type', '=', 'woh_transaction_type.woh_transaction_type')
            ->where('woh_member',$request->session()->get('member'))->orderBy('woh_member_transaction','asc')->get()->toArray();

        $level_pair = new DownlineLevel;
        $level = $level_pair->where(['parent_member'=>$request->session()->get('member')])->max('level');

        if($level > 0)
        {
            for($x=2; $x<=$level; $x++)
            {
                $total_counts_left = $total_counts_right = 0;
                $level_pair_main_left_sub_left = $level_pair->join('woh_transaction_type', 'woh_downline_level.woh_transaction_type', '=', 'woh_transaction_type.woh_transaction_type')
                    ->where(['parent_member'=>$request->session()->get('member'), 'main_position'=>'left', 'sub_position'=>'left', 'level' => $x])->get()->toArray();
                $level_pair_main_left_sub_right = $level_pair->join('woh_transaction_type', 'woh_downline_level.woh_transaction_type', '=', 'woh_transaction_type.woh_transaction_type')
                    ->where(['parent_member'=>$request->session()->get('member'), 'main_position'=>'left', 'sub_position'=>'right', 'level' => $x])->get()->toArray();

                $level_pair_main_right_sub_left = $level_pair->join('woh_transaction_type', 'woh_downline_level.woh_transaction_type', '=', 'woh_transaction_type.woh_transaction_type')
                    ->where(['parent_member'=>$request->session()->get('member'), 'main_position'=>'right', 'sub_position'=>'left', 'level' => $x])->get()->toArray();
                $level_pair_main_right_sub_right = $level_pair->join('woh_transaction_type', 'woh_downline_level.woh_transaction_type', '=', 'woh_transaction_type.woh_transaction_type')
                    ->where(['parent_member'=>$request->session()->get('member'), 'main_position'=>'right', 'sub_position'=>'right', 'level' => $x])->get()->toArray();

                if(!empty($level_pair_main_left_sub_left) && !empty($level_pair_main_right_sub_right))
                    $total_counts_left = count($level_pair_main_left_sub_left) > 3 ? 3 : count($level_pair_main_right_sub_right);

                if(!empty($level_pair_main_left_sub_right) && !empty($level_pair_main_right_sub_left))
                    $total_counts_right = count($level_pair_main_left_sub_right) > 3 ? 3 : count($level_pair_main_right_sub_left);

                if($total_counts_left > 0 || $total_counts_right > 0)
                {
                    $total_counts = ($total_counts_left + $total_counts_right > 3) ? 3 : ($total_counts_left + $total_counts_right);
                    $member_tran[] = [
                        "woh_member_transaction" => "----------",
                        "woh_member" => null,
                        "woh_transaction_type" => 5,
                        "transaction_date" => Carbon::now()->format('m/d/Y H:i A'),
                        "tran_amount" => ($total_counts * 200),
                        "transaction_referred" => null,
                        "no_of_pairs" => null,
                        "status" => 1,
                        "transaction_type" => "Level Pair",
                        "level" => $x
                    ];
                }
            }
        }
        return view('member_page.transaction_earnings', compact('member_tran'));
    }
}
