<?php

namespace App\Http\Controllers;

use App\Models\Prize;
use Illuminate\Http\Request;

class PrizeController extends Controller
{
    public function get_prizes()
    {
        $list = Prize::selectRaw('hadiah, deskripsi')->get();
        return response()->json($list);
    }

    public function update_prizes(Request $request)
    {
        $data = request()->data;
        $length = count($request->input('data'));
        $i = 0;
        while ($i < $length) {
            $prize = $data['prize'.$i];
            Prize::where('id', $i+1)->update(['hadiah' => $prize]);
            $i++;
        }
        return response()->json(200);
    }
}
