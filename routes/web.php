<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QueraController;
use App\Http\Controllers\TestController;
use App\Models\Product;
use Database\Factories\ProductFactory;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;

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

require __DIR__.'/auth.php';
