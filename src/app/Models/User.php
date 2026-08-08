<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    public const ROLE_JOB_SEEKER = 'job_seeker';

    public const ROLE_EMPLOYER = 'employer';

    public const ROLE_ADMIN = 'admin';

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'photo',
        'headline',
        'phone',
        'location',
        'date_of_birth',
        'gender',
        'address',
        'website',
        'linkedin',
        'github',
        'summary',
        'visibility',
        'is_banned',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'date_of_birth' => 'date',
            'is_banned' => 'boolean',
            'password' => 'hashed',
        ];
    }

    public function photoUrl(): Attribute
    {
        return Attribute::get(fn () => $this->photo
            ? (str_starts_with($this->photo, 'http') ? $this->photo : asset('storage/'.$this->photo))
            : 'https://ui-avatars.com/api/?name='.urlencode($this->name).'&background=0d9488&color=fff&size=128');
    }

    public function isJobSeeker(): bool
    {
        return $this->role === self::ROLE_JOB_SEEKER;
    }

    public function isEmployer(): bool
    {
        return $this->role === self::ROLE_EMPLOYER;
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function company()
    {
        return $this->hasOne(Company::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    public function savedJobs()
    {
        return $this->belongsToMany(Job::class, 'saved_jobs')->withTimestamps();
    }

    public function following()
    {
        return $this->belongsToMany(Company::class, 'company_followers')->withTimestamps();
    }

    public function educations()
    {
        return $this->hasMany(Education::class)->orderByDesc('is_current')->orderByDesc('start_date');
    }

    public function employments()
    {
        return $this->hasMany(Employment::class)->orderByDesc('is_current')->orderByDesc('start_date');
    }

    public function certifications()
    {
        return $this->hasMany(Certification::class);
    }

    public function languages()
    {
        return $this->hasMany(Language::class);
    }

    public function portfolios()
    {
        return $this->hasMany(Portfolio::class);
    }

    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }

    public function awards()
    {
        return $this->hasMany(Award::class);
    }

    public function hobbies()
    {
        return $this->hasMany(Hobby::class);
    }

    public function references()
    {
        return $this->hasMany(UserReference::class);
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'professional_skill_user')
            ->withPivot('proficiency')
            ->withTimestamps();
    }

    public function scopeJobSeeker($query)
    {
        return $query->where('role', self::ROLE_JOB_SEEKER);
    }

    public function scopeEmployer($query)
    {
        return $query->where('role', self::ROLE_EMPLOYER);
    }

    public function scopeAdmin($query)
    {
        return $query->where('role', self::ROLE_ADMIN);
    }

    public function profileCompletion(): int
    {
        $score = 0;

        $score += $this->photo ? 10 : 0;
        $score += $this->headline ? 8 : 0;
        $score += $this->summary ? 8 : 0;
        $score += $this->location ? 5 : 0;
        $score += $this->phone ? 5 : 0;
        $score += $this->date_of_birth ? 3 : 0;
        $score += $this->address ? 3 : 0;
        $score += ($this->website || $this->linkedin || $this->github) ? 3 : 0;
        $score += $this->educations()->exists() ? 12 : 0;
        $score += $this->employments()->exists() ? 12 : 0;
        $score += $this->skills()->exists() ? 10 : 0;
        $score += $this->languages()->exists() ? 5 : 0;
        $score += $this->certifications()->exists() ? 4 : 0;
        $score += $this->portfolios()->exists() ? 4 : 0;
        $score += $this->awards()->exists() ? 3 : 0;
        $score += $this->memberships()->exists() ? 2 : 0;
        $score += $this->hobbies()->exists() ? 2 : 0;
        $score += $this->references()->exists() ? 1 : 0;

        return min(100, $score);
    }
}
