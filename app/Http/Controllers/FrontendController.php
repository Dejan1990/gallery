<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Album;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function index(Request $request)
    {
    	$albums = Album::latest()->paginate(50);
    	return view('home', compact('albums'));
    }

    public function userAlbum($id)
    {
    	$albums = Album::where('user_id', $id)->get();
        //$userId = $id;
        $follows = (new User)->amIfollowing($id);
        return view('user-album', compact('albums', 'id', 'follows'));
    }
}
