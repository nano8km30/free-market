<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Mypage\ProfileController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\SellController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\LikesController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\StripeWebhookController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

//商品一覧（ログイン後）
Route::get('/', [ItemController::class, 'index'])
    ->name('items.index');

// 商品詳細
Route::get('/item/{item}', [ItemController::class, 'show'])->name('items.show');

// コメント投稿のルート
Route::post('/item/{item}/comments', [CommentsController::class, 'store'])->name('comments.store');

//いいね
Route::post('/items/{item}/toggle-like', [LikesController::class, 'toggleLike'])->name('items.toggle-like');

// 購入画面表示
Route::get('/purchase/{item}', [PurchaseController::class, 'show'])
    ->middleware('auth')
    ->name('purchase.show');

// 購入処理
Route::post('/purchase/{item}', [PurchaseController::class, 'store'])
    ->middleware('auth')
    ->name('purchase.store');

// 決済成功後
Route::get('/purchase/success', [PurchaseController::class, 'paymentSuccess'])
    ->name('purchase.payment.success');

Route::post('/webhook/stripe', [StripeWebhookController::class, 'handle']);

//住所変更
Route::get('/purchase/{item}/address', [PurchaseController::class, 'editAddress'])
    ->name('purchase.address.edit');

Route::post('/purchase/{item}/address', [PurchaseController::class, 'updateAddress'])
    ->name('purchase.address.update');

// 商品出品ページ
Route::middleware('auth')->get('/sell', [SellController::class, 'create'])->name('items.create');
Route::middleware('auth')->post('/sell', [SellController::class, 'store'])->name('items.store');

// マイページ
Route::middleware('auth')->get('/mypage', [ProfileController::class, 'mypage'])->name('mypage');

//ログイン
Route::get('/login', [LoginController::class, 'show'])->name('login.show');
Route::post('/login', [LoginController::class, 'login'])->name('login');

//ログアウト
Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

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
    Route::get('/mypage/profile', [ProfileController::class, 'edit'])
        ->name('mypage.profile');

    Route::post('/mypage/profile', [ProfileController::class, 'update'])
        ->name('mypage.profile.update');
});

// プロフィール完了後のみ入れるページ
Route::middleware(['auth', 'verified', 'profile.completed'])->group(function () {
    Route::get('/mypage', function () {
        return view('mypage');
    })->name('mypage');
});

