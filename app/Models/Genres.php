<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Genres extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'genres';
    protected $fillable = ['name'];

    public function list_movie()
    {
        return $this->hasMany(Movies::class,'genre_id');
    }
}