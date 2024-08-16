<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestPassword extends Model
{
    use HasFactory;

    protected $fillable = ['mobile', 'code_digit', 'time_exp'];

    public static function createPasswordOrUpdate (string $mobile, string $code) : void
    {

        RestPassword::updateOrCreate(['mobile' => $mobile], ['code_digit' => $code, 'time_exp' => time() + 120, 'token' => NULL]);

    }

    public static function getMobileInUser(string $mobile)
    {

        return RestPassword::whereMobile($mobile)->first();

    }

    public static function expChecker ($time) : bool
    {

        return time() <= $time;

    }

    public static function updateTokenInTableRestPassword(string $mobile, string $token) : void
    {

        RestPassword::whereMobile($mobile)->update(['token' => $token]);

    }

    public static function isHasRestPasswordWhitToken (string $token) : bool
    {

        return RestPassword::whereToken($token)->exists();

    }

    public static function getRestPasswordWhitTokenAndUpdateUser (string $token, string $password)
    {

        if (RestPassword::isHasRestPasswordWhitToken($token)) {

            $data =  RestPassword::whereToken($token)->first();

            User::updatePassword($data->mobile, $password);

        }



    }

    public static function authenticCodeBack (string $mobile) :bool
    {

        if (RestPassword::getMobileInUser($mobile)) {

            return (RestPassword::getMobileInUser($mobile))->time_exp >= time();

        }

        return false;



    }


}
