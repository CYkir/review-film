<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Casts extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'casts';
    protected $fillable = ['name', 'age', 'bio'];

    public function listMovie()
    {
        return $this->belongsToMany(Movies::class, 'cast_movies', 'cast_id', 'movie_id');
    }

}