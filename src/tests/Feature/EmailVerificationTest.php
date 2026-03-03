<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\URL;


class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_会員登録後に認証メールが送信される()
    {
        Notification::fake();

        $response = $this->post(route('register.store'), [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $user = User::where('email', 'test@example.com')->first();

        Notification::assertSentTo(
            $user,
            VerifyEmail::class
        );
    }
    public function test_認証リンクを踏むとメール認証が完了する()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $this->actingAs($user);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $user->id,
                'hash' => sha1($user->email),
            ]
        );

        $this->get($verificationUrl);

        $user->refresh();

        $this->assertNotNull($user->email_verified_at);
    }

    public function test_メール認証完了後プロフィール画面に遷移する()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $this->actingAs($user);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $user->id,
                'hash' => sha1($user->email),
            ]
        );

        $response = $this->get($verificationUrl);

        $response->assertRedirect(route('mypage.profile'));
    }
}
