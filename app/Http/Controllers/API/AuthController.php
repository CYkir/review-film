<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Roles;
use App\Models\OtpCode;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserRegisterMail; 
use App\Mail\GenerateEmailMail; 
use Carbon\Carbon;


class AuthController extends Controller
{
    public function register(Request $request){
        $request->validate([
            'name' => 'required|min:2',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            
            
        ],[
            'required' => "inputan :attribute harus di isi",
            'min' => "inputan :attribute minimar :min Karakter",
            'email' => "inputan :attribute harus berupa email",
            'unique' => "Email sudah terdaftar. Silakan gunakan email lain.",
            'confirmed' => "konfirmasi password tidak sesuai",
        ]);

        $user = new User();
        $roleUser = Roles::where('name','user')->first();

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->role_id = $roleUser->id;


        $user->save(); 

        Mail::to($user->email)->send(new UserRegisterMail($user));

        return response([
            "message" => "User Berhasil Register, silahkan cek email untuk verifikasi",
            "user" => $user,
        ],200);
    }

    public function login(Request $request) {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
            
            
        ],[
            'required' => "inputan :attribute harus di isi",

        ]);
        
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Invalid user'], 401);
        }

        $user = User::with('role')->where('email',$request->input('email'))->first();

        return response([
            "message" => "User Berhasil Login",
            "user" => $user,
            "token" => $token,
        ],200);

    }

    public function currentuser()
    {
        
        $user = auth()->user();

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        return response([
            "message" => "Profil berhasil ditampilkan",
            "user" => $user,
        ], 200);
    }


    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Logout Berhasil']);
    }


    public function updateUser(Request $request)
    {
        
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'error' => 'User tidak ditemukan'
            ], 401);
        }

        $request->validate([
            'name' => 'required|min:2',
            'email' => 'required|email|unique:users,email,' . $user->id, 
            'password' => 'nullable|min:8|confirmed', 
        ], [
            'required' => "inputan :attribute harus diisi",
            'min' => "inputan :attribute minimum :min karakter",
            'email' => "inputan :attribute harus berupa email yang valid",
            'unique' => "inputan :attribute sudah terdaftar",
            'confirmed' => "konfirmasi password tidak sesuai",
        ]);

        $user->name = $request->input('name');
        $user->email = $request->input('email');

        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        $user->save();

        return response([
            "message" => "User berhasil diupdate",
            "user" => $user,
        ], 200);
    }
    
    public function generateOtp(Request $request){
        $request->validate([
            'email' => 'required|email',
            
        ], [
            'required' => "inputan :attribute harus diisi",
            'email' => "inputan harus berformat email",
        ]);

        $user = User::where('email', $request->input('email'))->first();
        
        $user -> generate_otp();

        Mail::to($user->email)->send(new GenerateEmailMail($user));
        
        return response([
            "message" => "kode otp berhasil di buat, silahkan cek email",
        ]);
    }
    
    public function verifikasi(Request $request){
        $request->validate([
            'otp' => 'required|min:6',
            
        ], [
            'required' => "inputan :attribute harus diisi",
            'min' => "inputan :attribute minimal :min karakter",
        ]);
        $user = auth()->user();
        //jika Otp Code tidak ditemukan
        $otp_code = OtpCode::where('otp', $request -> input('otp'))-> where('user_id', $user -> id) -> first();
        if(!$otp_code){
            return response([
            "message" => "otp tidak ditemukan",
        ], 400);
        }
        
           //Jika valid untid expired
        $now = Carbon::now();
        if($now > $otp_code->valid_until){
            return response([
                "message" => "otp sudah expired",
            ],400);
        }
        
        //update user
        $user = User::find($otp_code -> user_id);
        
        $user -> email_verified_at = $now;
        
        $user -> save();

        $otp_code -> delete();

        return response([
                "message" => "verifikasi berhasil",
            ],200);
    }
}