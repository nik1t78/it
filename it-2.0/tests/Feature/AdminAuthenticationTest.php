<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_is_available(): void
    {
        $this->get(route('admin.login'))
            ->assertOk()
            ->assertSee('Вход для сотрудников');
    }

    public function test_dashboard_redirects_guest_to_login(): void
    {
        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_sign_in_and_open_dashboard(): void
    {
        User::query()->create([
            'name' => 'Тестовый администратор',
            'email' => 'admin@example.test',
            'password' => 'Strong-password-2026',
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->post(route('admin.login.store'), [
            'email' => 'admin@example.test',
            'password' => 'Strong-password-2026',
        ])->assertRedirect(route('admin.dashboard'));

        $this->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Панель управления');
    }

    public function test_inactive_account_cannot_access_admin_area(): void
    {
        $user = User::query()->create([
            'name' => 'Отключённый пользователь',
            'email' => 'inactive@example.test',
            'password' => 'Strong-password-2026',
            'role' => 'admin',
            'is_active' => false,
        ]);

        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_regular_user_role_cannot_open_admin_dashboard(): void
    {
        $user = User::query()->create([
            'name' => 'Обычный пользователь',
            'email' => 'user@example.test',
            'password' => 'Strong-password-2026',
            'role' => 'user',
            'is_active' => true,
        ]);

        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }
}
