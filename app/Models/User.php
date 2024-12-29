<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids;
    

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    public static function boot(){
        parent::boot();
        
        static::created(function($model){
            $model->generate_otp();
        });
    }
    
    public function generate_otp(){
        do{
            $randomNumber = mt_rand(100000, 999999);
            $check = OtpCode::where('otp', $randomNumber)->first();
        }while($check);

        $now = Carbon::now();
        $otp_code = OtpCode::updateOrCreate(
            ['user_id' => $this->id],
            [
                'otp' => $randomNumber,
                'valid_until' => $now -> addMinutes(5),
            ]
        );
    
    }
    
    protected $hidden = [
        'password', 
        'remember_token',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function role()
    {
        return $this->belongsTo(Roles::class,'role_id');
    }

    public function otpdata(){
        return $this->hasOne(OtpCode::class, 'user_id');
    }

}

    