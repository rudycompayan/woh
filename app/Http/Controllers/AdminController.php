<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\DownlineLevel;
use App\Models\GiftCertificate;
use App\Models\Member;
use App\Models\MemberCredit;
use App\Models\MemberTransaction;
use App\Models\ShortCodes;
use App\Models\Unilevel;
use Carbon\Carbon;
use Faker\Provider\Barcode;
use Illuminate\Http\Request;

use App\Http\Requests;
use Maatwebsite\Excel\Facades\Excel;
use Milon\Barcode\DNS2D;
use Milon\Barcode\Facades\DNS1DFacade;
use Milon\Barcode\Facades\DNS2DFacade;

class AdminController extends Controller
{
    public function admin_profile(Request $request)
    {
        if(!$request->session()->get('woh_admin_user'))
        {
            $redirect = action('AdminController@admin_login');
            return redirect($redirect);
        }
        $admin = $request->session()->get('woh_admin_user');
        return view('admin_page2.admin_profile', compact('admin'));
    }

    public function admin_login(Request $request)
    {
        return view('admin_page2.admin_login');
    }

    public function post_admin_login(Requests\AdminLoginRequest $request)
    {
        $error_msg = '';
        $admin = new Admin;
        $admin = $admin->where(['username'=>$request->username, 'password' => $request->password])->get()->toArray();
        if (!empty($admin))
        {
            $request->session()->put('woh_admin_user', $admin);
            $redirect = action('AdminController@admin_profile');
            return redirect($redirect);
        }
        else
        {
            $error_msg = 'Administrator not found!';
            return view('admin_page2.admin_login', compact('error_msg'));
        }
    }

    public function admin_logout(Request $request)
    {
        $request->session()->forget('woh_admin_user');
        $redirect = action('AdminController@admin_login');
        return redirect($redirect);
    }

    public function withdrawal_request(Request $request)
    {
        if(!$request->session()->get('woh_admin_user'))
        {
            $redirect = action('HomepageController@index');
            return redirect($redirect);
        }
        $member_trans = new MemberTransaction;
        $member_tran = $member_trans->join('woh_transaction_type', 'woh_member_transaction.woh_transaction_type', '=', 'woh_transaction_type.woh_transaction_type')
            ->join('woh_member', 'woh_member.woh_member', '=', 'woh_member_transaction.woh_member')
            ->where(['woh_member_transaction.woh_transaction_type'=>1, 'woh_member_transaction.status'=>2])->orderBy('woh_member_transaction','desc')->get([
                'first_name','last_name', 'change', 'woh_member_transaction', 'tran_amount', 'tax', 'transaction_date', 'cd_payment', 'woh_member_transaction.status AS w_status', 'notes', 'woh_member.status AS m_status'
            ])->toArray();
        return view('admin_page2.withdrawal_request', compact('member_tran'));
    }

    public function short_codes(Request $request)
    {
        if(!$request->session()->get('woh_admin_user'))
        {
            $redirect = action('HomepageController@index');
            return redirect($redirect);
        }
        $entry_code = ShortCodes::where(['type'=>1, 'status'=>0])->count();
        $pin_code = ShortCodes::where(['type'=>2, 'status'=>0])->count();
        $cd_code = ShortCodes::where(['type'=>3, 'status'=>0])->count();
        $bar_code = ShortCodes::where(['type'=>4, 'status'=>0])->count();
        $product_code = ShortCodes::where(['type'=>5, 'status'=>0])->count();
        $short_codes_count = [
            'entry_count' => $entry_code,
            'pin_count' => $pin_code,
            'cd_count' => $cd_code,
            'bar_count' => $bar_code,
            'product_code_count' => $product_code
        ];
        return view('admin_page2.short_codes', compact('short_codes_count'));
    }

