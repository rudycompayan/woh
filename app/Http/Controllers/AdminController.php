<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\MemberTransaction;
use App\Models\ShortCodes;
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
            ->where(['woh_member_transaction.woh_transaction_type'=>1, 'status'=>2])->orderBy('woh_member_transaction','desc')->get()->toArray();
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
        return view('admin_page2.gift_certificate', compact('short_codes_count'));
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
        }
        else
        {
            $data = [
                'status' => 3
            ];
        }
        \DB::table('woh_member_transaction')->where('woh_member_transaction', $request->woh_member_transaction)->update($data);

        return response(['msg' => 'Login Successfull'], 200) // 200 Status Code: Standard response for successful HTTP request
        ->header('Content-Type', 'application/json');
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
