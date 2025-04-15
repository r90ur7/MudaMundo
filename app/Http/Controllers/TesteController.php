<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TesteController extends Controller
{
    public function teste()
    {
        // dd($request->all());
        return view('teste');
    }


    public function submit(Request $request)
    {

        // dd(base64_encode($request->file('file')->getContent()));

        // $request->file('file')->storePublicly('uploads/plantas', [
        //     'disk' => 'public',
        //     'visibility' => 'public'
        // ]);


        $filePath = Storage::disk('public')->put('uploads/plantas2', $request->file('file'));

        dd([
            'message' => 'salvo com sucesso',
            'url' => 'storage/' . $filePath,
        ]);
        // dd([
        //     'message' => 'salvo com sucesso',
        //     'url' => config('app.url') . '/storage/' . $filePath,
        // ]);
    }
}
