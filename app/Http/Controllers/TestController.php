<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class TestController extends Controller
{
    public function index ($key)
    {

        $executed = RateLimiter::attempt(
            $key,
            5,
            function() {
                20;
            }
        );

        if (! $executed) {

            $_GET['Name'] = 'TEst NAme';

            dd('Error Limit Time', $_GET);

        }

        dd('OK...!');
    }

    public function ValidateTest(Request $request)
    {

        $request->validateWithBag('FormTestBag',[
            'name' => 'required'
        ]);

    }
}
