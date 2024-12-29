<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cast_movie;

class CastMovieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
        
    public function __construct()
    {
        $this->middleware(['auth:api', 'IsAdmin'])->except(['index','show']);
    }
    
    public function index()
    {
        $castMovie = Cast_movie::get();
        return response([
            'message' => 'Data berhasil ditambahkan',
            'data' => $castMovie
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|min:3',
            'cast_id' => 'required', 
            'movie_id' => 'required',
        ], [
            'nama.required' => 'Kolom nama harus diisi, tidak boleh kosong.',
            'nama.min' => 'Kolom nama harus diisi minimal 3 karakter.',
            'cast_id.required' => 'Kolom cast_id harus diisi, tidak boleh kosong.',
            'movie_id.required' => 'Kolom movie_id harus diisi, tidak boleh kosong,'
            
        ]);

        Cast_movie::create([
            'nama' => $request -> input('nama'),
            'cast_id' => $request -> input('cast_id'),
            'movie_id' => $request -> input('movie_id'),
            
        ]);

        return response([
            'message' => 'Data berhasil ditambahkan',
        ],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $castMovie = Cast_movie::with(['cast','movie'])->find($id);
        if (!$castMovie) {
            return response([
                "message" => "Data dengan id: $id tidak ditemukan"
        
        ],404);
        }
        return response([
            "message" => "Detail Data berhasil ditampilkan",
            "data" => $castMovie
        ],201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $castMovie = Cast_movie::find($id);
        
        if (!$castMovie) {
            return response([
                "message" => "Data dengan id: $id tidak ditemukan"
        
        ],404);
        }

        $request->validate([
            'nama' => 'required|min:3',
            'cast_id' => 'required', 
            'movie_id' => 'required',
        ], [
            'nama.required' => 'Kolom nama harus diisi, tidak boleh kosong.',
            'nama.min' => 'Kolom nama harus diisi minimal 3 karakter.',
            'cast_id.required' => 'Kolom cast_id harus diisi, tidak boleh kosong.',
            'movie_id.required' => 'Kolom movie_id harus diisi, tidak boleh kosong,'
            
        ]);

            $castMovie->nama = $request -> input('nama');
            $castMovie->cast_id = $request -> input('cast_id');
            $castMovie->movie_id = $request -> input('movie_id');

        $castMovie->save();
        return response([
            "message" => "Detail Data berhasil diupdate",
            "data" => $castMovie
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $castMovie = Cast_movie::find($id);
        if (!$castMovie) {
            return response([
                "message" => "Data dengan id: $id tidak ditemukan"
        
        ],404);
        }
        
        $castMovie->delete();
        
        return response([
            "message" => "Data berhasil dihapus",
            "data" => $castMovie
        ],200);
    }
}