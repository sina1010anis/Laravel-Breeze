<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestPassword extends Model
{
    use HasFactory;

    protected $fillable = ['mobile', 'code_digit', 'time_exp'];
}
