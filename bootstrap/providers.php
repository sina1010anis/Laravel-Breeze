<?php

use App\Providers\EditValidatePasswordRulesProdiver;
use Laravel\Scout\ScoutServiceProvider;

return [
    App\Providers\AppServiceProvider::class,
    EditValidatePasswordRulesProdiver::class,
    ScoutServiceProvider::class,
];
