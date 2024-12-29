<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Roles;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Roles::get();
                return response([
            'message' => 'data user berhasil di tampilkan',
            'data' => $roles,
        ], 200);
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

                Roles::create([
            'name' => $request -> input('name'),
            
        ]);

        return response([
            'message' => 'Data user berhasil ditambahkan',
        ],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $roles = Roles::find($id);

        if(!$roles){
            return response([
                "message" => "nama user dengan id: $id tidak ditemukan"
        
        ],404);
        }
        
        return response([
            "message" => "Detail Data berhasil ditampilkan",
            "data" => $genres
        ],201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $roles = Roles::find($id);
        if(!$roles){
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
        
            $roles->name = $request -> input('name');
            
            $roles->save();

            return response([
            "message" => " Data dengan id: $id berhasil diupdate",
            "data" => $roles
            ],201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $roles = Roles::find($id);
        if(!$roles){
            return response([
                "message" => "Data dengan $id tidak ditemukan"
        
        ],404);
        }
        $roles->delete();

        return response([
            "message" => " Data dengan $id berhasil dihapus",
            "data" => $roles
        ],200);
    }
}