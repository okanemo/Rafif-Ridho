<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\NAB;
use Illuminate\Support\Facades\Hash;
// use App\Model\update_balance;

class RegisterController extends Controller
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
}
