<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApiLoginReuqest;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Faker\Extension\Extension;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;


class UserController extends Controller
{

    public function throttleKey($email): string
    {

        return Str::transliterate(Str::lower($email).'|'.request()->ip());

    }

    public function login (ApiLoginReuqest $request)
    {

        $executed = RateLimiter::attempt(

            $this->throttleKey($request->email),

            5,

            function() {

                120;

            }

        );

        if ($executed) {

            if (Auth::attempt($request->only('email', 'password'))) {

                $token = auth()->user()->createToken('myToken')->plainTextToken;

                return request()->json(200, [

                    'data' => auth()->user(),

                    'token' => $token

                ]);

            }

            return request()->json(404, 'Error User');

        } else {

            return request()->json(404, 'Request Is High');

        }

    }

    public function register ()
    {

        return 'Register';

    }

    public function profile ()
    {

        try{

            $user = auth()->user();

        } catch(Extension $e) {

            return request()->json(404, 'User Not Find...!');

        }

        return request()->json(200, [
            'data' => [
                'name' => $user->name
            ]
        ]);



    }


    public function logout ()
    {

        return 'Logout';

    }
}
