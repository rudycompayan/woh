<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\GiftCertificate;
use App\Models\Member;
use App\Models\MemberCredit;
use App\Models\MemberTransaction;
use App\Models\ShortCodes;
use Carbon\Carbon;
use Faker\Provider\Barcode;
use Illuminate\Http\Request;

use App\Http\Requests;
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
                'first_name','last_name', 'woh_member_transaction', 'tran_amount', 'tax', 'transaction_date', 'cd_payment', 'woh_member_transaction.status AS w_status', 'notes', 'woh_member.status AS m_status'
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
        $short_codes_count = [
            'entry_count' => $entry_code,
            'pin_count' => $pin_code,
            'cd_count' => $cd_code,
            'bar_count' => $bar_code
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
                    $tran_data = [
                        "code" => $this->generatePin(10),
                        "type" => $t,
                        'status' => 0
                    ];
                    ShortCodes::create($tran_data);
                }
            }
            $error_msg = 'Codes successfully updated.';
        }
        $entry_code = ShortCodes::where(['type'=>1, 'status'=>0])->count();
        $pin_code = ShortCodes::where(['type'=>2, 'status'=>0])->count();
        $cd_code = ShortCodes::where(['type'=>3, 'status'=>0])->count();
        $bar_code = ShortCodes::where(['type'=>4, 'status'=>0])->count();
        $short_codes_count = [
            'entry_count' => $entry_code,
            'pin_count' => $pin_code,
            'cd_count' => $cd_code,
            'bar_count' => $bar_code
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
        $gc = GiftCertificate::get()->toArray();
        return view('admin_page2.gift_certificate', compact('gc'));
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

            $member_tran = MemberTransaction::where('woh_member_transaction',$request->woh_member_transaction)->get(['woh_member', 'tran_amount'])->toArray();
            $member = new Member;
            $member = $member->where('woh_member',$member_tran[0]['woh_member'])->get()->toArray();
            if($member[0]['status'] == 0)
            {
                $member_credit = MemberCredit::where('woh_member',$member[0]['woh_member'])->get()->toArray();
                if($member_credit[0]['credit_amount'] > 0)
                    \DB::table('woh_member_credit')->where(['woh_member'=>$member[0]['woh_member']])->update(['credit_amount'=>($member_credit[0]['credit_amount'] - ($member_tran[0]['tran_amount']/2))]);

                $member_credit2 = MemberCredit::where('woh_member',$member[0]['woh_member'])->get()->toArray();
                if(!empty($member_credit2) && $member_credit2[0]['credit_amount'] == 0)
                {
                    \DB::table('woh_member_credit')->where('woh_member_credit', $member_credit2[0]['woh_member_credit'])->delete();
                    \DB::table('woh_member')->where('woh_member', $member[0]['woh_member'])->update(['status'=>1,'picture'=>'online.png']);
                    $tran_data = [
                        "woh_member" => $member[0]['sponsor'],
                        "woh_transaction_type" => 2,
                        "transaction_date" => Carbon::now(),
                        "tran_amount" => 200,
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
                            "tran_amount" => 200,
                            "transaction_referred" => null,
                            'status' => 1
                        ];
                        MemberTransaction::create($tran_data);
                    }
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

        for($x=1; $x<= $request->gc_number; $x++)
        {
            $data = [
                'to' => $request->gc_to,
                'gc_name' => $request->gc_name,
                'date_created' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'description' => $request->gc_description,
                'amount' => $request->gc_amount,
                'bar_code' => $bar_code[$x]['code'],
                'pin_code' => ($request->code ? $pin_code[0]['code'] : null),
                'entry_code'=>($request->code == 1 ? $entry_code[0]['code'] : null),
                'cd_code'=>($request->code == 2 ? $cd_code[0]['code'] : null),
                'status' => ($request->code == 2 ? 2 : 0),
            ];
            GiftCertificate::create($data);

            if($request->code == 1)
                \DB::table('woh_short_codes')->where(['type'=>1, 'code' => $entry_code[0]['code']])->update(['status'=>1]);
            if($request->code == 2)
                \DB::table('woh_short_codes')->where(['type'=>3, 'code' => $cd_code[0]['code']])->update(['status'=>1]);
            if($request->code)
                \DB::table('woh_short_codes')->where(['type'=>2, 'code' => $pin_code[$x]['code']])->update(['status'=>1]);
            \DB::table('woh_short_codes')->where(['type'=>4, 'code' => $bar_code[0]['code']])->update(['status'=>1]);
        }
        $redirect = action('AdminController@gift_certificates');
        return redirect($redirect);
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
}
