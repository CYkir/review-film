<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Genres;

class GenreController extends Controller
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
        $genres = Genres::get();
        return response([
            'message' => 'Data berhasil ditambahkan',
            'data' => $genres
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3',

        ], [
            'name.required' => 'Kolom nama harus diisi, tidak boleh kosong.',
            'name.min' => 'Kolom nama harus memiliki minimal :min karakter.',
        ]);

        Genres::create([
            'name' => $request -> input('name'),
            
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
        $genres = Genres::with('list_movie')->find($id);

        if(!$genres){
            return response([
                "message" => "Data dengan id: $id tidak ditemukan"
        
        ],404);
        }
        
        return response([
            "message" => "Detail Data berhasil ditambahkan",
            "data" => $genres
        ],201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $genres = Genres::find($id);
        if(!$genres){
            return response([
                "message" => "Data dengan id :$id tidak ditemukan"
        
        ],404);
        }

        $request->validate([
            'name' => 'required|min:3',

        ], [
            'name.required' => 'Kolom nama harus diisi, tidak boleh kosong.',
            'name.min' => 'Kolom nama harus memiliki minimal :min karakter.',
        ]);
        
            $genres->name = $request -> input('name');
            
            $genres->save();

            return response([
            "message" => " Data dengan id: $id berhasil diupdate",
            "data" => $genres
            ],201);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $genres = Genres::find($id);
        if(!$genres){
            return response([
                "message" => "Data dengan $id tidak ditemukan"
        
        ],404);
        }
        $genres->delete();

        return response([
            "message" => " Data dengan $id berhasil dihapus",
            "data" => $genres
        ],200);
    }
}