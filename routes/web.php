<?php

use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QueraController;
use App\Http\Controllers\TestController;
use App\Models\Product;
use Database\Factories\ProductFactory;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {

    $id = '4';

    $key = 'product:'.$id.':name';

    if (Redis::executeRaw(['EXISTS', $key])) {

        dd('Redis', Redis::executeRaw(['get', $key]));

    } else {

        $data = Product::find($id);

        Redis::executeRaw(['set', $key, $data->name]);

        dd('Mysql', $data->name);

    }

    // return view('welcome');
});

Route::get('/test/{key}', [TestController::class, 'index']);

Route::get('/Quera', [QueraController::class, 'S1_1']);

Route::get('/dashboard', function () {

    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/logout', function () {

    return Auth::logout();

});

Route::get('forgot-password-mobile', [PasswordResetLinkController::class, 'create_mobile'])->name('password.request.mobile');

Route::post('forgot-password-mobile', [PasswordResetLinkController::class, 'create_mobile'])->name('password.request.mobile');

Route::post('forgot-password-mobile-store', [PasswordResetLinkController::class, 'store_mobile'])->name('password.mobile');

Route::post('forgot-password-mobile-code', [PasswordResetLinkController::class, 'store_mobile_code'])->name('password.code');

Route::get('/rest/password/mobile/{token}', [PasswordResetLinkController::class, 'edit_passoerd'])->name('password.token');

Route::post('/rest/password/mobile/{token}', [PasswordResetLinkController::class, 'store_password'])->name('password.token');

Route::view('/test/page/validate', 'test-validate');

Route::post('/test/page/validate', [TestController::class, 'ValidateTest'])->name('validate-test');

require __DIR__.'/auth.php';

Route::get('/vue-page', function () {

    return Inertia::render('Welcome');

});

Route::get('/vue-page-2', function () {

    return Inertia::render('Welcome_2', ['username'=>'sina1010']);

});
