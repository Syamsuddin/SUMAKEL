<?php

namespace Tests\Feature;

use App\Models\Opd;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['superadmin', 'admin_tu', 'pimpinan', 'staf'] as $role) {
            Role::firstOrCreate(['name' => $role]);
        }
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_login_page_is_accessible(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_superadmin_can_login_and_reach_dashboard(): void
    {
        $user = User::factory()->create(['password' => bcrypt('password')]);
        $user->assignRole('superadmin');

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee($user->name);
    }

    public function test_admin_tu_can_login_and_reach_dashboard(): void
    {
        $opd = Opd::create(['kode' => 'TEST', 'nama' => 'OPD Test']);
        $user = User::factory()->create(['password' => bcrypt('password'), 'opd_id' => $opd->id]);
        $user->assignRole('admin_tu');

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect('/dashboard');

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
    }

    public function test_pimpinan_can_login_and_reach_dashboard(): void
    {
        $opd = Opd::create(['kode' => 'TEST', 'nama' => 'OPD Test']);
        $user = User::factory()->create(['password' => bcrypt('password'), 'opd_id' => $opd->id]);
        $user->assignRole('pimpinan');

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect('/dashboard');

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
    }

    public function test_staf_can_login_and_reach_dashboard(): void
    {
        $opd = Opd::create(['kode' => 'TEST', 'nama' => 'OPD Test']);
        $user = User::factory()->create(['password' => bcrypt('password'), 'opd_id' => $opd->id]);
        $user->assignRole('staf');

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect('/dashboard');

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
    }

    public function test_register_route_is_disabled(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(404);
    }

    public function test_invalid_credentials_are_rejected(): void
    {
        $user = User::factory()->create(['password' => bcrypt('password')]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }
}
