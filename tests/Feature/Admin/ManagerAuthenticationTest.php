<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ManagerAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_manager_can_log_in_via_login_form(): void
    {
        Role::findOrCreate('manager', 'web');

        $manager = User::factory()->create([
            'email' => 'manager@example.com',
            'password' => Hash::make('secret-password'),
        ]);

        $manager->assignRole('manager');

        $this->post(route('login.store'), [
            'email' => 'manager@example.com',
            'password' => 'secret-password',
        ])->assertRedirect(route('admin.tickets.index'));

        $this->assertAuthenticatedAs($manager);
    }
}
