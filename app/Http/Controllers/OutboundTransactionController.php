<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Wallet;
use App\Models\OutboundTransaction;

class OutboundTransactionController extends Controller
{
    public function send(Request $request){
        try{
            return DB::transaction(function () use ($request) {
                // dd(auth()->user()->id);
                $send_user_id=auth()->user()->id;
                $request->validate([
                    'outbound_amount' =>'required|numeric|min:1000',
                    'receive_user_id' => 'required|integer'
                ]);
                
                $outboundTransaction = OutboundTransaction::create([
                    'outbound_amount' =>$request->outbound_amount,
                    'receive_user_id' =>$request->receive_user_id,
                    'send_user_id' => $send_user_id,
                ]);

                $send_wallet = Wallet::where('id_user', $send_user_id)->first();
                $receive_wallet = Wallet::where('id_user', $request->receive_user_id)->first();

                // dd($send_wallet);

                if($send_wallet == null || $send_wallet->current_amount<=$request->outbound_amount){
                    return response()->json([
                        'error'=>'Saldo Insuficiente'
                    ],404);
                }elseif(!$receive_wallet){
                    $receive_wallet = Wallet::create([
                        'id_user' => $request->receive_user_id,
                        'current_amount' => 0,
                    ]);
                }

                $receive_wallet->current_amount += $request->outbound_amount;
                $receive_wallet->save();
                
                $send_wallet->current_amount -= $request->outbound_amount;
                $send_wallet->save();

                return response()->json([
                    'message'=>"Envío exitoso. Tu nuevo saldo es: ".$send_wallet->current_amount
                ], 201);              
            },5);
        }catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }

    }
}
