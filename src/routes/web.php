<?php

use App\Http\Controllers\SitemapController;
use App\Livewire\Admin\AdminDashboard;
use App\Livewire\Admin\AdminJobs;
use App\Livewire\Admin\AdminUsers;
use App\Livewire\Admin\CompanyManagement;
use App\Livewire\Admin\JobCategoryManagement;
use App\Livewire\Admin\SkillManagement;
use App\Livewire\Admin\SystemSettings;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Auth\VerifyEmail;
use App\Livewire\Company\CompanyDirectory;
use App\Livewire\Company\CompanyProfilePage;
use App\Livewire\Company\CompanySettings;
use App\Livewire\Company\JobApplicants;
use App\Livewire\Company\JobForm;
use App\Livewire\Company\MyCompany;
use App\Livewire\Company\MyJobs;
use App\Livewire\Dashboard;
use App\Livewire\Home\HomePage;
use App\Livewire\Job\AppliedJobs;
use App\Livewire\Job\JobDetail;
use App\Livewire\Job\JobSearch;
use App\Livewire\Job\SavedJobs;
use App\Livewire\Notifications\NotificationCenter;
use App\Livewire\Profile\ProfileBuilder;
use App\Livewire\Profile\PublicProfile;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::view('/up', 'up')->name('health');

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Public routes
Route::get('/', HomePage::class)->name('home');
Route::get('/jobs', JobSearch::class)->name('jobs.index');
Route::get('/jobs/{job:slug}', JobDetail::class)->name('jobs.show');
Route::get('/companies', CompanyDirectory::class)->name('companies.index');
Route::get('/companies/{company:slug}', CompanyProfilePage::class)->name('companies.show');
Route::get('/u/{username}', PublicProfile::class)->name('profile.public')->where('username', '[A-Za-z0-9._-]+');

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
    Route::get('/forgot-password', ForgotPassword::class)->name('password.request');
    Route::get('/reset-password/{token}', ResetPassword::class)->name('password.reset');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', function () {
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('home');
    })->name('logout');

    Route::get('/email/verify', VerifyEmail::class)->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (Request $request, $id, $hash) {
        $user = User::findOrFail($id);

        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            abort(403);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect()->route('dashboard');
    })->middleware('signed')->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    })->middleware('throttle:6,1')->name('verification.send');

    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    Route::get('/notifications', NotificationCenter::class)->name('notifications.index');
});

// Job seeker routes
Route::middleware(['auth', 'role:job_seeker,admin'])->group(function () {
    Route::get('/profile', ProfileBuilder::class)->name('profile.edit');
    Route::get('/profile/{section}', ProfileBuilder::class)->name('profile.section')->whereIn('section', [
        'basic-info', 'summary', 'education', 'employment', 'certifications', 'skills',
        'languages', 'portfolio', 'memberships', 'awards', 'hobbies', 'references',
    ]);

    Route::get('/saved-jobs', SavedJobs::class)->name('saved-jobs');
    Route::get('/applied-jobs', AppliedJobs::class)->name('applied-jobs');
});

// Employer routes
Route::middleware(['auth', 'role:employer,admin'])->group(function () {
    Route::get('/company', MyCompany::class)->name('company.show');
    Route::get('/company/settings', CompanySettings::class)->name('company.settings');
    Route::get('/company/jobs/create', JobForm::class)->name('company.jobs.create');
    Route::get('/company/jobs/{job}/edit', JobForm::class)->name('company.jobs.edit');
    Route::get('/company/jobs', MyJobs::class)->name('company.jobs');
    Route::get('/company/jobs/{job}/applicants', JobApplicants::class)->name('company.jobs.applicants');
});

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', AdminDashboard::class)->name('dashboard');
    Route::get('/users', AdminUsers::class)->name('users');
    Route::get('/companies', CompanyManagement::class)->name('companies');
    Route::get('/jobs', AdminJobs::class)->name('jobs');
    Route::get('/categories', JobCategoryManagement::class)->name('categories');
    Route::get('/skills', SkillManagement::class)->name('skills');
    Route::get('/settings', SystemSettings::class)->name('settings');
});
