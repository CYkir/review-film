<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Movies extends Model
{
    use HasFactory, HasUuids;
    
    protected $table = 'movies';
    protected $fillable = ['title', 'summary', 'poster', 'genre_id', 'year'];
    
    
    public function genres()
    {
        return $this->belongsTo(Genres::class,'genre_id');
    }
    
    public function listCast()
    {
        return $this->belongsToMany(Casts::class, 'cast_movies', 'movie_id', 'cast_id');
    }

    
    
    public function listReview()
    {
        return $this->hasMany(Reviews::class,'movie_id');
    }
    
}