<?php

namespace Tests\Feature;

use App\Livewire\Profile\ProfileBuilder;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ProfileBuilderTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeker_can_view_profile_builder()
    {
        $seeker = User::factory()->create(['role' => 'job_seeker']);

        $this->actingAs($seeker)->get(route('profile.edit'))->assertOk();
        $this->actingAs($seeker)->get(route('profile.section', 'education'))->assertOk();
        $this->actingAs($seeker)->get(route('profile.section', 'skills'))->assertOk();
    }

    public function test_seeker_can_update_basic_info()
    {
        $seeker = User::factory()->create(['role' => 'job_seeker']);

        Livewire::actingAs($seeker)
            ->test(ProfileBuilder::class)
            ->set('headline', 'Full Stack Developer')
            ->set('location', 'Berlin')
            ->call('saveBasicInfo')
            ->assertHasNoErrors();

        $this->assertEquals('Full Stack Developer', $seeker->fresh()->headline);
        $this->assertEquals('Berlin', $seeker->fresh()->location);
    }

    public function test_seeker_can_add_education()
    {
        $seeker = User::factory()->create(['role' => 'job_seeker']);

        Livewire::actingAs($seeker)
            ->test(ProfileBuilder::class)
            ->call('addEducation')
            ->set('educations.0.school', 'MIT')
            ->set('educations.0.degree', 'BSc')
            ->set('educations.0.field_of_study', 'Computer Science')
            ->call('saveEducations')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('educations', [
            'user_id' => $seeker->id,
            'school' => 'MIT',
        ]);
    }

    public function test_seeker_can_add_employment()
    {
        $seeker = User::factory()->create(['role' => 'job_seeker']);

        Livewire::actingAs($seeker)
            ->test(ProfileBuilder::class)
            ->call('addEmployment')
            ->set('employments.0.job_title', 'Developer')
            ->set('employments.0.company_name', 'Acme')
            ->call('saveEmployments')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('employments', [
            'user_id' => $seeker->id,
            'job_title' => 'Developer',
        ]);
    }

    public function test_seeker_can_add_skill_to_profile()
    {
        $seeker = User::factory()->create(['role' => 'job_seeker']);
        $skill = Skill::create(['name' => 'Laravel', 'slug' => 'laravel']);

        Livewire::actingAs($seeker)
            ->test(ProfileBuilder::class)
            ->call('addSkillPreset', $skill->id)
            ->call('saveSkills')
            ->assertHasNoErrors();

        $this->assertTrue($seeker->fresh()->skills()->where('skills.id', $skill->id)->exists());
    }

    public function test_seeker_can_remove_skill_from_profile()
    {
        $seeker = User::factory()->create(['role' => 'job_seeker']);
        $skill = Skill::create(['name' => 'React', 'slug' => 'react']);
        $seeker->skills()->attach($skill->id);

        Livewire::actingAs($seeker)
            ->test(ProfileBuilder::class)
            ->call('removeSkill', $skill->id)
            ->call('saveSkills')
            ->assertHasNoErrors();

        $this->assertFalse($seeker->fresh()->skills()->where('skills.id', $skill->id)->exists());
    }

    public function test_public_profile_visible_to_guests()
    {
        $seeker = User::factory()->create([
            'role' => 'job_seeker',
            'username' => 'john-doe',
            'visibility' => 'public',
        ]);

        $this->get(route('profile.public', 'john-doe'))->assertOk();
    }

    public function test_private_profile_returns_404()
    {
        $seeker = User::factory()->create([
            'role' => 'job_seeker',
            'username' => 'private-user',
            'visibility' => 'private',
        ]);

        $this->get(route('profile.public', 'private-user'))->assertNotFound();
    }
}
