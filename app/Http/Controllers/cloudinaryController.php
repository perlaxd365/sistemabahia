<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;

class cloudinaryController extends Controller
{
    //
    public function upload(Request $request)
{
    $request->validate([
        'file' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx',
    ]);

    $uploaded = Cloudinary::upload($request->file('file')->getRealPath(), [
        'folder' => 'mis_archivos'
    ]);

    return response()->json([
        'url' => $uploaded->getSecurePath(),
        'public_id' => $uploaded->getPublicId(),
        
    ]);
}
}
