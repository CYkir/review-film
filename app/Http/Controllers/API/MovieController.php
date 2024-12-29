<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Movies;
// use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class MovieController extends Controller
{
    

    public function __construct()
    {
        $this->middleware(['auth:api', 'IsAdmin'])->except(['index','show']);
    }

    public function index()
    {
        $movies = Movies::all();
                return response([
            'message' => 'List movie berhasil di tampilkan',
            'data' => $movies,
        ], 200);


        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'poster' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'title' => 'required',
            'summary' => 'required|string|min:10',
            'genre_id' => 'required |exists:genres,id',
            'year' => 'required',
            
        ], [
            'required' => 'inputan :attribute harus di isi',
            'max' => 'inputan : attribute tidak boleh melebihi :max bite',
            'mimes' => 'inputan :attribute harus berupa file :mimes',
            'image' => 'inputan :attribute harus berupa file gambar',
            'exists' => 'inputan :attribute tidak ada di database ',
            'min' => 'inputan :attribute harus di isi minimal :min karakter',
            'summary' => 'inputan :attribute harus di isi minimal 10 karakter',
        ]);

        $uploadedFileUrl = cloudinary()->upload($request->file('poster')->getRealPath(), [
            'folder' => 'poster_image',
        ])->getSecurePath();

        $movies = new Movies;

        $movies->title = $request->input('title');
        $movies->summary = $request->input('summary');
        $movies->poster = $uploadedFileUrl;
        $movies->genre_id = $request->input('genre_id');
        $movies->year = $request->input('year');
        $movies->save();

        return response([
            'message' => 'berhasil menambahkan movie',
            'data' => $movies,
        ], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $movies = Movies::with(['genres', 'listCast','listReview'])->find($id);

        if(!$movies){
            return response([
                "message" => "Data dengan id: $id tidak ditemukan"
        
            ],404);
        }
        
        return response([
            "message" => "Detail Data berhasil ditampilkan",
            "data" => $movies
        ],201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'poster' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'title' => 'required',
            'summary' => 'required|string|min:10',
            'genre_id' => 'required |exists:genres,id',
            'year' => 'required',
            
        ], [
            'required' => 'inputan :attribute harus di isi',
            'max' => 'inputan : attribute tidak boleh melebihi :max bite',
            'mimes' => 'inputan :attribute harus berupa file :mimes',
            'image' => 'inputan :attribute harus berupa file gambar',
            'exists' => 'inputan :attribute tidak ada di database ',
            'min' => 'inputan :attribute harus di isi minimal :min karakter',
            'summary' => 'inputan :attribute harus di isi minimal 10 karakter',
        ]);
        
        $movies = Movies::find($id);

        if($request->hasFile('poster')){
            
            $uploadedFileUrl = cloudinary()->upload($request->file('poster')->getRealPath(), [
                'folder' => 'poster_image',
            ])->getSecurePath();
            $movies->poster = $uploadedFileUrl;
            
        }
        if(!$movies){
            return response([
                "message" => "Data dengan id: $id tidak ditemukan",
                'data' => $movies,
        
        ],404);
        }

        $movies->title = $request->input('title');
        $movies->summary = $request->input('summary');
        $movies->genre_id = $request->input('genre_id');
        $movies->year = $request->input('year');
        
        $movies->save();

        return response([
            'message' => 'movie berhasil di update',
            'data' => $movies,
        ], 201);


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $movies = Movies::find($id);
        if(!$movies){
            return response([
                "message" => "Data dengan id: $id tidak ditemukan"
        
            ],404);
        }
        
        $movies->delete();

        return response([
            'message' => 'movie berhasil di delete',
            'data' => $movies,
        ], 200); 
        
    }
}