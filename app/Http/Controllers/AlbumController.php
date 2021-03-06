<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Image;
use Illuminate\Http\Request;
use App\Http\Resources\AlbumResource;

class AlbumController extends Controller
{
    public function getAlbums()
    {
        return new AlbumResource(Album::with('category')->where('user_id', auth()->user()->id)->paginate(3));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('album.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('album.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:3|max:25',
            'description' => 'required|min:3|max:200',
            'category_id' => 'required|numeric',
            'image' => 'required|mimes:jpeg,jpg,png'
        ]);

        $data = $request->all();
        $data['image'] = $request->image->hashName(); //hashName() slicno kao da koristimo store/storage
        $request->image->move(public_path('album'), $data['image']);

        $album = Album::create($data + ['user_id' => auth()->user()->id]);

        return response()->json([ 'id' => $album->id ]);
    }

    public function update(Album $album, Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:3|max:25',
            'description' => 'required|min:3|max:200',
            'category_id' => 'required|numeric',
            'image' => 'sometimes|mimes:jpeg,jpg,png'
        ]);

        $data = $request->all();
        $data['image'] = $album->image;

        if($request->hasFile('image')){
            $file = $request->file('image');
            $data['image'] = $file->hashName();
            $file->move('./album/', $data['image']);
        }

        $album->update($data);

        if($request->expectsJson()){
            return response()->json($this->getAlbums());
        }
    }

    public function destroy($id)
    {
        $album = Album::find($id);
        unlink(public_path('/album/'.$album->image));
        $album->delete();
        if($album){
            $image = Image::where('album_id', $id)->get();
            foreach($image as $img){
                unlink(public_path('/images/'.$img->image));
            }

            Image::where('album_id', $id)->delete();
            return response()->json($this->getAlbums());
        }
    }

    public function getOneAlbum($id)
    {
        return Album::with('category')->findOrFail($id);
    }
}