    public function post_short_codes(Requests\ShortCodeRequest $request)
    {
        $error_msg = '';
        if(!$request->session()->get('woh_admin_user'))
        {
            $redirect = action('HomepageController@index');
            return redirect($redirect);
        }
        else
        {
            foreach($request->type as $t)
            {
                for($x=1; $x<= $request->max_no; $x++)
                {
                    $code = $this->generatePin(10);
                    $tran_data = [
                        "code" => $code,
                        "type" => $t,
                        'status' => 0
                    ];
                    if(empty(ShortCodes::where(['code'=>$code,"type" => $t,])->get()->toArray()))
                        ShortCodes::create($tran_data);
                }
            }
            $error_msg = 'Codes successfully updated.';
        }
        $entry_code = ShortCodes::where(['type'=>1, 'status'=>0])->count();
        $pin_code = ShortCodes::where(['type'=>2, 'status'=>0])->count();
        $cd_code = ShortCodes::where(['type'=>3, 'status'=>0])->count();
        $bar_code = ShortCodes::where(['type'=>4, 'status'=>0])->count();
        $product_code = ShortCodes::where(['type'=>5, 'status'=>0])->count();
        $short_codes_count = [
            'entry_count' => $entry_code,
            'pin_count' => $pin_code,
            'cd_count' => $cd_code,
            'bar_count' => $bar_code,
            'product_code_count' => $product_code
        ];
        return view('admin_page2.short_codes', compact('error_msg', 'short_codes_count'));
    }

    public function gift_certificates(Request $request)
    {
        if(!$request->session()->get('woh_admin_user'))
        {
            $redirect = action('HomepageController@index');
            return redirect($redirect);
        }
        $entry_code = ShortCodes::where(['type'=>1, 'status'=>0])->count();
        $pin_code = ShortCodes::where(['type'=>2, 'status'=>0])->count();
        $cd_code = ShortCodes::where(['type'=>3, 'status'=>0])->count();
        $bar_code = ShortCodes::where(['type'=>4, 'status'=>0])->count();
        $product_code = ShortCodes::where(['type'=>5, 'status'=>0])->count();
        $short_codes_count = [
            'entry_count' => $entry_code,
            'pin_count' => $pin_code,
            'cd_count' => $cd_code,
            'bar_count' => $bar_code,
            'product_code_count' => $product_code
        ];
        $gc = GiftCertificate::where('status','<>',2)->where('printed',0)->orderBy('woh_gc','desc')->get()->toArray();
        return view('admin_page2.gift_certificate', compact('gc','short_codes_count'));
    }

    public function redeem_gc(Request $request)
    {
        if(!$request->session()->get('woh_admin_user'))
        {
            $redirect = action('HomepageController@index');
            return redirect($redirect);
        }
        return view('admin_page2.redeem_gc');
    }

