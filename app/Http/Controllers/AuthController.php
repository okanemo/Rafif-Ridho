<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\NAB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
class AuthController extends Controller
{
    public function register(Request $request){
        $fields = $request->validate([
            'name' => 'required|string',
            'u_name' => 'required|string|unique:users,u_name',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed',
            'balance' => 'required|string',
        ]);
        
        $nab = NAB::orderBy('nab_id', 'DESC')->first();
        $updatedUnit = floatval($fields['balance'])/floatval($nab->nab);
        $user = User::create([
            'name' => $fields['name'],
            'u_name' => $fields['u_name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
            'balance' => round(number_format((float)$fields['balance'], 2, '.', ''), 4, PHP_ROUND_HALF_DOWN),
            'unit' => round(number_format((float)$updatedUnit, 4, '.', ''), 4, PHP_ROUND_HALF_DOWN)
        ]);
        // $user->update(array('unit' => $updatedUnit));
        
        $balance_sum = number_format((float)User::sum('balance'), 2,'.', '');
        $unit_sum = number_format((float)User::sum('unit'), 4,'.', '');
        $new_nab_val = ($balance_sum/$unit_sum);
        // var_dump($nab->nab);
        // var_dump($new_nab_val);
        
        if($nab->nab != $new_nab_val){
            $new_nab = NAB::create([
                'nab' => round(number_format((float)$new_nab_val, 4, '.', ''), 4, PHP_ROUND_HALF_DOWN)
            ]);
        }

        $all = User::all();
        $new_nab_val = NAB::orderBy('nab_id', 'DESC')->first();
        foreach($all as $e){
            User::where("user_id", $e['user_id'])->update(['balance' => round($e['unit']*$new_nab_val->nab, 2, PHP_ROUND_HALF_DOWN)]);
        }

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token,
            // 'unit' => $updatedUnit
        ];

        return response($response, 201);
    }

    public function logout(Request $request){
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Logged out'
        ];
    }

    public function login(Request $request){
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

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }
}
