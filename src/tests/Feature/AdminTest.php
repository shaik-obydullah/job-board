<?php

namespace Tests\Feature;

use App\Livewire\Admin\AdminUsers;
use App\Livewire\Admin\CompanyManagement;
use App\Livewire\Admin\JobCategoryManagement;
use App\Livewire\Admin\SkillManagement;
use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    public function test_admin_can_view_dashboard()
    {
        $this->actingAs($this->admin())->get('/admin')->assertOk();
    }

    public function test_admin_can_view_users()
    {
        $this->actingAs($this->admin())->get('/admin/users')->assertOk();
    }

    public function test_admin_can_view_companies()
    {
        $this->actingAs($this->admin())->get('/admin/companies')->assertOk();
    }

    public function test_admin_can_view_jobs()
    {
        $this->actingAs($this->admin())->get('/admin/jobs')->assertOk();
    }

    public function test_admin_can_view_categories()
    {
        $this->actingAs($this->admin())->get('/admin/categories')->assertOk();
    }

    public function test_admin_can_view_skills()
    {
        $this->actingAs($this->admin())->get('/admin/skills')->assertOk();
    }

    public function test_admin_can_view_settings()
    {
        $this->actingAs($this->admin())->get('/admin/settings')->assertOk();
    }

    public function test_non_admin_cannot_access_admin()
    {
        $seeker = User::factory()->create(['role' => 'job_seeker']);

        $this->actingAs($seeker)->get('/admin')->assertForbidden();
    }

    public function test_employer_cannot_access_admin()
    {
        $employer = User::factory()->create(['role' => 'employer']);

        $this->actingAs($employer)->get('/admin/users')->assertForbidden();
    }

    public function test_admin_can_toggle_user_ban()
    {
        $admin = $this->admin();
        $target = User::factory()->create(['role' => 'job_seeker']);

        Livewire::actingAs($admin)
            ->test(AdminUsers::class)
            ->call('toggleBan', $target->id)
            ->assertHasNoErrors();

        $this->assertTrue((bool) $target->fresh()->is_banned);
    }

    public function test_admin_can_update_user_role()
    {
        $admin = $this->admin();
        $target = User::factory()->create(['role' => 'job_seeker']);

        Livewire::actingAs($admin)
            ->test(AdminUsers::class)
            ->call('updateRole', $target->id, 'employer')
            ->assertHasNoErrors();

        $this->assertEquals('employer', $target->fresh()->role);
    }

    public function test_admin_can_create_category()
    {
        $admin = $this->admin();

        Livewire::actingAs($admin)
            ->test(JobCategoryManagement::class)
            ->set('name', 'Design')
            ->call('create')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('job_categories', ['name' => 'Design']);
    }

    public function test_admin_can_create_skill()
    {
        $admin = $this->admin();

        Livewire::actingAs($admin)
            ->test(SkillManagement::class)
            ->set('name', 'Vue.js')
            ->call('create')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('skills', ['name' => 'Vue.js']);
    }

    public function test_admin_can_toggle_company_verification()
    {
        $admin = $this->admin();
        $employer = User::factory()->create(['role' => 'employer']);
        $company = Company::factory()->create(['user_id' => $employer->id, 'is_verified' => false]);

        Livewire::actingAs($admin)
            ->test(CompanyManagement::class)
            ->call('toggleVerified', $company->id)
            ->assertHasNoErrors();

        $this->assertTrue((bool) $company->fresh()->is_verified);
    }

    public function test_admin_can_toggle_company_active()
    {
        $admin = $this->admin();
        $employer = User::factory()->create(['role' => 'employer']);
        $company = Company::factory()->create(['user_id' => $employer->id, 'is_active' => true]);

        Livewire::actingAs($admin)
            ->test(CompanyManagement::class)
            ->call('toggleActive', $company->id)
            ->assertHasNoErrors();

        $this->assertFalse((bool) $company->fresh()->is_active);
    }
}
