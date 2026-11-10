<?php

namespace Tests\Feature;

use App\Http\Middleware\PreventRequestForgery;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class RequestForgeryProtectionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Exercise the real protection instead of Laravel's unit-test bypass.
        $this->app->bind(PreventRequestForgery::class, fn ($app) => new class($app, $app['encrypter']) extends PreventRequestForgery {
            protected function runningUnitTests()
            {
                return false;
            }
        });

        Route::middleware('web')->post('/_test/csrf', fn () => response()->noContent());
        config(['sanctum.stateful' => ['localhost']]);
    }

    public static function requests(): array
    {
        return [
            'token header without Fetch Metadata' => [['X-CSRF-TOKEN' => 'session-token'], 204],
            'same-origin without token' => [['Sec-Fetch-Site' => 'same-origin'], 204],
            'missing token' => [[], 419],
            'invalid token' => [['X-CSRF-TOKEN' => 'incorrecto'], 419],
            'cross-site without token' => [['Sec-Fetch-Site' => 'cross-site'], 419],
            'same-site without token' => [['Sec-Fetch-Site' => 'same-site'], 419],
            'cross-site with valid token' => [['Sec-Fetch-Site' => 'cross-site', 'X-CSRF-TOKEN' => 'session-token'], 204],
        ];
    }

    #[DataProvider('requests')]
    public function test_web_requests_use_laravel_13_origin_and_token_protection(array $headers, int $status): void
    {
        $this->withSession(['_token' => 'session-token'])
            ->postJson('/_test/csrf', [], $headers)->assertStatus($status);
    }

    public function test_blade_forms_can_still_send_the_hidden_token(): void
    {
        $this->withSession(['_token' => 'session-token'])
            ->post('/_test/csrf', ['_token' => 'session-token'])->assertNoContent();
    }

    public function test_frontend_api_requests_reject_missing_csrf_tokens(): void
    {
        $this->withSession(['_token' => 'session-token'])
            ->postJson('/api/login', [], ['Origin' => 'http://localhost'])->assertStatus(419);
    }

    public function test_frontend_api_requests_accept_valid_csrf_tokens(): void
    {
        $this->withSession(['_token' => 'session-token'])
            ->postJson('/api/login', [], ['Origin' => 'http://localhost', 'X-CSRF-TOKEN' => 'session-token'])
            ->assertUnprocessable()->assertJsonValidationErrors(['email', 'password']);
    }

    public function test_frontend_api_accepts_the_encrypted_xsrf_cookie_used_by_axios(): void
    {
        $response = $this->withSession(['_token' => 'session-token'])
            ->get('/sanctum/csrf-cookie')->assertNoContent()->assertCookie('XSRF-TOKEN');
        $token = $response->getCookie('XSRF-TOKEN', false)->getValue();

        $this->postJson('/api/login', [], ['Origin' => 'http://localhost', 'X-XSRF-TOKEN' => $token])
            ->assertUnprocessable()->assertJsonValidationErrors(['email', 'password']);
    }
}
