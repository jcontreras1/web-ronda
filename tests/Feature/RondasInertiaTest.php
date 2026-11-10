<?php

namespace Tests\Feature;

use App\Models\Ronda\Checkpoint;
use App\Models\Ronda\Circuito;
use App\Models\Ronda\ImageCheckpoint;
use App\Models\Ronda\Ronda;
use App\Models\User;
use App\Models\Usuario\TipoUsuario;
use App\Models\Varios\Area;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia;
use Intervention\Image\Facades\Image;
use Tests\TestCase;

class RondasInertiaTest extends TestCase
{
    private User $user;
    private Circuito $circuito;

    protected function setUp(): void
    {
        parent::setUp();
        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
            'cache.default' => 'array',
            'session.driver' => 'array',
        ]);
        DB::purge('sqlite');
        $this->withoutVite();
        $manifest = public_path('build/manifest.json');
        $this->withHeaders(['X-Inertia-Version' => is_file($manifest) ? hash_file('xxh128', $manifest) : '']);
        $this->artisan('migrate', ['--database' => 'sqlite', '--path' => [
            'database/migrations/2014_10_12_000000_create_users_table.php',
            'database/migrations/2022_04_21_130143_create_tipo_usuario_table.php',
            'database/migrations/2022_04_21_130233_create_usuario_tipo_usuario_table.php',
            'database/migrations/2022_05_06_162824_create_variable_global_table.php',
            'database/migrations/2022_07_20_112628_create_ronda_table.php',
            'database/migrations/2022_07_20_112641_create_checkpoint_table.php',
            'database/migrations/2022_08_08_111537_create_circuito_table.php',
            'database/migrations/2022_08_08_111705_create_geofence_table.php',
            'database/migrations/2022_09_15_154202_create_imagen_checkpoint_table.php',
            'database/migrations/2022_09_19_091009_create_area_table.php',
            'database/migrations/2022_09_19_091118_create_area_usuario_table.php',
            'database/migrations/2022_09_19_091205_add_area_to_circuito_table.php',
            'database/migrations/2022_09_19_091402_add_circuito_to_ronda_table.php',
            'database/migrations/2022_09_20_094311_add_es_jefe_to_area_usuario_table.php',
            'database/migrations/2022_09_21_101444_add_soft_deletes_to_circuito_table.php',
        ]])->assertExitCode(0);
        $this->user = User::create(['nombre' => 'Operador', 'apellido' => 'Ronda', 'email' => 'test@example.test', 'password' => 'test']);
        $area = Area::create(['nombre' => 'Sector']);
        $this->user->areas()->attach($area);
        $this->circuito = Circuito::create(['titulo' => 'Circuito principal', 'created_by' => $this->user->id, 'area_id' => $area->id]);
    }

    private function ronda(bool $open = true): Ronda
    {
        return Ronda::create(['user_id' => $this->user->id, 'circuito_id' => $this->circuito->id, 'abierta' => $open]);
    }

    public function test_initial_visit_uses_the_inertia_root(): void
    {
        $this->actingAs($this->user)->get(route('ronda.index'))
            ->assertOk()
            ->assertSee('id="rondas-app"', false)
            ->assertInertia(fn (AssertableInertia $page) => $page->component('Rondas/Index')->has('abiertas', 0));
    }

    public function test_history_is_paginated_and_uses_counts_instead_of_relations(): void
    {
        for ($i = 0; $i < 31; $i++) $this->ronda(false);
        $ronda = $this->ronda(false);
        $checkpoint = Checkpoint::create(['ronda_id' => $ronda->id, 'user_id' => $this->user->id, 'latitud' => '-42', 'longitud' => '-65', 'novedad' => 'Aviso']);
        ImageCheckpoint::create(['checkpoint_id' => $checkpoint->id, 'filename' => 'test.jpeg']);
        $this->actingAs($this->user)->get(route('ronda.index'), ['X-Inertia' => 'true'])
            ->assertOk()->assertHeader('X-Inertia', 'true')
            ->assertJsonPath('component', 'Rondas/Index')
            ->assertJsonCount(25, 'props.cerradas.data')
            ->assertJsonPath('props.cerradas.total', 32)
            ->assertJsonPath('props.cerradas.data.0.checkpoints_count', 1)
            ->assertJsonPath('props.cerradas.data.0.images_count', 1)
            ->assertJsonPath('props.cerradas.data.0.novedades_count', 1)
            ->assertJsonMissingPath('props.cerradas.data.0.checkpoints')
            ->assertJsonMissingPath('props.cerradas.data.0.images');
        $this->get(route('ronda.index', ['page' => 2]), ['X-Inertia' => 'true'])
            ->assertJsonCount(7, 'props.cerradas.data');
    }

    public function test_search_and_area_visibility_are_preserved(): void
    {
        $this->ronda(false);
        Ronda::create(['user_id' => $this->user->id, 'circuito_id' => null, 'abierta' => false]);
        $this->actingAs($this->user)->get(route('ronda.index', ['q' => 'principal']))
            ->assertInertia(fn (AssertableInertia $page) => $page->where('cerradas.total', 1));
        $this->get(route('ronda.index', ['q' => 'inexistente']))
            ->assertInertia(fn (AssertableInertia $page) => $page->where('cerradas.total', 0));
        $admin = TipoUsuario::create(['nombre' => 'ADM_SIS', 'descripcion' => 'Administrador']);
        $this->user->tipos_usuario()->attach($admin);
        $this->user->unsetRelation('tipos_usuario');
        $this->get(route('ronda.index'))
            ->assertInertia(fn (AssertableInertia $page) => $page->where('cerradas.total', 2));
    }

    public function test_detail_has_photo_urls_without_serializing_private_user_fields(): void
    {
        $ronda = $this->ronda();
        $checkpoint = Checkpoint::create(['ronda_id' => $ronda->id, 'user_id' => $this->user->id, 'latitud' => '-42', 'longitud' => '-65', 'novedad' => '<texto>']);
        ImageCheckpoint::create(['checkpoint_id' => $checkpoint->id, 'filename' => 'test.jpeg']);
        $this->actingAs($this->user)->get(route('ronda.show', $ronda), ['X-Inertia' => 'true'])
            ->assertOk()->assertHeader('X-Inertia', 'true')
            ->assertJsonPath('component', 'Rondas/Show')
            ->assertJsonPath('props.ronda.abierta', true)
            ->assertJsonPath('props.ronda.checkpoints.0.novedad', '<texto>')
            ->assertJsonPath('props.ronda.checkpoints.0.images.0.url', asset("storage/ronda/{$checkpoint->id}/test.jpeg"))
            ->assertJsonPath('props.ronda.checkpoints.0.usuario', 'Operador Ronda')
            ->assertJsonMissingPath('props.ronda.checkpoints.0.user');
    }

    public function test_history_can_be_sorted_by_date_or_creator_on_the_server(): void
    {
        $ronda = $this->ronda(false);
        $ronda->forceFill(['created_at' => '2020-01-01 00:00:00'])->save();
        $another = User::create(['nombre' => 'Alberto', 'email' => 'another@example.test', 'password' => 'test']);
        $newer = Ronda::create(['user_id' => $another->id, 'circuito_id' => $this->circuito->id, 'abierta' => false]);
        $this->actingAs($this->user)->get(route('ronda.index', ['sort' => 'created_at', 'direction' => 'asc']))
            ->assertInertia(fn (AssertableInertia $page) => $page->where('cerradas.data.0.id', $ronda->id));
        $this->get(route('ronda.index', ['sort' => 'creador', 'direction' => 'asc']))
            ->assertInertia(fn (AssertableInertia $page) => $page->where('cerradas.data.0.id', $newer->id));
    }

    public function test_mutations_redirect_and_share_success_and_validation_errors(): void
    {
        $this->actingAs($this->user)->from(route('ronda.index'))
            ->post(route('ronda.store'), ['circuito_id' => $this->circuito->id], ['X-Inertia' => 'true'])
            ->assertRedirect(route('ronda.index'))->assertSessionHas('success', 'Ronda creada');
        $ronda = Ronda::first();
        $this->from(route('ronda.show', $ronda))->post(route('checkpoint.store', $ronda), [], ['X-Inertia' => 'true'])
            ->assertSessionHasErrors(['latitud', 'longitud']);
        $this->from(route('ronda.show', $ronda))->post(route('checkpoint.store', $ronda), [
            'latitud' => '-42.7', 'longitud' => '-65', 'novedad' => 'Control',
        ], ['X-Inertia' => 'true'])->assertRedirect(route('ronda.show', $ronda))->assertSessionHas('success', 'Novedad agregada');
        $this->assertDatabaseCount('checkpoint', 1);
        $this->from(route('ronda.index'))->patch(route('ronda.cerrar', $ronda), [], ['X-Inertia' => 'true'])
            ->assertStatus(303)->assertSessionHas('success', 'Ronda cerrada');
        $this->assertFalse((bool) $ronda->fresh()->abierta);
        $this->from(route('ronda.index'))->delete(route('ronda.destroy', $ronda), [], ['X-Inertia' => 'true'])
            ->assertStatus(303)->assertSessionHas('success', 'Ronda eliminada');
        $this->assertDatabaseCount('ronda', 0);
    }

    public function test_inertia_visit_to_blade_login_switches_to_full_navigation(): void
    {
        $this->get(route('login'), ['X-Inertia' => 'true'])
            ->assertStatus(409)->assertHeader('X-Inertia-Location', route('login'));
        $this->get(route('ronda.index'))->assertRedirect(route('login'));
    }

    public function test_checkpoint_rejects_invalid_coordinates_and_does_not_enlarge_small_photos(): void
    {
        Storage::fake('public');
        $ronda = $this->ronda();
        $this->actingAs($this->user)->from(route('ronda.show', $ronda))
            ->post(route('checkpoint.store', $ronda), ['latitud' => '100', 'longitud' => '-190'])
            ->assertSessionHasErrors(['latitud', 'longitud']);
        $this->assertDatabaseCount('checkpoint', 0);
        $photo = 'data:image/jpeg;base64,' . base64_encode((string) Image::canvas(100, 80, '#8fc6b1')->encode('jpg'));
        $this->post(route('checkpoint.store', $ronda), ['latitud' => '-42', 'longitud' => '-65', 'image64' => $photo])
            ->assertSessionHasNoErrors()->assertSessionHas('success', 'Novedad agregada');
        $checkpoint = Checkpoint::first();
        $image = $checkpoint->images->first();
        $path = "ronda/{$checkpoint->id}/{$image->filename}";
        Storage::disk('public')->assertExists($path);
        $stored = Image::make(Storage::disk('public')->get($path));
        $this->assertSame(100, $stored->width());
        $this->assertSame(80, $stored->height());
    }
}
