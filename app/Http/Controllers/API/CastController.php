<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Casts;

class CastController extends Controller
{
    
    public function __construct()
    {
        $this->middleware(['auth:api', 'IsAdmin'])->except(['index','show']);
    }

    public function index()
    {
        $casts = Casts::get();
        return response([
            'message' => 'Data berhasil ditampilkan',
            'data' => $casts
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3',
            'age' => 'required|integer|min:1', 
            'bio' => 'nullable|string|min:10',
        ], [
            'name.required' => 'Kolom nama harus diisi, tidak boleh kosong.',
            'name.min' => 'Kolom nama harus memiliki minimal :min karakter.',
            'age.required' => 'Kolom usia harus diisi.',
            'age.integer' => 'Kolom usia harus berupa angka.',
            'age.min' => 'Kolom usia minimal adalah :min.',
            'bio.string' => 'Kolom bio harus berupa teks.',
            'bio.min' => 'Kolom bio harus memiliki minimal :min karakter.',
        ]);

        Casts::create([
            'name' => $request -> input('name'),
            'age' => $request -> input('age'),
            'bio' => $request -> input('bio'),
            
        ]);

        return response([
            'message' => 'Data berhasil ditambahkan',
        ],201);

    }
    
    public function show(string $id)
    {
        $casts = Casts::with('listMovie')->find($id);
        
        if(!$casts){
            return response([
                "message" => "Data dengan id: $id tidak ditemukan"
        
        ],404);
        }
        
        return response([
            "message" => "Detail Data berhasil ditampilkan",
            "data" => $casts
        ],201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $casts = Casts::find($id);
        if(!$casts){
            return response([
                "message" => "Data dengan id: $id tidak ditemukan"
        
        ],404);
        }

        $request->validate([
                'name' => 'required|min:3',
                'age' => 'required|integer|min:1', 
                'bio' => 'nullable|string|min:10',
            ], [
                'name.required' => 'Kolom nama harus diisi, tidak boleh kosong.',
                'name.min' => 'Kolom nama harus memiliki minimal :min karakter.',
                'age.required' => 'Kolom usia harus diisi.',
                'age.integer' => 'Kolom usia harus berupa angka.',
                'age.min' => 'Kolom usia minimal adalah :min.',
                'bio.string' => 'Kolom bio harus berupa teks.',
                'bio.min' => 'Kolom bio harus memiliki minimal :min karakter.',
        ]);
        
            $casts->name = $request -> input('name');
            $casts->age = $request -> input('age');
            $casts->bio = $request -> input('bio');
            
            $casts->save();

            return response([
            "message" => " Data dengan id: $id  berhasil diupdate",
            "data" => $casts
        ],201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $casts = Casts::find($id);
        if(!$casts){
            return response([
                "message" => "Data dengan id: $id tidak ditemukan"
        
        ],404);
        }
        $casts->delete();

        return response([
            "message" => " Data dengan id: $id berhasil dihapus",
            "data" => $casts
        ],200);
    }
}