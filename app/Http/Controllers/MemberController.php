<?php

namespace App\Http\Controllers;

use App\Models\DownlineLevel;
use App\Models\GiftCertificate;
use App\Models\Member;
use App\Models\MemberCredit;
use App\Models\MemberGC;
use App\Models\MemberTransaction;
use App\Models\MemberUnilevelEarning;
use App\Models\ShortCodes;
use App\Models\Unilevel;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class MemberController extends Controller
{
    public function member_profile(Request $request)
    {
        if(!$request->session()->get('woh_member'))
        {
            $redirect = action('HomepageController@index');
            return redirect($redirect);
        }
        $member = new Member;
        $member = $member->where('woh_member',$request->session()->get('woh_member'))->get();
        $member_heads = Member::where(['last_name'=>$member[0]->last_name, 'first_name'=>$member[0]->first_name, 'middle_name'=>$member[0]->middle_name])->count();
        $downlines = $this->downline($member[0]->woh_member);
        $downlines = $downlines ? $downlines : '';
        $unilevel_period = MemberUnilevelEarning::where('woh_member',$request->session()->get('woh_member'))->where('period_cover_end', '>', Carbon::now())->where('period_cover_start','<=',Carbon::now())->where('status',0)->get()->toArray();

        $unilevel_period_id = MemberUnilevelEarning::where('woh_member',$request->session()->get('woh_member'))->where('period_cover_end', '>', Carbon::now())->where('period_cover_start','<=',Carbon::now())->get(['woh_member_unilevel_earning'])->toArray();
        if(!empty($unilevel_period_id)) :
            $period_cover_count = Unilevel::where('woh_member_unilevel_earning', $unilevel_period_id[0]['woh_member_unilevel_earning'])->count();
            if($period_cover_count > 3)
            {
                MemberUnilevelEarning::find($unilevel_period_id[0]['woh_member_unilevel_earning'])->update(['status'=>1]);
                $level = [];
                $downlines2 = Member::where('sponsor',$request->session()->get('woh_member'))->limit(10)->get(['woh_member'])->toArray();
                if(!empty($downlines2))
                {
                    foreach($downlines2 as $dl)
                    {
                        $level[1][] = $dl['woh_member'];
                    }
                    $x=1;
                    while(!empty($level[$x]))
                    {
                        $level[] = $this->unilevel_dp($level[$x], $x);
                        $x++;
                    }
                }
                if(!empty($level))
                {
                    $period_covers = MemberUnilevelEarning::where('woh_member',$request->session()->get('woh_member'))->where('woh_member_unilevel_earning', $unilevel_period_id[0]['woh_member_unilevel_earning'])->get()->toArray();
                    for($i=1; $i<= 5; $i++)
                    {
                        if(!empty($level[$i]))
                        {
                            $level_count = MemberUnilevelEarning::whereIn('woh_member',$level[$i])
                                ->whereBetween('period_cover_start', [$period_covers[0]['period_cover_start'], $period_covers[0]['period_cover_end']])
                                ->where('status',1)->count();
                            MemberUnilevelEarning::where('woh_member_unilevel_earning',$unilevel_period_id[0]['woh_member_unilevel_earning'])->update(['level'.$i.'_earn'=>(($level_count * 2000) * ($i<=2 ? .007 : .001))]);
                        }
                    }
                }
            }
        endif;
        return view('member_page.member_profile', compact('member', 'downlines', 'member_heads','unilevel_period'));
    }

    public function post_member_search(Request $request)
    {
        $member = new Member;
        $member = $member->where('woh_member',$request->woh_member)->get();
        return response(['msg' => 'Login Successfull', 'woh_member'=>$member], 200) // 200 Status Code: Standard response for successful HTTP request
        ->header('Content-Type', 'application/json');
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
                    if($dl['status'] == 1 && empty(DownlineLevel::where(['parent_member'=>$upline, 'downline_member'=>$dl['woh_member']])->get()->toArray()))
                        DownlineLevel::create($level_data);
                    $downline_li .= $this->downline_all($dl['woh_member'], 1, $key == 0 ? 'left' : 'right', $upline);
                    $downline_li .= "\n</li>";
                }
            }
            else
            {
                if($downline[0]['tree_position'] == "right")
                {
                    $downline_li .= "\n<li><a class='empty_node' data-cur_level = '1' data-woh_member='{$upline}' data-position=\"left\" data-username=\"{$upline_data[0]['username']}\"><img src=\"member_page/images/offline.png\" alt=\"Raspberry\"/></a></li>";
                    $downline_li .= "\n<li><a class='with_node' data-level=\"1-r\"><span class=\"span\">{$downline[0]['woh_member']}-{$downline[0]['username']}</span><img src=\"member_page/images/{$downline[0]['picture']}\" alt=\"Raspberry\"/></a>";
                    $level_data = [
                        "parent_member" => $upline,
                        "downline_member" => $downline[0]['woh_member'],
                        "main_position" => 'right',
                        "sub_position" => 'right',
                        "level" => 1,
                        'status' => 1
                    ];
                    if($downline[0]['status']==1 && empty(DownlineLevel::where(['parent_member'=>$upline, 'downline_member'=>$downline[0]['woh_member']])->get()->toArray()))
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
                    if($downline[0]['status']==1 && empty(DownlineLevel::where(['parent_member'=>$upline, 'downline_member'=>$downline[0]['woh_member']])->get()->toArray()))
                        DownlineLevel::create($level_data);
                    $downline_li .= $this->downline_all($downline[0]['woh_member'], 1, 'left', $upline);
                    $downline_li .= "</li>";
                    $downline_li .= "\n<li><a class='empty_node' data-cur_level = '1' data-woh_member='{$upline}' data-position=\"right\" data-username=\"{$upline_data[0]['username']}\"><img src=\"member_page/images/offline.png\" alt=\"Raspberry\"/></a></li>";
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
                if($downline[0]['status']==1 && empty(DownlineLevel::where(['parent_member'=>$sponsor, 'downline_member'=>$downline[0]['woh_member']])->get()->toArray()))
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
                if($downline[1]['status']==1 && empty(DownlineLevel::where(['parent_member'=>$sponsor, 'downline_member'=>$downline[1]['woh_member']])->get()->toArray()))
                    DownlineLevel::create($level_data);
                $box .= $this->downline_all($downline[1]['woh_member'], $level, $main_pos, $sponsor);
                $box .= "</li>";
            }
            else
            {
                if($downline[0]['tree_position'] == "right")
                {
                    $box .= "\n<li><a class='empty_node' data-cur_level = '{$level}' data-woh_member='{$upline}' data-position=\"left\" data-username=\"{$upline_data[0]['username']}\"><img src=\"member_page/images/offline.png\" alt=\"Raspberry\"/></a></li>";
                    $box .= "\n<li><a class='with_node'><span class=\"span\">{$downline[0]['woh_member']}-{$downline[0]['username']}</span><img src=\"member_page/images/{$downline[0]['picture']}\" alt=\"Raspberry\"/></a>";
                    $level_data = [
                        "parent_member" => $sponsor,
                        "downline_member" => $downline[0]['woh_member'],
                        "main_position" => $main_pos,
                        "sub_position" => 'right',
                        "level" => $level,
                        'status' => 1
                    ];
                    if($downline[0]['status']==1 && empty(DownlineLevel::where(['parent_member'=>$sponsor, 'downline_member'=>$downline[0]['woh_member']])->get()->toArray()))
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
                    if($downline[0]['status']==1 && empty(DownlineLevel::where(['parent_member'=>$sponsor, 'downline_member'=>$downline[0]['woh_member']])->get()->toArray()))
                        DownlineLevel::create($level_data);
                    $box .= $this->downline_all($downline[0]['woh_member'], $level, $main_pos, $sponsor);
                    $box .= "</li>";
                    $box .= "\n<li><a class='empty_node' data-cur_level = '{$level}'  data-woh_member='{$upline}' data-position=\"right\" data-level='{$level}-{$main_pos}' data-username=\"{$upline_data[0]['username']}\"><img src=\"member_page/images/offline.png\" alt=\"Raspberry\"/></a></li>";
                }
            }

        }
        else
        {
            $box .= "\n<li><a class='empty_node' data-cur_level = '{$level}'  data-woh_member='{$upline}' data-position=\"left\" data-level='{$level}-{$main_pos}' data-username=\"{$upline_data[0]['username']}\"><img src=\"member_page/images/offline.png\" alt=\"Raspberry\"/></a></li>";
            $box .= "\n<li><a class='empty_node' data-cur_level = '{$level}'  data-woh_member='{$upline}' data-position=\"right\" data-level='{$level}-{$main_pos}' data-username=\"{$upline_data[0]['username']}\"><img src=\"member_page/images/offline.png\" alt=\"Raspberry\"/></a></li>";
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
            $request->session()->put('woh_member', $member->woh_member);
            return response(['msg' => 'Login Successfull', 'woh_member'=>$member->woh_member], 200) // 200 Status Code: Standard response for successful HTTP request
            ->header('Content-Type', 'application/json');
        }

        return response(['msg' => 'Member not found!'], 401) // 401 Status Code: Forbidden, needs authentication
        ->header('Content-Type', 'application/json');

    }

    public function member_logout(Request $request)
    {
        $request->session()->forget('woh_member');
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
            "account_type" => 'required',
            "pin_code" =>'required',
        ]);// Returns response with validation errors if any, and 422 Status Code (Unprocessable Entity)

        if($request->account_type == 'entry_code')
        {
            $this->validate($request, ["entry_code" =>'required']);
            if(empty(ShortCodes::where(['type'=>1, 'code'=>$request->entry_code])->get()->toArray()))
                return response(['msg' => 'Entry code not found!'], 401)->header('Content-Type', 'application/json');
            if(empty($rudy=GiftCertificate::where(['entry_code'=>$request->entry_code, 'status'=>0])->get()->toArray()))
                return response(['msg' => 'Entry code was already used!'], 401)->header('Content-Type', 'application/json');
        }

        if($request->account_type == 'cd_code')
        {
            $this->validate($request, ["cd_code" => 'required']);
            if(empty(ShortCodes::where(['type'=>3, 'code'=>$request->cd_code])->get()->toArray()))
                return response(['msg' => 'CD code not found!'], 401)->header('Content-Type', 'application/json');
            if(empty(GiftCertificate::where(['cd_code'=>$request->cd_code, 'status'=>3])->get()->toArray()))
                return response(['msg' => 'CD code was already used!'], 401)->header('Content-Type', 'application/json');
        }

        if(empty(ShortCodes::where(['type'=>2, 'code'=>$request->pin_code])->get()->toArray()))
            return response(['msg' => 'Pin code not found!'], 401)->header('Content-Type', 'application/json');
        if(empty(GiftCertificate::where(['pin_code'=>$request->pin_code, 'status'=>($request->cd_code ? 3 : 0)])->get()->toArray()))
            return response(['msg' => 'Pin code was already used!'], 401)->header('Content-Type', 'application/json');

        if($request->account_type == 'cd_code')
            \DB::table('woh_gc')->where(['cd_code' => $request->cd_code])->update(['status'=>4]);
        else
            \DB::table('woh_gc')->where(['entry_code' => $request->entry_code])->update(['status'=>1]);

        $picture = '';
        if($request->account_type == 'entry_code')
        {
            if($request->gender == 'male')
                $picture = 'online-m.png';
            else
                $picture = 'online-f.png';
        }
        else
        {
            if($request->gender == 'male')
                $picture = 'floating-m.png';
            else
                $picture = 'floating-f.png';
        }
        $data = [
            "first_name" => ucfirst(strtolower($request->first_name)),
            "last_name" => ucfirst(strtolower($request->last_name)),
            "middle_name" => ucfirst(strtolower($request->middle_name)),
            "address" => $request->address,
            "bday" => $request->bday,
            "gender" => $request->gender,
            "tree_position" => $request->tree_position,
            "sponsor" => $request->sponsor,
            "downline_of" => $request->downline_of,
            "picture" => $picture,
            "username" => $request->username,
            "password" => $request->password,
            "status" => $request->account_type == 'entry_code' ? 1 : 0,
            "cd_code" => $request->account_type == 'cd_code' ? $request->cd_code : null,
            "entry_code" => $request->account_type == 'entry_code' ? $request->entry_code : null,
            "pin_code" => $request->pin_code,
            "level" => $request->level
        ];
        $member = Member::create($data);
        if (!empty($member))
        {
            if($request->account_type == 'entry_code')
            {
                $tran_data = [
                    "woh_member" => $request->sponsor,
                    "woh_transaction_type" => 2,
                    "transaction_date" => Carbon::now(),
                    "tran_amount" => 400,
                    "transaction_referred" => $member->woh_member,
                    'status' => 1,
                    "level" => $request->level
                ];
                MemberTransaction::create($tran_data);

                $pair_dl = new Member;
                $pair_dl = $pair_dl->where(['downline_of'=>$request->downline_of, 'status' => 1])->get()->toArray();
                if($pair_dl && count($pair_dl) == 2)
                {
                    $tran_data = [
                        "woh_member" => $request->downline_of,
                        "woh_transaction_type" => 3,
                        "transaction_date" => Carbon::now(),
                        "tran_amount" => 100,
                        "transaction_referred" => null,
                        'status' => 1
                    ];
                    MemberTransaction::create($tran_data);
                }
            }
            else
            {
                $tran_data = [
                    "woh_member" => $member->woh_member,
                    "credit_date" => Carbon::now(),
                    "credit_amount" => 2988,
                ];
                MemberCredit::create($tran_data);
            }

            $months_plus_1 = date('Y-m-d', strtotime("+1 month", strtotime($member->created_at)));
            MemberUnilevelEarning::create([
                'woh_member' => $member->woh_member,
                'period_cover_start' => $member->created_at,
                'period_cover_end' => $months_plus_1,
                'level1_earn' => 0,
                'level5_earn' => 0,
                'level2_earn' => 0,
                'level3_earn' => 0,
                'level4_earn' => 0,
                'status' => 0
            ]);

            return response(['msg' => 'Login Successfull', 'woh_member'=>$member->woh_member], 200) // 200 Status Code: Standard response for successful HTTP request
            ->header('Content-Type', 'application/json');
        }

        return response(['msg' => 'Member not found!'], 401) // 401 Status Code: Forbidden, needs authentication
        ->header('Content-Type', 'application/json');

    }

    public function post_member_update(Request $request)
    {
        $this->validate($request, [
            "first_name" => 'required',
            "last_name" => 'required',
            "middle_name" => 'required',
            "address" => 'required',
            "bday" => 'required',
            "gender" => 'required',
        ]);// Returns response with validation errors if any, and 422 Status Code (Unprocessable Entity)

        $member = Member::where('woh_member', $request->woh_member)->get()->toArray();

        $data = [
            "first_name" => ucfirst(strtolower($request->first_name)),
            "last_name" => ucfirst(strtolower($request->last_name)),
            "middle_name" => ucfirst(strtolower($request->middle_name)),
            "address" => $request->address,
            "bday" => $request->bday,
            "gender" => $request->gender,
            "password" => $request->password ? $request->password : $member[0]['password']
        ];
        \DB::table('woh_member')->where('woh_member', $request->woh_member)->update($data);
        return response(['msg' => 'Login Successfull'], 200) // 200 Status Code: Standard response for successful HTTP request
        ->header('Content-Type', 'application/json');
    }

    public function member_transactions(Request $request)
    {
        $level_arr = [];
        if(!$request->session()->get('woh_member'))
        {
            $redirect = action('HomepageController@index');
            return redirect($redirect);
        }
        $member = new Member;
        $member = $member->where('woh_member',$request->session()->get('woh_member'))->get();

        $member_trans = new MemberTransaction;
        $member_tran = $member_trans->join('woh_transaction_type', 'woh_member_transaction.woh_transaction_type', '=', 'woh_transaction_type.woh_transaction_type')
            ->where('woh_member_transaction.woh_member',$request->session()->get('woh_member'))->orderBy('woh_member_transaction','asc')->get()->toArray();

        $member_credit = MemberCredit::where('woh_member',$request->session()->get('woh_member'))->get()->toArray();

        $level_pair = new DownlineLevel;
        $level = $level_pair->where(['parent_member'=>$request->session()->get('woh_member')])->max('level');

        if($level > 0)
        {
            for($x=2; $x<=$level; $x++)
            {
                $total_counts_left = $total_counts_right = 0;
                $level_pair_main_left_sub_left = $level_pair->join('woh_transaction_type', 'woh_downline_level.woh_transaction_type', '=', 'woh_transaction_type.woh_transaction_type')
                    ->where(['parent_member'=>$request->session()->get('woh_member'), 'main_position'=>'left', 'sub_position'=>'left', 'level' => $x])->get()->toArray();
                $level_pair_main_left_sub_right = $level_pair->join('woh_transaction_type', 'woh_downline_level.woh_transaction_type', '=', 'woh_transaction_type.woh_transaction_type')
                    ->where(['parent_member'=>$request->session()->get('woh_member'), 'main_position'=>'left', 'sub_position'=>'right', 'level' => $x])->get()->toArray();

                $level_pair_main_right_sub_left = $level_pair->join('woh_transaction_type', 'woh_downline_level.woh_transaction_type', '=', 'woh_transaction_type.woh_transaction_type')
                    ->where(['parent_member'=>$request->session()->get('woh_member'), 'main_position'=>'right', 'sub_position'=>'left', 'level' => $x])->get()->toArray();
                $level_pair_main_right_sub_right = $level_pair->join('woh_transaction_type', 'woh_downline_level.woh_transaction_type', '=', 'woh_transaction_type.woh_transaction_type')
                    ->where(['parent_member'=>$request->session()->get('woh_member'), 'main_position'=>'right', 'sub_position'=>'right', 'level' => $x])->get()->toArray();

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
                        "woh_transaction_type" => ($x%5==0) ? 4 : 5,
                        "transaction_date" => Carbon::now(),
                        "tran_amount" => ($x%5==0) ? 300 : ($total_counts * 100),
                        "transaction_referred" => null,
                        "no_of_pairs" => null,
                        "status" => 1,
                        "transaction_type" => ($x%5==0) ? "GC worth 300 pesos" : "Level Pair",
                        "level" => $x
                    ];
                }
            }

            $unilevel_period = MemberUnilevelEarning::where('woh_member',$request->session()->get('woh_member'))
                ->groupBy('woh_member')
                ->select([
                    DB::raw('SUM(level1_earn) AS level1_earn'),
                    DB::raw('SUM(level2_earn) AS level2_earn'),
                    DB::raw('SUM(level3_earn) AS level3_earn'),
                    DB::raw('SUM(level4_earn) AS level4_earn'),
                    DB::raw('SUM(level5_earn) AS level5_earn')])->get()->toArray();
            if(!empty($unilevel_period))
            {
                for($x=1; $x<=5; $x++)
                {
                    $member_tran[] = [
                        "woh_member_transaction" => "----------",
                        "woh_member" => null,
                        "woh_transaction_type" => 8,
                        "transaction_date" => Carbon::now(),
                        "tran_amount" =>  $unilevel_period[0]['level'.$x.'_earn'],
                        "transaction_referred" => null,
                        "no_of_pairs" => null,
                        "status" => 1,
                        "transaction_type" => "Unilevel Earnings",
                        "level" => $x
                    ];
                }
            }
        }
        $member_gc_claim = MemberGC::where('woh_member',$request->session()->get('woh_member'))->where('woh_gc', '>', 0)->sum('gc_qty');
        $unilevel_period = MemberUnilevelEarning::where('woh_member',$request->session()->get('woh_member'))->where('period_cover_end', '>', Carbon::now())->where('period_cover_start','<=',Carbon::now())->where('status',0)->get()->toArray();
        return view('member_page.transaction_earnings', compact('member_tran', 'member', 'member_credit', 'member_gc_claim', 'unilevel_period'));
    }

    public function member_withdrawals(Request $request)
    {
        $level_arr = [];
        if(!$request->session()->get('woh_member'))
        {
            $redirect = action('HomepageController@index');
            return redirect($redirect);
        }
        $member = new Member;
        $member = $member->where('woh_member',$request->session()->get('woh_member'))->get();

        $member_trans = new MemberTransaction;
        $member_tran = $member_trans->join('woh_transaction_type', 'woh_member_transaction.woh_transaction_type', '=', 'woh_transaction_type.woh_transaction_type')
            ->where('woh_member',$request->session()->get('woh_member'))->orderBy('woh_member_transaction','asc')->get()->toArray();

        $member_credit = MemberCredit::where('woh_member',$request->session()->get('woh_member'))->get()->toArray();

        $level_pair = new DownlineLevel;
        $level = $level_pair->where(['parent_member'=>$request->session()->get('woh_member')])->max('level');

        if($level > 0)
        {
            for($x=2; $x<=$level; $x++)
            {
                $total_counts_left = $total_counts_right = 0;
                $level_pair_main_left_sub_left = $level_pair->join('woh_transaction_type', 'woh_downline_level.woh_transaction_type', '=', 'woh_transaction_type.woh_transaction_type')
                    ->where(['parent_member'=>$request->session()->get('woh_member'), 'main_position'=>'left', 'sub_position'=>'left', 'level' => $x])->get()->toArray();
                $level_pair_main_left_sub_right = $level_pair->join('woh_transaction_type', 'woh_downline_level.woh_transaction_type', '=', 'woh_transaction_type.woh_transaction_type')
                    ->where(['parent_member'=>$request->session()->get('woh_member'), 'main_position'=>'left', 'sub_position'=>'right', 'level' => $x])->get()->toArray();

                $level_pair_main_right_sub_left = $level_pair->join('woh_transaction_type', 'woh_downline_level.woh_transaction_type', '=', 'woh_transaction_type.woh_transaction_type')
                    ->where(['parent_member'=>$request->session()->get('woh_member'), 'main_position'=>'right', 'sub_position'=>'left', 'level' => $x])->get()->toArray();
                $level_pair_main_right_sub_right = $level_pair->join('woh_transaction_type', 'woh_downline_level.woh_transaction_type', '=', 'woh_transaction_type.woh_transaction_type')
                    ->where(['parent_member'=>$request->session()->get('woh_member'), 'main_position'=>'right', 'sub_position'=>'right', 'level' => $x])->get()->toArray();

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
                        "woh_transaction_type" => ($x%5==0) ? 4 : 5,
                        "transaction_date" => Carbon::now(),
                        "tran_amount" => ($x%5==0) ? 300 : ($total_counts * 100),
                        "transaction_referred" => null,
                        "no_of_pairs" => null,
                        "status" => 1,
                        "transaction_type" => ($x%5==0) ? "GC worth 300 pesos" : "Level Pair",
                        "level" => $x
                    ];
                }

            }
            $unilevel_period = MemberUnilevelEarning::where('woh_member',$request->session()->get('woh_member'))
                ->groupBy('woh_member')
                ->select([
                    DB::raw('SUM(level1_earn) AS level1_earn'),
                    DB::raw('SUM(level2_earn) AS level2_earn'),
                    DB::raw('SUM(level3_earn) AS level3_earn'),
                    DB::raw('SUM(level4_earn) AS level4_earn'),
                    DB::raw('SUM(level5_earn) AS level5_earn')])->get()->toArray();
            if(!empty($unilevel_period))
            {
                for($x=1; $x<=5; $x++)
                {
                    $member_tran[] = [
                        "woh_member_transaction" => "----------",
                        "woh_member" => null,
                        "woh_transaction_type" => 8,
                        "transaction_date" => Carbon::now(),
                        "tran_amount" =>  $unilevel_period[0]['level'.$x.'_earn'],
                        "transaction_referred" => null,
                        "no_of_pairs" => null,
                        "status" => 1,
                        "transaction_type" => "Unilevel Earnings",
                        "level" => $x
                    ];
                }
            }
        }
        $member_gc_claim = MemberGC::where('woh_member',$request->session()->get('woh_member'))->sum('gc_qty');
        $unilevel_period = MemberUnilevelEarning::where('woh_member',$request->session()->get('woh_member'))->where('period_cover_end', '>', Carbon::now())->where('period_cover_start','<=',Carbon::now())->where('status',0)->get()->toArray();
        return view('member_page.member_withdrawals', compact('member_tran', 'member', 'member_credit', 'member_gc_claim', 'unilevel_period'));
    }

    public function post_member_withdrawal(Request $request)
    {
        if ($request->session()->get('woh_member'))
        {
            $member = new Member;
            $member = $member->where('woh_member',$request->session()->get('woh_member'))->get()->toArray();
            $cd_payment = null;
            $change = null;
            if($member[0]['status'] == 0)
            {
                $member_credit = MemberCredit::where('woh_member',$member[0]['woh_member'])->get()->toArray();
                if($member_credit[0]['credit_amount'] < (($request->amount-($request->amount * 0.1))/2))
                {
                    $cd_payment = $member_credit[0]['credit_amount'];
                    $change = (($request->amount-($request->amount * 0.1))/2) - $member_credit[0]['credit_amount'];
                }
                else
                    $cd_payment = (($request->amount-($request->amount * 0.1))/2);
            }
            $tran_data = [
                "woh_member" => $request->woh_member,
                "woh_transaction_type" => 1,
                "transaction_date" => Carbon::now(),
                "tran_amount" => $request->amount,
                "tax" => ($request->amount * 0.1),
                "cd_payment" => $cd_payment,
                "change" => $change,
                "notes" => $request->notes,
                'status' => 2
            ];
            MemberTransaction::create($tran_data);
            return response(['msg' => 'Login Successfull'], 200) // 200 Status Code: Standard response for successful HTTP request
            ->header('Content-Type', 'application/json');
        }

        return response(['msg' => 'Member not found!'], 401) // 401 Status Code: Forbidden, needs authentication
        ->header('Content-Type', 'application/json');
    }

    public function post_member_unilevel(Request $request)
    {
        if ($request->session()->get('woh_member'))
        {
            $member = new Member;
            $member = $member->where('woh_member',$request->session()->get('woh_member'))->get()->toArray();
            if(empty(ShortCodes::where(['type'=>5,'code'=>$request->product_code, 'status'=>0])->get()->toArray()))
            {
                return response(['msg' => 'Product code not found!'], 401) // 401 Status Code: Forbidden, needs authentication
                ->header('Content-Type', 'application/json');
            }
            $data = [
                "woh_member" => $request->woh_member,
                "date_encoded" => Carbon::now(),
                "product_code" => $request->product_code,
                "woh_member_unilevel_earning" => $request->period_cover
            ];
            Unilevel::create($data);
            \DB::table('woh_short_codes')->where(['code' => $request->product_code,'type'=>5])->update(['status'=>1]);

            $period_cover_count = Unilevel::where('woh_member_unilevel_earning', $request->period_cover)->count();
            if($period_cover_count > 3)
            {
                MemberUnilevelEarning::find($request->period_cover)->update(['status'=>1]);
                $level = [];
                $downlines = Member::where('sponsor',$request->session()->get('woh_member'))->limit(10)->get(['woh_member'])->toArray();
                if(!empty($downlines))
                {
                    foreach($downlines as $dl)
                    {
                        $level[1][] = $dl['woh_member'];
                    }
                    $x=1;
                    while(!empty($level[$x]))
                    {
                        $level[] = $this->unilevel_dp($level[$x], $x);
                        $x++;
                    }
                }
                if(!empty($level))
                {
                    $period_covers = MemberUnilevelEarning::where('woh_member',$request->session()->get('woh_member'))->where('woh_member_unilevel_earning', $request->period_cover)->get()->toArray();
                    for($i=1; $i<= 5; $i++)
                    {
                        if(!empty($level[$i]))
                        {
                            $level_count = MemberUnilevelEarning::whereIn('woh_member',$level[$i])
                                ->whereBetween('period_cover_start', [$period_covers[0]['period_cover_start'], $period_covers[0]['period_cover_end']])
                                ->where('status',1)->count();
                            MemberUnilevelEarning::where('woh_member_unilevel_earning',$request->period_cover)->update(['level'.$i.'_earn'=>(($level_count * 2000) * ($i<=2 ? .007 : .001))]);
                        }
                    }
                }
            }
            return response(['msg' => 'Login Successfull'], 200) // 200 Status Code: Standard response for successful HTTP request
            ->header('Content-Type', 'application/json');
        }
    }

    public function post_member_gc_claim(Request $request)
    {
        if ($request->session()->get('woh_member'))
        {
            $member = new Member;
            $member = $member->where('woh_member',$request->session()->get('woh_member'))->get()->toArray();
            $cd_payment = null;
            $change = null;
            $tran_data = [
                "woh_member" => $request->woh_member,
                "date_claim" => Carbon::now(),
                "woh_gc" => null,
                "notes" => $request->notes,
                "gc_qty" => $request->qty
            ];
            MemberGC::create($tran_data);
            return response(['msg' => 'Login Successfull'], 200) // 200 Status Code: Standard response for successful HTTP request
            ->header('Content-Type', 'application/json');
        }

        return response(['msg' => 'Member not found!'], 401) // 401 Status Code: Forbidden, needs authentication
        ->header('Content-Type', 'application/json');
    }

    private function unilevel_dp($woh_member, $level)
    {
        $downlines_level = [];
        if(!empty($woh_member))
        {
            foreach($woh_member as $wm)
            {
                $data = Member::where('sponsor',$wm)->limit(10)->get(['woh_member'])->toArray();
                if(!empty($data))
                {
                    foreach($data as $d)
                        $downlines_level[] = $d['woh_member'];
                }
            }
        }
        return $downlines_level;
    }


}
