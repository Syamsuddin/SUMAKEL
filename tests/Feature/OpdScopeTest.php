<?php

namespace Tests\Feature;

use App\Models\Opd;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class OpdScopeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['superadmin', 'admin_tu', 'pimpinan', 'staf'] as $role) {
            Role::firstOrCreate(['name' => $role]);
        }
    }

    public function test_admin_tu_can_only_see_users_in_own_opd(): void
    {
        $opdA = Opd::create(['kode' => 'OPD_A', 'nama' => 'OPD A']);
        $opdB = Opd::create(['kode' => 'OPD_B', 'nama' => 'OPD B']);

        $adminA = User::factory()->create(['opd_id' => $opdA->id]);
        $adminA->assignRole('admin_tu');

        $userA = User::factory()->create(['opd_id' => $opdA->id]);
        $userA->assignRole('staf');

        $userB = User::factory()->create(['opd_id' => $opdB->id]);
        $userB->assignRole('staf');

        $response = $this->actingAs($adminA)->get(route('user.index'));
        $response->assertStatus(200);
        $response->assertSee($userA->name);
        $response->assertDontSee($userB->name);
    }

    public function test_admin_tu_cannot_edit_user_from_other_opd(): void
    {
        $opdA = Opd::create(['kode' => 'OPD_A', 'nama' => 'OPD A']);
        $opdB = Opd::create(['kode' => 'OPD_B', 'nama' => 'OPD B']);

        $adminA = User::factory()->create(['opd_id' => $opdA->id]);
        $adminA->assignRole('admin_tu');

        $userB = User::factory()->create(['opd_id' => $opdB->id]);
        $userB->assignRole('staf');

        $response = $this->actingAs($adminA)->get(route('user.edit', $userB));
        $response->assertStatus(403);
    }

    public function test_superadmin_can_see_all_users(): void
    {
        $opdA = Opd::create(['kode' => 'OPD_A', 'nama' => 'OPD A']);
        $opdB = Opd::create(['kode' => 'OPD_B', 'nama' => 'OPD B']);

        $superadmin = User::factory()->create();
        $superadmin->assignRole('superadmin');

        $userA = User::factory()->create(['opd_id' => $opdA->id]);
        $userA->assignRole('staf');

        $userB = User::factory()->create(['opd_id' => $opdB->id]);
        $userB->assignRole('staf');

        $response = $this->actingAs($superadmin)->get(route('user.index'));
        $response->assertStatus(200);
        $response->assertSee($userA->name);
        $response->assertSee($userB->name);
    }

    public function test_superadmin_can_manage_opds(): void
    {
        $superadmin = User::factory()->create();
        $superadmin->assignRole('superadmin');

        $response = $this->actingAs($superadmin)->post(route('opd.store'), [
            'kode' => 'NEW',
            'nama' => 'OPD Baru',
        ]);
        $response->assertRedirect(route('opd.index'));
        $this->assertDatabaseHas('opds', ['kode' => 'NEW']);
    }

    public function test_non_superadmin_cannot_manage_opds(): void
    {
        $opd = Opd::create(['kode' => 'TEST', 'nama' => 'Test']);
        $admin = User::factory()->create(['opd_id' => $opd->id]);
        $admin->assignRole('admin_tu');

        $response = $this->actingAs($admin)->get(route('opd.index'));
        $response->assertStatus(403);
    }
}
