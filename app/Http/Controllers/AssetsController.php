<?php

namespace App\Http\Controllers;

use App\Models\Assets;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AssetsController extends Controller
{
    public function get_assets()
    {
        $data = Assets::select('*')->first();
        return response()->json($data, 200);
    }

    public function update_asset(Request $request)
    {
        if (request()->hasFile('file')) {
            $file = request()->file('file');
            $file_type = $file->extension();
            if ($file_type == 'png' || $file_type == 'jpg' || $file_type == 'jpeg' || $file_type == 'svg') {
                $time = date('dmy_his');
                $file_path = 'images';
                $header = strtolower($request->input('head'));
                $file_name = $header.'_'.$time.'.'.$file_type;

                $file->move($file_path, $file_name);

                $old_file = Assets::select($header)->first();
                $old_file = $old_file->$header;

                Assets::where('id', 1)->update([$header => $file_name]);
                File::delete('images/'.$old_file);
                return response()->json('File berhasil diupload.', 200);
            } else {
                return response()->json('Jenis file tidak didukung.', 400);
            }
        } else {
            return response()->json("Tidak ada file yang diupload.", 400);
        }
            
    }
}
