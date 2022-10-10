<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Wallet;
use App\Models\InboundTransaction;


class InboundTransactionController extends Controller
{
    public function add(Request $request){
        try{
            return DB::transaction(function () use ($request) {
                // dd(auth()->user()->id);
                $id=auth()->user()->id;
                $request->validate([
                    'inbound_amount' =>'required|numeric|min:1000',
                ]);

                $inboundTransaction = InboundTransaction::create([
                    'inbound_amount' =>$request->inbound_amount,
                    'user_id' => $id,
                ]);

                $wallet = Wallet::where('id_user', $id)->first();
                // dd($wallet);

                if(!$wallet){
                    $wallet = Wallet::create([
                        'id_user' => $id,
                        'current_amount' => 0,
                    ]);
                }
                $wallet->current_amount += $request->inbound_amount;
                $wallet->save(); 

                return response()->json([
                    'message'=>"Recarga exitosa. Tu nuevo saldo es: ".$wallet->current_amount
                ], 201);              
            },5);
        }catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }

    }
}
