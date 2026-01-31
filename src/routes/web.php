<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Mypage\ProfileController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 会員登録
Route::get('/register', [RegisterController::class, 'show'])->name('register.show');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
// メール認証
//Route::get('/email/verify', function () {
//    return view('auth.verify-email');
//})->middleware('auth')->name('verification.notice');

//Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
//    $request->fulfill();
//    return redirect()->route('mypage.profile');
//})->middleware(['auth', 'signed'])->name('verification.verify');

//Route::post('/email/verification-notification', function (Request $request) {
//    $request->user()->sendEmailVerificationNotification();
//    return back()->with('message', '認証メールを再送しました');
//})->middleware(['auth', 'throttle:6,1'])->name('verification.send');
// プロフィール編集（初回・編集どちらも）
Route::middleware(['auth'])->group(function () {
    Route::get('/mypage/profile', function () {
        return view('mypage.profile');
    })->name('mypage.profile');

    Route::post('/mypage/profile', [ProfileController::class, 'update'])
        ->name('mypage.profile.update');
});

// プロフィール完了後のみ入れるページ
Route::middleware(['auth', 'verified', 'profile.completed'])->group(function () {
    Route::get('/mypage', function () {
        return view('mypage');
    })->name('mypage');
});


Route::get('/login', [LoginController::class, 'show'])->name('login.show');
Route::post('/login', [LoginController::class, 'login'])->name('login');