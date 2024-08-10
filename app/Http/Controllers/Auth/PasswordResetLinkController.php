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

    public function store_mobile_code(RestPasswordValidateRequest $request)
    {

        $user = new User();

        $user->setCode($request->code)->setMobile($request->mobile);

        if (User::isHasMobileInUser($user->mobile)) {

            $data = RestPassword::getMobileInUser($user->getMobile());

            if (RestPassword::expChecker($data->time_exp)) {

                if ($user->getCode() == $data->code_digit) {

                    RestPassword::updateTokenInTableRestPassword($user->getMobile(), $user->getToken());

                    return redirect()->route('password.token', ['token'=> $user->getToken()]);

                } else {

                    return view('auth.forgot-password-mobile-code', ['mobile' => $request->mobile, 'error_restPassword'=> 'Error Code Digit....!']);

                }


            } else {

                return view('auth.forgot-password-mobile-code', ['mobile' => $request->mobile, 'error_restPassword'=> 'Time EXP....!']);

            }

        } else {

            return view('auth.forgot-password-mobile-code', ['mobile' => $request->mobile, 'error_restPassword'=> 'Oh Mobile not Databse....!']);

        }

    }

    public function store_mobile(RestPasswordValidateRequest $request)//: RedirectResponse
    {

        $user = new User();

        $user->setMobile($request->mobile);

        if (User::isHasMobileInUser($user->mobile)) {

            Log::info('Code (4 Digit)'.$user->getCode());

            RestPassword::createPasswordOrUpdate($user->mobile, $user->getCode());

            return view('auth.forgot-password-mobile-code', ['mobile' => $user->mobile]);

        } else {

            return $this->returnBack('Mobile Not Has in databases...!');

        }

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

        if ($data = RestPassword::whereToken($token)->first()) {

            User::whereMobile($data->mobile)->update(['password' => Hash::make($request->password)]);

            return redirect()->route('login');

        } else {

            throw new \Exception('Error Token');

        }

    }
}
