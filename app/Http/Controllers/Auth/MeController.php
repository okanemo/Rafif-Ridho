<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\NAB;
use Illuminate\Support\Collection;
use App\Http\Resources\UserResource;

class MeController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $nab = NAB::orderBy('nab_id', 'DESC')->first()->nab;
        $totalAmount = User::sum('balance');

        return (new UserResource($request->user()))->additional([
            'nab' => $nab,
            'total_balance' => $totalAmount
        ]);
    }
}
