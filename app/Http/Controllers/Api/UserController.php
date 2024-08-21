<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApiLoginReuqest;
use App\Http\Requests\ApiRegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Faker\Extension\Extension;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;


class UserController extends Controller
{

    private function throttleKey($email): string
    {

        return Str::transliterate(Str::lower($email).'|'.request()->ip());

    }

    public function creteToken(): string
    {

        return auth()->user()->createToken('myToken')->plainTextToken;

    }

    public function login (ApiLoginReuqest $request)
    {

        if ($this->limitRate($request->email)) {

            if (Auth::attempt($request->only('email', 'password'))) {

                return request()->json(200, [

                    'data' => auth()->user(),

                    'CODE' => '200',

                    'token' => $this->creteToken()

                ]);

            }

            return request()->json(404, [

                'message' => 'Not find user',

                'CODE' => '404'

            ]);

        }

        return request()->json(404, [

            'message' => 'Request Is High',

            'CODE' => '404'

        ]);

    }

    private function limitRate(string $email): bool
    {

        $executed = RateLimiter::attempt(

            $this->throttleKey($email),

            5,

            function() {

                120;

            }

        );

        return $executed;

    }

    public function register (ApiRegisterRequest $request)
    {

        User::create([

            'name' => $request->name,

            'email' => $request->email,

            'mobile' => $request->mobile,

            'password' => Hash::make($request->password)

        ]);

        return request()->json(202, [

            'message' => 'User successfully created',

            'CODE' => 202

        ]);

    }

    public function profile ()
    {

        $user = auth()->user();

        return request()->json(200, [

            'data' => [

                'name' => $user->name,

                'password' => $user->password,

                'email' => $user->email,

                'mobile' => $user->mobile,

                'created_at' => $user->created_at,

            ]

        ]);

    }


    // public function logout ()
    // {

    //     return request()->json(200, [

    //         'message' => 'User is Logout',

    //         // 'body' => Auth::logout(),

    //         'CODE' => 200

    //     ]);

    // }
}
