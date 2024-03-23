<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $field = $request->validate([
            'lastname' => 'required|string',
            'firstname' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed',
            'address' => 'required|string',
            'roles' => 'required|json',
            'avatar' => 'required|image',
        ]);

        $filename = '';
        if ($request->hasFile('avatar')){
            $uploadedFile = $request->file('avatar');
            $filename = uniqid() . '.' . $uploadedFile->getClientOriginalExtension();
            $uploadedFile->move(public_path('avatar'), $filename);
        }

        $user = User::create([
            'lastname' => $field['lastname'],
            'firstname' => $field['firstname'],
            'phone' => $field['phone'],
            'email' => $field['email'],
            'password' => bcrypt($field['password']),
            'address' => $field['address'],
            'roles' => $field['roles'],
            'avatar' => $filename,
        ]);

        $token = $user->createToken('mytoken')->plainTextToken;

        $data = [
            'user' => $user,
            'token' => $token
        ];
        return Response()->json($data, 201);
    }

    public function login(Request $request)
    {
        $field = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::with('home')->where('email', $field['email'])->first();

        if (!$user){
            return Response()->json(['message'=> 'email error'],401);
        }

        if(!Hash::check($field['password'], $user->password))
        {
            return Response()->json(['message'=> 'password error'],401);
        }

        $token = $user->createToken('mytoken')->plainTextToken;

        $roles = json_decode($user->roles);
        $user->roles = $roles;
        $data = [
            'user' => $user,
            'token' => $token
        ];
        return Response()->json($data, 200);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return ['message'=>'Logged out'];
    }
}
