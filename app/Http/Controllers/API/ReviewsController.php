<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reviews;

class ReviewsController extends Controller
{
    public function storeupdate(Request $request){
        $user = auth()->user();
        $request->validate([
            'critic' => 'required',
            'rating' => 'required|integer',
            'movie_id' => 'required',
        ], [
            'required' => 'inputan :attribute harus di isi',
            'integer' => 'inputan :attribute harus berupa angka',
        ]);
        
        $review = Reviews::updateOrCreate(
        ['user_id' => $user->id],
        [
            'user_id' => $user->id,
            'critic' => $request->input('critic'),
            'rating' => $request->input('rating'),
            'movie_id' => $request->input('movie_id')
        ]
    );

        return response([
            'message' => 'profile berhasil dibuat/upadte',
        ], 201);

    }
}