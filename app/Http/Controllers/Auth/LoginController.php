<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;
use App\Http\Resources\UserResource;
// use App\Models\NAB;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $fields = $request->validate([
            'u_name' => 'required|string',
            'password' => 'required|string'
        ]);

        // Check username
        $user = User::where('u_name', $fields['u_name'])->first();

        // Check password

        if(!$user || !Hash::check($fields['password'], $user->password)){
            return response([
                'message' => "the password isn't matched with any of our records"
            ], 401);
        }

        $user->tokens()->delete();
        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => new UserResource($user),
            'token' => $token
        ];

        return response($response, 201);
    }
}
