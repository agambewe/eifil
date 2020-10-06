<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{

    /**
    * Handle file upload and return location.
    *
    * @param  Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function upload(Request $request)
    {
        $validatedData = $request->validate([
            'file' => 'required|file',
        ]);
    
        $path = $request->file('file')->store('uploads', 'public');
        return ['location' => Storage::url($path)];
    }    
    
}