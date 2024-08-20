<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Repository\TokenRestPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, TokenRestPassword, HasApiTokens;

    public $mobile;
    public $code;

    private $token = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'mobile',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function sumTowNumber (int $n1, int $n2)
    {

        return $n1 + $n2;

    }

    public function sendPasswordResetNotification($token)
    {

        return redirect()->route('password.token', ['token' => $token]);

    }

    public static function isHasMobileInUser (string $mobile) :bool
    {

        return User::whereMobile($mobile)->exists();

    }

    private function generateTokenLink()
    {

        $this->token = Str::replace('/', '***', Hash::make($this->getMobile(). $this->getCode(). time()));

    }


    public function getMobile()
    {
        return $this->mobile;
    }

    public function setMobile($mobile)
    {
        $this->mobile = $mobile;

        return $this;
    }

    // public function getCode()
    // {
    //     return $this->code;
    // }

    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get the value of token
     */
    public function getToken()
    {

        if ($this->token == null) {

            $this->generateTokenLink();

        }

        return $this->token;
    }

    public static function updatePassword(string $mobile, string $password)
    {

        return (!User::isHasMobileInUser($mobile)) ?: User::whereMobile($mobile)->update(['password' => Hash::make($password)]);


    }
}
