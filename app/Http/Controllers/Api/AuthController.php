<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Wallet;
use App\Helpers\Response;
use Illuminate\Http\Request;
use App\Helpers\UUIDGenerate;
use App\Http\Requests\StoreUser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public function register(StoreUser $request)
    {
        $user = new User();
        $user->name = request('name');
        $user->email = request('email');
        $user->phone = request('phone');
        $user->password = Hash::make($request->password);
        $user->ip = $request->ip();
        $user->user_agent = $request->server('HTTP_USER_AGENT');
        $user->login_at = date('Y-m-d H:i:s');
        $user->save();

        Wallet::firstOrCreate(
            [
                'user_id' =>  $user->id,
            ],
            [
                'account_number' => UUIDGenerate::account_number(),
                'amount' => 0,
            ]
        );

        $token = $user->createToken('Magic Pay Register')->accessToken;

        $data = new Response('Successfully registered', ['token' => $token]);
        return $data->success();
    }

    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt(['phone' => $request->phone, 'password' => $request->password])) {
            $user = auth()->user();

            Wallet::firstOrCreate(
                [
                    'user_id' =>  $user->id,
                ],
                [
                    'account_number' => UUIDGenerate::account_number(),
                    'amount' => 0,
                ]
            );

            $token = $user->createToken('Magic Pay Login')->accessToken;

            $data1 = new Response('Successfully login', ['token' => $token]);
            return $data1->success();
        }

        $data2 = new Response('Error login', null);
        return $data2->fail();
    }

    public function logout()
    {
        Auth::user()->tokens->each(function ($token, $key) {
            $token->delete();
        });

        $res = new Response('Successfully logout', null);
        return $res->success();
    }
}
