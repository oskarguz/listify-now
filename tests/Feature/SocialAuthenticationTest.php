<?php


use App\Enum\SocialAuthenticationDriver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Tests\TestCase;

class SocialAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function authenticatorsProvider(): iterable
    {
        return [
            'google' => [SocialAuthenticationDriver::Google->value],
            'discord' => [SocialAuthenticationDriver::Discord->value],
            'twitter' => [SocialAuthenticationDriver::Twitter->value],
        ];
    }

    /**
     * @dataProvider authenticatorsProvider
     */
    public function test_redirect_page(string $authenticator): void
    {
        $response = $this->get("/auth?driver=$authenticator");

        $response->assertRedirect();
    }

    public function test_redirect_page_with_wrong_authenticator(): void
    {
        $response = $this->get('/auth?driver=lorem-ipsum');

        $response->assertStatus(404);
    }

    public function test_auth_callback_for_new_user(): void
    {
        $u = \App\Models\User::factory()->make();

        Socialite::shouldReceive('driver->user')->andReturnUsing(function () use ($u) {
            $user = new \Laravel\Socialite\Two\User();
            $user->name = $u->name;
            $user->email = $u->email;
            $user->avatar = $u->avatar;
            $user->id = $u->id;
            $user->nickname = $u->username;
            return $user;
        });

        $response = $this->get('/auth/callback?driver=discord');
        $response->assertRedirect('/dashboard');

        $this->assertAuthenticated();
    }

    public function test_auth_callback_for_existing_user(): void
    {
        $u = \App\Models\User::factory()->create();

        Socialite::shouldReceive('driver->user')->andReturnUsing(function () use ($u) {
            $user = new \Laravel\Socialite\Two\User();
            $user->name = 'ipsum-lorem';
            $user->email = $u->email;
            $user->avatar = $u->avatar;
            $user->id = $u->id;
            $user->nickname = $u->username;
            return $user;
        });

        $this->assertDatabaseHas('users', [
            'id' => $u->id,
            'name' => $u->name,
        ]);

        $response = $this->get('/auth/callback?driver=discord');
        $response->assertRedirect('/dashboard');

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'id' => $u->id,
            'name' => 'ipsum-lorem'
        ]);
        $this->assertDatabaseCount('users', 1);
    }

    public function test_access_to_login_page_when_authenticated(): void
    {
        $user = \App\Models\User::factory()->create();

        $this->actingAs($user);

        $response = $this->get('/login');
        $response->assertRedirect('/dashboard');
    }

    public function test_access_to_auth_url_when_authenticated(): void
    {
        $user = \App\Models\User::factory()->create();

        $this->actingAs($user);

        $response = $this->get('/auth');
        $response->assertRedirect('/dashboard');
    }

    public function test_access_to_callback_url_when_authenticated(): void
    {
        $user = \App\Models\User::factory()->create();

        $this->actingAs($user);

        $this->assertAuthenticatedAs($user);

        $response = $this->get('/auth/callback');
        $response->assertRedirect('/dashboard');
    }

    public function test_logout(): void
    {
        $user = \App\Models\User::factory()->create();

        $this->actingAs($user);

        $this->assertAuthenticatedAs($user);

        $response = $this->get('/logout');
        $response->isRedirect();

        $this->assertGuest();
    }
}
