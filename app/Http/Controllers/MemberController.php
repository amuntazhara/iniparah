<?php

namespace App\Http\Controllers;

use App\Models\Assets;
use App\Models\Prize;
use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index()
    {
        $spinner = Prize::select('hadiah')->get();
        $assets = Assets::select('*')->first();
        return view('memberMain')->with('spinner', $spinner)
                                ->with('assets', $assets);
    }

    public function get_members()
    {
        $list = Member::selectRaw('username, voucher, klaim, proses')
                    ->orderBy('klaim', 'asc')
                    ->orderBy('proses', 'asc')
                    ->orderBy('username', 'asc')
                    ->get();
        return response()->json($list);
    }

    public function find_member()
    {
        $data = json_decode(request()->data);
        $list = Member::selectRaw('username, voucher, klaim, proses')->where('username', 'like', "%$data->username%")->get();
        return response()->json($list);
    }

    public function add_member()
    {
        $data = json_decode(request()->data);
        $username = $data->username;
        $voucher = $data->voucher;
        $data->gift == null ? $gift = 1 : $gift = $data->gift;

        $member = Member::selectRaw('username')->where(['username' => $username])->get();
        if ($member->count() == 0) {
            Member::insert(['username' => $username, 'voucher' => $voucher, 'hadiah' => $gift, 'klaim' => 0, 'proses' => 0]);
            return response()->json('OK', 200);
        } else {
            return response()->json('Error', 400);
        }

    }

    public function verify_voucher()
    {
        $data = json_decode(request()->data);
        $username = $data->username;
        $voucher = $data->voucher;
        $get = Member::where(['username' => $username, 'voucher' => $voucher])->get();
        if ($get->count() != 1) {
            return response()->json('Voucher yang Anda masukkan salah.', 400);
        } else {
            $get = Member::where(['username' => $username, 'voucher' => $voucher])->first();
            if($get->klaim == 1)
                return response()->json('Voucher hanya dapat digunakan satu kali.', 400);

            // session()->put('username', $get->username);
            $user_data = [
                'username' => $get->username,
                'hadiah' => $get->hadiah
            ];
            return response()->json($user_data, 200);
        }
    }

    public function update_member()
    {
        $data = json_decode(request()->data);
        Member::where('username', $data)->update(['klaim' => 1]);
        return response()->json('Data di-update', 200);
    }

    public function check_claim()
    {
        $data = json_decode(request()->data);
        $check = Member::where('username', $data->username)->first();
        $claim = $check->klaim;
        return response()->json($claim, 200);
    }

    public function confirm_process()
    {
        $data = json_decode(request()->data);
        $member = Member::where('username', $data->username)->first();
        
        if ($member->klaim == 0)
            return response()->json('Error', 400);
        else
            Member::where('username', $data->username)->update(['proses' => 1]);
            return response()->json('OK', 200);
    }
}
