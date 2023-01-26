<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// главная
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// полный вывод по объекту
Route::get('/show/{upload}', [App\Http\Controllers\UploadController::class, 'show'])->name('show');
// отдать изображение объекта в браузер
Route::get('/view/{upload}', [App\Http\Controllers\UploadController::class, 'view'])->name('view');
// лайкнуть объект
Route::get('/like/{upload}', [App\Http\Controllers\UploadController::class, 'like'])
    ->middleware('throttle:likes')
    ->name('like');
// скачать объект
Route::get('/download/{upload}', [App\Http\Controllers\UploadController::class, 'download'])
    ->middleware('throttle:downloads')
    ->name('download');

// только авторизованым
Route::middleware('auth')->group(function () {
    // список объектов юзера
    Route::get('/my_uploads', [App\Http\Controllers\HomeController::class, 'my_uploads'])->name('my_uploads');

    // загрузка объекта
    Route::get('/upload', [App\Http\Controllers\UploadController::class, 'form'])->name('upload');
    Route::post('/upload', [App\Http\Controllers\UploadController::class, 'store'])->name('upload');

    // сменить публичный статус объекта
    Route::get('/public/{upload}', [App\Http\Controllers\UploadController::class, 'public'])->name('public');
    // удалить объект
    Route::get('/delete/{upload}', [App\Http\Controllers\UploadController::class, 'delete'])->name('delete');

    // смена пароля
    Route::get('/change_password', [App\Http\Controllers\UserController::class, 'change_password'])->name('change_password');
    Route::post('/change_password', [App\Http\Controllers\UserController::class, 'update_password'])->name('change_password');
});

// регистрация, авторизация, подтверждение мыла, восстановление пароля
Auth::routes(['verify' => true]);
