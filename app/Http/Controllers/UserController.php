<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class UserController extends Controller
{
    public function register(Request $request){
        try{
            $request->validate([
                'name' =>'required|string',
                'email' =>'required|string',
                'password' =>'required|string',
                'nit' => 'required|integer',
                'phone' =>'required|integer'
            ]);

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'nit' => $request->nit,
                'phone' => $request->phone
            ]);

            return response()->json([
               'message' =>'Usuario creado exitosamente'
            ],201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }

    }
}
