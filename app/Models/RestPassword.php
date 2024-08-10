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

        RestPassword::updateOrCreate(['mobile' => $mobile], ['code_digit' => $code, 'time_exp' => time() + 120]);

    }

    public static function getMobileInUser(string $mobile) : RestPassword
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
}
