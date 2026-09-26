<?php

namespace Tests\Feature;

use App\Models\Rol;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    protected string $seeder = DatabaseSeeder::class;

    private function admin(): User
    {
        return User::where('email', 'superusuario@live.es')->firstOrFail();
    }

    private function usuario(): User
    {
        return User::where('email', 'simpleusuario@live.es')->firstOrFail();
    }

    public function test_registro_crea_usuario_con_rol_usuario_y_token(): void
    {
        $response = $this->postJson('/api/register', [
            'nombres' => 'Juan',
            'apellido_paterno' => 'Pérez',
            'email' => 'juan@example.com',
            'password' => 'secreto123',
            'roles_id' => 1, // debe ignorarse
        ]);

        $response->assertCreated()->assertJsonStructure(['user' => ['id', 'email'], 'token']);

        $user = User::where('email', 'juan@example.com')->first();
        $this->assertSame(Rol::USUARIO, $user->roles->rol);
    }

    public function test_login_y_perfil(): void
    {
        $token = $this->postJson('/api/auth/login', [
            'email' => 'simpleusuario@live.es',
            'password' => '123456',
        ])->assertOk()->json('token');

        $this->withToken($token)->getJson('/api/auth/profile')
            ->assertOk()
            ->assertJsonPath('data.email', 'simpleusuario@live.es')
            ->assertJsonMissingPath('data.password');
    }

    public function test_login_con_credenciales_invalidas(): void
    {
        $this->postJson('/api/auth/login', ['email' => 'simpleusuario@live.es', 'password' => 'mal'])
            ->assertUnauthorized();

        $this->postJson('/api/auth/login', [])->assertUnprocessable();
    }

    public function test_rutas_protegidas_requieren_token(): void
    {
        $this->getJson('/api/users')->assertUnauthorized();
    }

    public function test_usuario_puede_actualizarse_a_si_mismo_pero_no_a_otros(): void
    {
        $usuario = $this->usuario();
        Sanctum::actingAs($usuario);

        $this->putJson("/api/users/{$usuario->id}", [
            'nombres' => 'Nuevo',
            'email' => $usuario->email, // su propio email no debe fallar por unique
        ])->assertOk()->assertJsonPath('data.nombres', 'Nuevo');

        $this->putJson("/api/users/{$this->admin()->id}", ['nombres' => 'Hack'])->assertForbidden();
        $this->deleteJson("/api/users/{$this->admin()->id}")->assertForbidden();
    }

    public function test_admin_puede_actualizar_y_eliminar_usuarios(): void
    {
        Sanctum::actingAs($this->admin());
        $usuario = $this->usuario();

        $this->putJson("/api/users/{$usuario->id}", ['estatus_id' => 2])
            ->assertOk()->assertJsonPath('data.estatus_id', 2);

        $this->deleteJson("/api/users/{$usuario->id}")->assertOk();
        $this->assertModelMissing($usuario);
    }

    public function test_listado_paginado(): void
    {
        Sanctum::actingAs($this->usuario());

        $this->getJson('/api/users')->assertOk()->assertJsonCount(2, 'data');
    }
}