    public function post_withdrawal_request_update(Request $request)
    {
        if(!$request->session()->get('woh_admin_user'))
        {
            $redirect = action('HomepageController@index');
            return redirect($redirect);
        }
        if($request->action == 'approve')
        {
            $this->validate($request, [
                "transaction_no" => 'required',
                "check_num" => 'required',
                "issuance_date" => 'required'
            ]);// Returns response with validation errors if any, and 422 Status Code (Unprocessable Entity)

            $data = [
                'transaction_no' => $request->transaction_no,
                'check_number' => $request->check_num,
                'issuance_date' => \Carbon\Carbon::parse($request->issuance_date)->format('Y-m-d H:i:s'),
                'status' => 1
            ];

            $member_tran = MemberTransaction::where('woh_member_transaction',$request->woh_member_transaction)->get(['woh_member', 'tran_amount', 'cd_payment'])->toArray();
            $member = new Member;
            $member = $member->where('woh_member',$member_tran[0]['woh_member'])->get()->toArray();
            if($member[0]['status'] == 0)
            {
                $member_credit = MemberCredit::where('woh_member',$member[0]['woh_member'])->get()->toArray();
                if($member_credit[0]['credit_amount'] > 0)
                    \DB::table('woh_member_credit')->where(['woh_member'=>$member[0]['woh_member']])->update(
                        [
                            'credit_amount'=>$member_credit[0]['credit_amount'] - ($member_tran[0]['cd_payment'])
                        ]
                    );

                $member_credit2 = MemberCredit::where('woh_member',$member[0]['woh_member'])->get()->toArray();
                if(!empty($member_credit2) && $member_credit2[0]['credit_amount'] == 0)
                {
                    \DB::table('woh_member_credit')->where('woh_member_credit', $member_credit2[0]['woh_member_credit'])->delete();
                    \DB::table('woh_member')->where('woh_member', $member[0]['woh_member'])->update(['status'=>1,'picture'=> ($member[0]['gender'] == 'male' ? 'online-m.png' : 'online-f.png')]);
                    $tran_data = [
                        "woh_member" => $member[0]['sponsor'],
                        "woh_transaction_type" => 2,
                        "transaction_date" => Carbon::now(),
                        "tran_amount" => 400,
                        "transaction_referred" => $member[0]['woh_member'],
                        'status' => 1
                    ];
                    MemberTransaction::create($tran_data);

                    $pair_dl = new Member;
                    $pair_dl = $pair_dl->where('downline_of',$member[0]['downline_of'])->get()->toArray();
                    if($pair_dl && count($pair_dl) == 2)
                    {
                        $tran_data = [
                            "woh_member" => $member[0]['downline_of'],
                            "woh_transaction_type" => 3,
                            "transaction_date" => Carbon::now(),
                            "tran_amount" => 100,
                            "transaction_referred" => null,
                            'status' => 1
                        ];
                        MemberTransaction::create($tran_data);
                    }

                    \DB::table('woh_gc')->where(['cd_code' => $member[0]['cd_code']])->update(['status'=>1]);
                }
            }
        }
        else
        {
            $this->validate($request, [
                "admin_notes" => 'required'
            ]);// Returns response with validation errors if any, and 422 Status Code (Unprocessable Entity)
            $data = [
                'status' => 3,
                "admin_notes" => $request->admin_notes
            ];
        }
        \DB::table('woh_member_transaction')->where('woh_member_transaction', $request->woh_member_transaction)->update($data);

        return response(['msg' => 'Login Successfull'], 200) // 200 Status Code: Standard response for successful HTTP request
        ->header('Content-Type', 'application/json');
    }

    public function post_gift_certificates(Requests\GiftCertificateRequest $request)
    {
        if(!$request->session()->get('woh_admin_user'))
        {
            $redirect = action('HomepageController@index');
            return redirect($redirect);
        }

        $entry_code = ShortCodes::where(['status'=>0, 'type'=>1])->get()->toArray();
        $pin_code = ShortCodes::where(['status'=>0, 'type'=>2])->get()->toArray();
        $cd_code = ShortCodes::where(['status'=>0, 'type'=>3])->get()->toArray();
        $bar_code = ShortCodes::where(['status'=>0, 'type'=>4])->get()->toArray();
        $product_code = ShortCodes::where(['status'=>0, 'type'=>5])->get()->toArray();

        for($x=1; $x<= $request->gc_number; $x++)
        {
            $status = 0;
            if($request->code == 2)
                $status = 3;
            elseif(isset($request->code) && $request->code == 5)
                $status = 1;
            elseif(!isset($request->code))
                $status = 1;
            $data = [
                'to' => $request->gc_to,
                'gc_name' => $request->gc_name,
                'date_created' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'description' => $request->gc_description,
                'amount' => $request->gc_amount,
                'bar_code' => $bar_code[$x]['code'],
                'pin_code' => ($request->code && $request->code !=5 ? $pin_code[0]['code'] : null),
                'entry_code'=>($request->code == 1 ? $entry_code[0]['code'] : null),
                'cd_code'=>($request->code == 2 ? $cd_code[0]['code'] : null),
                'product_code'=>($request->code == 5 ? $product_code[$x]['code'] : null),
                'status' => $status,
            ];
            GiftCertificate::create($data);

            if($request->code == 1)
                \DB::table('woh_short_codes')->where(['type'=>1, 'code' => $entry_code[0]['code']])->update(['status'=>1]);
            if($request->code == 2)
                \DB::table('woh_short_codes')->where(['type'=>3, 'code' => $cd_code[0]['code']])->update(['status'=>1]);
            \DB::table('woh_short_codes')->where(['type'=>4, 'code' => $bar_code[$x]['code']])->update(['status'=>1]);
        }
        if($request->code ==1 || $request->code ==2)
            \DB::table('woh_short_codes')->where(['type'=>2, 'code' => $pin_code[0]['code']])->update(['status'=>1]);
        $redirect = action('AdminController@gift_certificates');
        return redirect($redirect);
    }

