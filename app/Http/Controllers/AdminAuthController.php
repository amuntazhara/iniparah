<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function index()
    {
        return view('admin/login');
    }

    public function logging_in()
    {
        $data = json_decode(request()->data);
        if (!Auth::attempt(["name" => $data->username, "password" => $data->password]))
            return response()->json('Username / Password salah.', 400);
        else {
            Auth::user();
            return response()->json('Selamat Datang', 200);
        }
    }
    
    public function logout_panel()
    {
        Auth::logout();
        return redirect('/wheellogin');
    }
}
