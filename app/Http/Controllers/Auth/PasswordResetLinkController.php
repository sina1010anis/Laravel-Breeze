<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RestPasswordValidateRequest;
use App\Models\RestPassword;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{

    public function create(): View
    {

        return view('auth.forgot-password');

    }

    public function create_mobile(): View
    {

        return view('auth.forgot-password-mobile');

    }

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

    private function isHasMobileInUserForRestPassword(string $mobile, string $code)
    {

        if (!User::isHasMobileInUser($mobile)) {

            return redirect()->route('password.request.mobile')->with('error:restPassword', 'Find the desired user in the database....!');

        }

        Log::info($code);

        return view('auth.forgot-password-mobile-code', ['mobile' => $mobile]);

    }

    private function checkerTimeExpInRestPassword(string $time, string $mobile, $token)
    {

        return (!RestPassword::expChecker($time) or !User::isHasMobileInUser($mobile)) ? redirect()->route('password.request.mobile')->with('error:restPassword', 'Time EXP Error....!') : redirect()->route('password.token', ['token' => $token]);

    }

    public function store_mobile_code(RestPasswordValidateRequest $request)
    {

        $user = new User();

        $user->setCode($request->code)->setMobile($request->mobile);

        $data = RestPassword::getMobileInUser($user->getMobile());

        if ($data->code_digit == $request->code) {

            RestPassword::updateTokenInTableRestPassword($user->getMobile(), $user->getToken());

            return $this->checkerTimeExpInRestPassword($data->time_exp, $user->getMobile(), $user->getToken());

        }

        return redirect()->route('password.request.mobile')->with('error:restPassword', 'The code entered is invalid');

    }

    public function store_mobile(RestPasswordValidateRequest $request)//: RedirectResponse
    {


        $user = new User();

        $user->setCode($request->code)->setMobile($request->mobile);

        if (RestPassword::authenticCodeBack($user->getMobile())) {

            return redirect()->route('password.request.mobile')->with('error:restPassword', 'The code has already been sent to you, if you do not receive it, you must wait for 2 minutes.');

        }

        RestPassword::createPasswordOrUpdate($user->getMobile(), $user->getCode());

        return $this->isHasMobileInUserForRestPassword($user->getMobile(), $user->getCode());

    }

    private function returnBack(string $msg) : RedirectResponse
    {

        return redirect()->back()->with('error:restPassword', $msg);

    }

    public function edit_passoerd($token)
    {

        return view('auth.reset-password', ['token'=> $token]);

    }

    public function store_password(Request $request, $token)
    {

        if (RestPassword::isHasRestPasswordWhitToken($request->token)) {

            RestPassword::getRestPasswordWhitTokenAndUpdateUser($request->token, $request->password);

            return redirect()->route('login')->with('ok:restPassword', 'The password change operation was successful.');

        } else {

            return redirect()->route('password.request.mobile')->with('error:restPassword', 'Invalid Token...!');

        }

    }
}