    public function post_redeem_gc(Requests\RedeemGCRequest $request)
    {
        $error_msg = '';
        $success_msg = '';
        $gc = [];
        if(!$request->session()->get('woh_admin_user'))
        {
            $redirect = action('HomepageController@index');
            return redirect($redirect);
        }

        $gc = GiftCertificate::where(['bar_code'=>$request->barcode])->get()->toArray();
        if(empty($gc))
            $error_msg = 'GC not found';
        elseif(!empty($gc) && $gc[0]['status'] == 2)
            $error_msg = 'GC was already redeemed.';
        elseif(!empty($gc) && $gc[0]['status'] == 4)
            $error_msg = 'Can\'t redeemed GC! It belongs to CD Account owner. Please pay credit first to redeem.';
        elseif(!empty($gc) && $gc[0]['status'] == 1)
        {
            \DB::table('woh_gc')->where(['bar_code'=>$request->barcode])->update(['status'=>2]);
            $gc = GiftCertificate::where(['bar_code'=>$request->barcode])->get()->toArray();
            $success_msg = 'GC successfully redeemed.';
        }
        else
            $error_msg = 'GC not registered! Please register to our KLP Marketing Team using this GC. Thank you!';
        return view('admin_page2.redeem_gc', compact('error_msg','success_msg','gc'));
    }

    public function post_print_gc(Request $request)
    {
        if(!$request->session()->get('woh_admin_user'))
        {
            $redirect = action('HomepageController@index');
            return redirect($redirect);
        }
        $woh = explode('-',$request->woh_gc);
        if(!empty($woh))
        {
            foreach($woh as $w)
                \DB::table('woh_gc')->where('woh_gc', $w)->update(['printed'=>1]);
        }
        return response(['msg' => 'Login Successfull'], 200) // 200 Status Code: Standard response for successful HTTP request
        ->header('Content-Type', 'application/json');
    }

    public function klp_members(Request $request)
    {
        if(!$request->session()->get('woh_admin_user'))
        {
            $redirect = action('HomepageController@index');
            return redirect($redirect);
        }
        $klp_member = Member::orderBy('woh_member', 'ASC')->get()->toArray();
        return view('admin_page2.klp_members', compact('member_tran', 'member', 'klp_member'));
    }

