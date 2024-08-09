<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\RestPassword;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    public function create_mobile(): View
    {



        return view('auth.forgot-password-mobile', ['code' => false]);
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {

        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withInput($request->only('email'))
                            ->withErrors(['email' => __($status)]);
    }

    public function store_mobile(Request $request)//: RedirectResponse
    {

        if ($request->has('code')) {

            $request->validate([

                'mobile' => ['required'],

                'code' => ['min:4', 'max:4'],

            ]);

            if (User::whereMobile($request->mobile)->exists()) {

                $data = RestPassword::whereMobile($request->mobile)->first();

                if (time() <= $data->time_exp) {

                    if ($request->code == $data->code_digit) {

                        $token = Hash::make($request->mobile. $request->code. time());

                        $token = Str::replace('/', '***', $token);

                        RestPassword::whereMobile($request->mobile)->update(['token' => $token]);

                        $data2 = RestPassword::whereMobile($request->mobile)->first();

                        return redirect()->route('password.token', ['token'=> $token]);

                    }

                    dd('Error Code Digit');

                }

                dd('Time EXP');

            } else {

                dd('Mobile Not Has');

            }

        } else {

            if (User::whereMobile($request->mobile)->exists()) {

                $code = rand(1354, 9876);

                Log::info('Code (4 Digit)'.$code);

                RestPassword::updateOrCreate(['mobile' => $request->mobile], ['code_digit' => $code, 'time_exp' => time() + 120]);

                return view('auth.forgot-password-mobile', ['code' => true, 'mobile' => $request->mobile]);

            } else {

                dd('Mobile Not Has');

            }



        }



    }

    public function edit_passoerd($token)
    {

        return view('auth.reset-password', ['token'=> $token]);

    }

    public function store_password(Request $request, $token)
    {

        if ($data = RestPassword::whereToken($token)->first()) {

            User::whereMobile($data->mobile)->update(['password' => Hash::make($request->password)]);

            return redirect()->route('login');

        } else {

            throw new \Exception('Error Token');

        }

    }
}
