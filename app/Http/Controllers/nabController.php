<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\NAB;
use App\Models\User;
use App\Models\update_balance;

class nabController extends Controller
{
    public function index(){
        $request = NAB::all();
        $arr = array();
        foreach($request as $r){
            $e = NAB::where('nab_id', $r['nab_id'])->select('nab_id', 'nab', 'created_at as date')->get();
            array_push($arr, $e);
        }

        return response($arr, 200);
    }

    public function update(Request $request){
        $amount = $request->input('balance');
        $balance_sum = number_format((float)(User::sum('balance')+$amount), 2,'.', '');
        $unit_sum = number_format((float)User::sum('unit'), 4,'.', '');
        $new_nab_val = ($balance_sum/$unit_sum);

        $nab = NAB::orderBy('nab_id', 'DESC')->first();
        if($nab->nab != $new_nab_val){
            $new_nab = NAB::create([
                'nab' => round(number_format((float)$new_nab_val, 4, '.', ''), 4, PHP_ROUND_HALF_DOWN)
            ]);
        }

        $nab = NAB::orderBy('nab_id', 'DESC')->first();

        return response($nab, 200);
        
    }

    public function setNABVal(){
        $userCount = User::count();

        if($userCount == 0) {
            $new_nab = NAB::create([
                'nab' => round(number_format((float)1.0000, 4, '.', ''), 4, PHP_ROUND_HALF_DOWN)
            ]);
            
            $response = [
                'message' => 'No User Detected'
            ];
        }else{
            $response = [
                'message' => 'User Detected'
            ];
        }


        return response($response, 201);
    }
}
