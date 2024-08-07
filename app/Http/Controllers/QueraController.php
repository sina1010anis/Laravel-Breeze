<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QueraController extends Controller
{
    public function S1_1 ()
    {

        function getTranslation($translations, $lang, $statement)
        {

            return $translations[$lang][$statement] ?? $statement;
        }

        $translations = [
            'fa' => [
                'Hello!' => 'درود!',
                'Hi!' => 'درود!'
            ],
            'fr' => [
                'Hello!' => 'Bonjour!'
            ]
        ];

        dd(getTranslation($translations, 'fa', 'Hello!'), getTranslation($translations, 'fr', 'Hello!'), getTranslation($translations, 'fr', 'Something'));

    }
}
