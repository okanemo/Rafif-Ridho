<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\update_balance;
use App\Models\NAB;
use Illuminate\Http\Response;

class updateBalanceController extends Controller
{
    public function topup(Request $request,$id){
        $user = User::find($id);
        $nab = NAB::orderBy('nab_id', 'DESC')->first();
        // var_dump($nab->nab);
        $amount = $request->input('amount');
        $balance = update_balance::create([
            'action' => 'topUp',
            'amount' => $amount,
            'user_id' => $id
        ]);
        // if($request->has('amount')){
        //     echo('berhasil');
        // }
        // else{
        //     echo('gagal');
        // }
        $updatedBalance = $user->balance + $balance->amount;
        $updatedUnit = floatval($updatedBalance)/floatval($nab->nab);
        // var_dump(floatval($nab->nab));
        // var_dump($updatedUnit);
        // var_dump($updatedBalance);

        $user->update(array('unit' => number_format((float)$updatedUnit, 4, '.', ''), 'balance' => number_format((float)$updatedBalance, 2, '.', '')));
        // $user->update(array('unit' => $updatedUnit));
        // var_dump($user->unit);
        // var_dump($user->balance);

        $balance_sum = number_format((float)User::sum('balance'), 2,'.', '');
        $unit_sum = number_format((float)User::sum('unit'), 4,'.', '');
        $new_nab_val = ($balance_sum/$unit_sum);
        // var_dump($nab_sum);

        if($nab->nab != $new_nab_val){
            $new_nab = NAB::create([
                'nab' => number_format((float)$new_nab_val, 4, '.', '')
            ]);
        }

        $all = User::all();
        $new_nab_val = NAB::orderBy('nab_id', 'DESC')->first();
        foreach($all as $e){
            User::where("user_id", $e['user_id'])->update(['balance' => round($e['unit']*$new_nab_val->nab, 4, PHP_ROUND_HALF_DOWN)]);
        }

        $response = [
            'message' => 'Top up balance successfull',
            'current_balance' => 'Rp.'.round($user->balance, 2, PHP_ROUND_HALF_DOWN),
            'current_unit' => round($user->unit, 4, PHP_ROUND_HALF_DOWN),
            'current_nab' => NAB::orderBy('nab_id', 'DESC')->first()->nab
        ];

        return response($response, 200);
    }

    public function withdraw(Request $request,$id){
        $user = User::find($id);
        $amount = $request->input('amount');
        if($user->balance >= $amount){
            $nab = NAB::orderBy('nab_id', 'DESC')->first();
            $balance = update_balance::create([
                'action' => 'withdrawal',
                'amount' => $amount,
                'user_id' => $id
            ]);
            // if($request->has('amount')){
            //     echo('berhasil');
            // }
            // else{
            //     echo('gagal');
            // }
            
            $updatedBalance = $user->balance - $balance->amount;
            $updatedUnit = floatval($updatedBalance)/floatval($nab->nab);
            
            $user->update(array('unit' => $updatedUnit, 'balance' => $updatedBalance));
            
            $balance_sum = number_format((float)User::sum('balance'), 2,'.', '');
            $unit_sum = number_format((float)User::sum('unit'), 4,'.', '');
            $new_nab_val = ($balance_sum/$unit_sum);
            // var_dump($nab_sum);

            if($nab->nab != $new_nab_val){
                $new_nab = NAB::create([
                    'nab' => number_format((float)$new_nab_val, 4, '.', '')
                ]);
            }

            $all = User::all();
            $new_nab_val = NAB::orderBy('nab_id', 'DESC')->first();
            foreach($all as $e){
                User::where("user_id", $e['user_id'])->update(['balance' => round($e['unit']*$new_nab_val->nab, 4, PHP_ROUND_HALF_DOWN)]);
            }
            
            $response = [
                'message' => 'Top up balance successfull',
                'current_balance' => 'Rp.'.round($user->balance, 2, PHP_ROUND_HALF_DOWN),
                'current_unit' => round($user->unit, 4, PHP_ROUND_HALF_DOWN),
                'current_nab' => NAB::orderBy('nab_id', 'DESC')->first()->nab
            ];

            return response($response, 200);
        }
        else{
            $response = [
                'message' => 'Top up balance successfull',
                'current_balance' => 'Rp.'.round($user->balance, 2, PHP_ROUND_HALF_DOWN),
                'current_unit' => round($user->unit, 4, PHP_ROUND_HALF_DOWN),
                'current_nab' => NAB::orderBy('nab_id', 'DESC')->first()->nab
            ];
            return response($response, 400);
        }
    }
}
