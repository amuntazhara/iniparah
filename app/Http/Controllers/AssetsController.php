<?php

namespace App\Http\Controllers;

use App\Models\Assets;
use Illuminate\Http\Request;

class AssetsController extends Controller
{
    public function get_assets()
    {
        $data = Assets::select('*')->first();
        return response()->json($data, 200);
    }

    public function update_asset(Request $request)
    {
        if(request()->hasFile('file'))
            $file = request()->file('file');
            // $file_size = $file->getSize();
            $file_type = $file->extension();
            $file_name = strtolower($request->input('head'));
            $file_path = 'images';
            $file->move($file_path, $file_name.'0.'.$file_type);
            Assets::where('id', 1)->update([$file_name => $file_name.'0.'.$file_type]);
            return response()->json('OK', 200);

        return response()->json("No! It's not a File", 400);
    }
}