    public function klp_members_account(Request $request)
    {
        $level_arr = [];
        if(!$request->session()->get('woh_admin_user'))
        {
            $redirect = action('HomepageController@index');
            return redirect($redirect);
        }
        $member = new Member;
        $member = $member->where('woh_member',$request->member)->get();

        $member_trans = new MemberTransaction;
        $member_tran = $member_trans->join('woh_transaction_type', 'woh_member_transaction.woh_transaction_type', '=', 'woh_transaction_type.woh_transaction_type')
            ->where('woh_member_transaction.woh_member',$request->member)->orderBy('woh_member_transaction','asc')->get()->toArray();

        $member_credit = MemberCredit::where('woh_member',$request->member)->get()->toArray();

        $level_pair = new DownlineLevel;
        $level = $level_pair->where(['parent_member'=>$request->member])->max('level');

        if($level > 0)
        {
            for($x=2; $x<=$level; $x++)
            {
                $total_counts_left = $total_counts_right = 0;
                $level_pair_main_left_sub_left = $level_pair->join('woh_transaction_type', 'woh_downline_level.woh_transaction_type', '=', 'woh_transaction_type.woh_transaction_type')
                    ->where(['parent_member'=>$request->member, 'main_position'=>'left', 'sub_position'=>'left', 'level' => $x])->get()->toArray();
                $level_pair_main_left_sub_right = $level_pair->join('woh_transaction_type', 'woh_downline_level.woh_transaction_type', '=', 'woh_transaction_type.woh_transaction_type')
                    ->where(['parent_member'=>$request->member, 'main_position'=>'left', 'sub_position'=>'right', 'level' => $x])->get()->toArray();

                $level_pair_main_right_sub_left = $level_pair->join('woh_transaction_type', 'woh_downline_level.woh_transaction_type', '=', 'woh_transaction_type.woh_transaction_type')
                    ->where(['parent_member'=>$request->member, 'main_position'=>'right', 'sub_position'=>'left', 'level' => $x])->get()->toArray();
                $level_pair_main_right_sub_right = $level_pair->join('woh_transaction_type', 'woh_downline_level.woh_transaction_type', '=', 'woh_transaction_type.woh_transaction_type')
                    ->where(['parent_member'=>$request->member, 'main_position'=>'right', 'sub_position'=>'right', 'level' => $x])->get()->toArray();

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
                        "transaction_type" => ($x%5==0) ? "GC worth 600 pesos" : "Level Pair",
                        "level" => $x
                    ];
                }
            }
            $level = [];
            $member_maintenance = Unilevel::where('woh_member',$request->member)->where('date_encoded','>=', $member[0]->created_at)->where('date_encoded','<=', date('Y-m-d', strtotime("+1 month", strtotime($member[0]->created_at))))->count();
            $downlines = Member::where('sponsor',$request->member)->get(['woh_member'])->toArray();
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
                for($i=1; $i<= count($level); $i++)
                {
                    if(!empty($level[$i]))
                    {
                        $level_count = Unilevel::whereIn('woh_member',$level[$i])->where('date_encoded','>=', $member[0]->created_at)->where('date_encoded','<=', date('Y-m-d', strtotime("+1 month", strtotime($member[0]->created_at))))->count();
                        $member_tran[] = [
                            "woh_member_transaction" => "----------",
                            "woh_member" => null,
                            "woh_transaction_type" => 8,
                            "transaction_date" => Carbon::now(),
                            "tran_amount" =>  (!$member_maintenance || $member_maintenance && $member_maintenance < 4)? 0 : (($level_count * 500) * ($i<=2 ? .007 : .001)),
                            "transaction_referred" => null,
                            "no_of_pairs" => null,
                            "status" => 1,
                            "transaction_type" => "Unilevel Earnings",
                            "level" => $i
                        ];
                    }
                }
            }
        }
        return view('admin_page2.klp_members_account', compact('member_tran', 'member', 'member_credit'));
    }

    private function generatePin( $number ) {
        // Generate set of alpha characters
        $alpha = array();
        for ($u = 65; $u <= 90; $u++) {
            // Uppercase Char
            array_push($alpha, chr($u));
        }

        // Just in case you need lower case
        // for ($l = 97; $l <= 122; $l++) {
        //    // Lowercase Char
        //    array_push($alpha, chr($l));
        // }

        // Get random alpha character
        $rand_alpha_key = array_rand($alpha);
        $rand_alpha = $alpha[$rand_alpha_key];

        // Add the other missing integers
        $rand = array($rand_alpha);
        for ($c = 0; $c < $number - 1; $c++) {
            array_push($rand, mt_rand(0, 9));
            shuffle($rand);
        }

        return implode('', $rand);
    }

    private function unilevel_dp($woh_member, $level)
    {
        $downlines_level = [];
        if(!empty($woh_member))
        {
            foreach($woh_member as $wm)
            {
                $data = Member::where('sponsor',$wm)->get(['woh_member'])->toArray();
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
