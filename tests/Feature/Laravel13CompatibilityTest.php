<?php

namespace Tests\Feature;

use App\Http\Middleware\PreventRequestForgery;
use App\Models\Ronda\Circuito;
use App\Models\Ronda\Geofence;
use App\Models\User;
use App\Models\Usuario\TipoUsuario;
use App\Models\Varios\Area;
use App\Models\Varios\VariableGlobal;
use App\Notifications\BienvenidoUsuarioNuevo;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;
use Laravel\Sanctum\Sanctum;
use SessionHandlerInterface;
use Tests\TestCase;

class Laravel13CompatibilityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    private function user(): User
    {
        return User::create([
            'nombre' => 'Operador',
            'apellido' => 'Prueba',
            'email' => 'operador@example.test',
            'password' => Hash::make('password-original'),
        ]);
    }

    public function test_laravel_13_preserves_existing_configuration_and_translations(): void
    {
        $this->assertStringStartsWith('13.', app()->version());
        $this->assertSame('php', config('session.serialization'));
        $this->assertFalse(config('cache.serializable_classes'));
        $this->assertSame('America/Argentina/Buenos_Aires', config('app.timezone'));
        $this->assertSame('es', app()->getLocale());
        $this->assertSame('Restablecer contraseña', __('Reset your password'));
        $this->assertContains(PreventRequestForgery::class, app(Kernel::class)->getMiddlewareGroups()['web']);
        $this->assertSame(PreventRequestForgery::class, config('sanctum.middleware.validate_csrf_token'));
        $this->assertFalse(Route::has('register'));
    }

    public function test_guest_views_and_protected_web_routes_remain_available(): void
    {
        foreach (['/', '/login', '/password/reset'] as $url) {
            $this->get($url)->assertOk();
        }

        foreach (['/home', '/ronda', '/user', '/area', '/circuito', '/config', '/export', '/tareas'] as $url) {
            $this->get($url)->assertRedirect(route('login'));
        }
    }

    public function test_existing_php_sessions_restore_authentication_and_validation_errors(): void
    {
        $user = $this->user();
        $loginKey = Auth::guard('web')->getName();
        $errors = (new ViewErrorBag)->put('default', new MessageBag(['nombre' => ['Campo requerido']]));
        $payload = serialize([
            $loginKey => $user->id,
            '_token' => 'previous-session-token',
            'errors' => $errors,
            'success' => 'Ronda creada',
        ]);
        $handler = $this->createMock(SessionHandlerInterface::class);
        $handler->expects($this->once())->method('read')->willReturn($payload);
        $session = new Store(config('session.cookie'), $handler, null, config('session.serialization'));
        $session->start();
        $this->app->instance('session.store', $session);
        Auth::forgetGuards();

        $this->assertAuthenticatedAs($user);
        $this->assertSame('previous-session-token', $session->token());
        $this->assertSame('Campo requerido', $session->get('errors')->first('nombre'));
        $this->assertSame('Ronda creada', $session->get('success'));
    }

    public function test_web_login_validation_session_and_logout(): void
    {
        $user = $this->user();
        $this->from(route('login'))->post(route('login'), [
            'email' => $user->email,
            'password' => 'incorrecta',
        ])->assertRedirect(route('login'))->assertSessionHasErrors('email');
        $this->assertGuest();

        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password-original',
        ])->assertRedirect('/');
        $this->assertAuthenticatedAs($user);

        $this->post(route('logout'))->assertRedirect('/');
        $this->assertGuest();
    }

    public function test_password_reset_and_invitation_links_still_work(): void
    {
        Notification::fake();
        $user = $this->user();
        $this->from(route('password.request'))->post(route('password.email'), [
            'email' => $user->email,
        ])->assertRedirect(route('password.request'))->assertSessionHasNoErrors();

        $notification = Notification::sent($user, ResetPassword::class)->sole();
        $this->assertSame('Restablecer contraseña', $notification->toMail($user)->subject);
        $this->get(route('password.reset', ['token' => $notification->token, 'email' => $user->email]))->assertOk();
        $this->get(route('password.define', ['token' => $notification->token, 'email' => $user->email]))->assertOk();
        $this->post(route('password.update'), [
            'token' => $notification->token,
            'email' => $user->email,
            'password' => 'password-nueva',
            'password_confirmation' => 'password-nueva',
        ])->assertRedirect('/')->assertSessionHasNoErrors();
        $this->assertTrue(Hash::check('password-nueva', $user->fresh()->password));
    }

    public function test_sanctum_token_login_authenticated_requests_and_revocation(): void
    {
        $user = $this->user();
        $this->getJson('/api/user')->assertUnauthorized();
        $this->postJson('/api/login', [])->assertUnprocessable()->assertJsonValidationErrors(['email', 'password']);
        $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'incorrecta',
        ])->assertStatus(400);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password-original',
        ])->assertOk()->assertJsonPath('user.id', $user->id)->assertJsonMissingPath('user.password');
        $headers = ['Authorization' => 'Bearer '.$response->json('token')];
        Auth::forgetGuards();
        $this->getJson('/api/user', $headers)->assertOk()->assertJsonPath('id', $user->id);
        $this->postJson('/api/ping', [], $headers)->assertOk()->assertJsonPath('user.id', $user->id);
        $this->postJson('/api/logout', [], $headers)->assertOk();
        $this->assertDatabaseCount('personal_access_tokens', 0);

        Auth::forgetGuards();
        $this->getJson('/api/user', $headers)->assertUnauthorized();
    }

    public function test_sanctum_still_accepts_frontend_session_authentication(): void
    {
        config(['sanctum.stateful' => ['localhost']]);
        $user = $this->user();
        $this->actingAs($user)->getJson('/api/user', ['Origin' => 'http://localhost'])
            ->assertOk()->assertJsonPath('id', $user->id);
        $this->get('/sanctum/csrf-cookie')->assertNoContent()->assertCookie('XSRF-TOKEN');
    }

    public function test_existing_blade_modules_render_for_an_administrator(): void
    {
        $user = $this->user();
        $role = TipoUsuario::create(['nombre' => 'ADM_SIS', 'descripcion' => 'Administrador']);
        $user->tipos_usuario()->attach($role);
        foreach (['AVATAR', 'URL_TILES', 'API_TOKEN_MAPS'] as $key) {
            VariableGlobal::create(['clave' => $key, 'valor' => '', 'descripcion' => $key]);
        }

        $this->actingAs($user);
        foreach (['/', '/home', '/user', '/user/create', '/user/profile/show', '/area', '/circuito', '/config', '/export', '/tareas'] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_area_circuit_and_geofence_mutations_keep_route_bindings_and_flash_messages(): void
    {
        $user = $this->user();
        $this->actingAs($user)->from(route('area.index'))
            ->post(route('area.store'), ['nombre' => 'Sector norte'])
            ->assertRedirect(route('area.index'))->assertSessionHas('alert.config');
        $area = Area::sole();
        $user->areas()->attach($area, ['es_jefe' => true]);
        $this->post(route('circuito.store'), ['area_id' => $area->id])->assertRedirect();
        $circuito = Circuito::sole();
        $this->get(route('circuito.show', $circuito))->assertOk()->assertSee('Sector norte');
        $this->patch(route('circuito.update', $circuito), ['titulo' => 'Recorrido nuevo'])->assertRedirect();
        $this->assertSame('Recorrido nuevo', $circuito->fresh()->titulo);

        $this->post(route('geofence.store', $circuito), [
            'latitud' => '-42.7', 'longitud' => '-65', 'radio' => 25,
        ])->assertRedirect(route('circuito.show', $circuito));
        $geofence = Geofence::sole();
        $this->delete(route('geofence.destroy', ['circuito' => $circuito, 'geofence' => $geofence]))->assertRedirect();
        $this->assertDatabaseCount('geofence', 0);
        $this->delete(route('circuito.destroy', $circuito))->assertRedirect();
        $this->assertSoftDeleted($circuito);
    }

    public function test_user_management_profile_and_notifications(): void
    {
        Notification::fake();
        $admin = $this->user();
        $area = Area::create(['nombre' => 'Sector']);
        $role = TipoUsuario::create(['nombre' => 'USR', 'descripcion' => 'Operador']);
        $this->actingAs($admin)->post(route('user.store'), [
            'nombre' => 'Nuevo',
            'apellido' => 'Usuario',
            'email' => 'nuevo@example.test',
            'rol' => $role->id,
            'area' => [$area->id],
        ])->assertRedirect(route('user.index'))->assertSessionHasNoErrors();
        $created = User::where('email', 'nuevo@example.test')->sole();
        Notification::assertSentTo($created, BienvenidoUsuarioNuevo::class);
        $this->assertTrue($created->areas->contains($area));
        $this->assertTrue($created->tipos_usuario->contains($role));

        $this->patch(route('user.profile.update'), [
            'nombre' => 'Administrador',
            'email' => $admin->email,
        ])->assertRedirect()->assertSessionHasNoErrors();
        $this->assertSame('Administrador', $admin->fresh()->nombre);
        $this->patch(route('user.profile.password.update'), [
            'password' => 'password-nueva',
            'password_confirmation' => 'password-nueva',
        ])->assertRedirect()->assertSessionHasNoErrors();
        $this->assertTrue(Hash::check('password-nueva', $admin->fresh()->password));
    }

    public function test_tasks_api_still_creates_lists_updates_and_deletes_tasks(): void
    {
        $user = $this->user();
        $area = Area::create(['nombre' => 'Sector']);
        $user->areas()->attach($area);
        Sanctum::actingAs($user);

        $this->postJson(route('api.tarea.store'), [])->assertUnprocessable()->assertJsonValidationErrors('titulo');
        $response = $this->postJson(route('api.tarea.store'), ['titulo' => 'Revisar sector'])->assertCreated();
        $id = $response->json('id');
        $this->getJson(route('api.tarea.index'))->assertOk()->assertJsonPath('0.titulo', 'Revisar sector');
        $this->getJson(route('api.tarea.show', $id))->assertOk()->assertJsonPath('tarea.id', $id);
        $this->patchJson(route('api.tarea.update', $id), ['titulo' => 'Sector revisado', 'finalizada' => true])->assertCreated();
        $this->getJson('/api/tareas/finalizadas')->assertOk()->assertJsonPath('0.titulo', 'Sector revisado');
        $this->deleteJson(route('api.tarea.destroy', $id))->assertOk();
        $this->assertDatabaseCount('tarea', 0);
        $this->assertDatabaseCount('tarea_participante', 0);
    }
}
