<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Job;
use App\Models\JobCategory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\URL;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $urls = [];

        $staticPages = [
            URL::route('home'),
            URL::route('jobs.index'),
            URL::route('companies.index'),
        ];

        foreach ($staticPages as $url) {
            $urls[] = ['loc' => $url, 'lastmod' => now()->toDateString()];
        }

        foreach (Job::open()->latest()->get() as $job) {
            $urls[] = ['loc' => URL::route('jobs.show', $job->slug), 'lastmod' => $job->updated_at->toDateString()];
        }

        foreach (Company::where('is_active', true)->get() as $company) {
            $urls[] = ['loc' => URL::route('companies.show', $company->slug), 'lastmod' => $company->updated_at->toDateString()];
        }

        foreach (JobCategory::where('is_active', true)->get() as $category) {
            $urls[] = ['loc' => URL::route('jobs.index').'?category='.$category->slug, 'lastmod' => now()->toDateString()];
        }

        return response()->view('sitemap', ['urls' => $urls])->header('Content-Type', 'application/xml');
    }
}
