<?php

namespace Tests\Browser;

use App\Models\Company;
use App\Models\Job;
use App\Models\JobCategory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class JobSearchBrowserTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_guest_can_browse_and_search_jobs(): void
    {
        $company = Company::factory()->create(['name' => 'Dusk Corp']);
        $category = JobCategory::create(['name' => 'Engineering', 'slug' => 'engineering']);

        Job::factory()->create([
            'company_id' => $company->id,
            'user_id' => $company->user_id,
            'category_id' => $category->id,
            'title' => 'Senior Laravel Developer',
            'status' => Job::STATUS_PUBLISHED,
        ]);

        Job::factory()->create([
            'company_id' => $company->id,
            'user_id' => $company->user_id,
            'category_id' => $category->id,
            'title' => 'UI Designer',
            'status' => Job::STATUS_PUBLISHED,
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/jobs')
                ->assertSee('Find your next job')
                ->assertSee('Senior Laravel Developer')
                ->assertSee('UI Designer')
                ->type('input[placeholder="Job title, skill, or keyword"]', 'Laravel')
                ->waitForText('Senior Laravel Developer')
                ->assertDontSee('UI Designer');
        });
    }
}
