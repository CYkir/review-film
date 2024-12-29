<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Profile;

class ProfileController extends Controller
{
    public function storeupdate(Request $request){
        $user = auth()->user();
        $request->validate([
            'age' => 'required|integer',
            'biodata' => 'required',
            'address' => 'required',
        ], [
            'required' => 'inputan :attribute harus di isi',
            'integer' => 'inputan :attribute harus berupa angka',
        ]);
        
        $profile = Profile::updateOrCreate(
        ['user_id' => $user->id],
        [
            'user_id' => $user->id,
            'age' => $request->input('age'),
            'biodata' => $request->input('biodata'),
            'address' => $request->input('address')
        ]
    );

        return response([
            'message' => 'profile berhasil dibuat/upadte',
        ], 201);

    }
}