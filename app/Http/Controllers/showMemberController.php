<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use App\Models\User;
use App\Models\NAB;

class showMemberController extends Controller
{
    public function index(){
        // $all = User::all();
        // $new_nab_val = NAB::orderBy('nab_id', 'DESC')->first();
        // foreach($all as $e){
        //     User::where("user_id", $e['user_id'])->update(['balance' => round($e['unit']*$new_nab_val->nab, 4, PHP_ROUND_HALF_DOWN)]);
        // }
        
       $user =  User::all();
       $nab = NAB::orderBy('nab_id', 'DESC')->first()->nab;
       $arrUser = array();
       $totalAmount = User::sum('balance');
       foreach($user as $user){
        $data = User::where("user_id", $user['user_id'])->select('user_id', 'unit', 'balance')->get();
        $data->put('nab', $nab)->put('total_amount', $totalAmount);
        array_push($arrUser, $data);
       }
        return response($arrUser, 200);
    }
}
