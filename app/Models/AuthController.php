<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Model
{
    use HasFactory;

    public function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email',$request->email)->first();

        if(!$user){
            ValidationException::withMessages(['email' => 'email is not correct']);
        }

        if(!Hash::check($request->password, $user->password)){
            ValidationException::withMessages(['password' => 'password is not correct']);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'id' => $user->id
        ]);
    }
    public function logout(Request $request){
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged Out Successfully!'
        ]);
    }
}
