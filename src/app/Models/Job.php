<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Job extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUS_DRAFT = 'draft';

    public const STATUS_PUBLISHED = 'published';

    public const STATUS_CLOSED = 'closed';

    public const STATUS_EXPIRED = 'expired';

    public const STATUSES = [
        self::STATUS_DRAFT,
        self::STATUS_PUBLISHED,
        self::STATUS_CLOSED,
        self::STATUS_EXPIRED,
    ];

    public const TYPES = ['full_time', 'part_time', 'contract', 'internship', 'freelance', 'remote'];

    public const LOCATION_TYPES = ['on_site', 'remote', 'hybrid'];

    public const EXPERIENCE_LEVELS = ['entry', 'junior', 'mid', 'senior', 'lead', 'executive'];

    protected $fillable = [
        'company_id',
        'user_id',
        'category_id',
        'title',
        'slug',
        'description',
        'requirements',
        'responsibilities',
        'benefits',
        'salary_min',
        'salary_max',
        'salary_currency',
        'is_salary_negotiable',
        'location',
        'location_type',
        'job_type',
        'experience_level',
        'vacancies',
        'status',
        'deadline',
        'views_count',
        'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'salary_min' => 'decimal:2',
            'salary_max' => 'decimal:2',
            'is_salary_negotiable' => 'boolean',
            'vacancies' => 'integer',
            'views_count' => 'integer',
            'is_featured' => 'boolean',
            'deadline' => 'date',
        ];
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(JobCategory::class, 'category_id');
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class)->withTimestamps();
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function savedBy()
    {
        return $this->belongsToMany(User::class, 'saved_jobs')->withTimestamps();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function salaryLabel(): Attribute
    {
        return Attribute::get(function () {
            if ($this->is_salary_negotiable) {
                return 'Negotiable';
            }

            if ($this->salary_min && $this->salary_max) {
                return $this->salary_currency.' '.number_format($this->salary_min).' - '.$this->salary_currency.' '.number_format($this->salary_max);
            }

            if ($this->salary_min) {
                return 'From '.$this->salary_currency.' '.number_format($this->salary_min);
            }

            return 'Not disclosed';
        });
    }

    public function isOpen(): bool
    {
        if ($this->status !== self::STATUS_PUBLISHED) {
            return false;
        }

        return $this->deadline === null || $this->deadline->gte(now()->toDateString());
    }

    public function scopeOpen($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED)
            ->where(function ($q) {
                $q->whereNull('deadline')->orWhere('deadline', '>=', now()->toDateString());
            });
    }

    public function hasExpired(): bool
    {
        return $this->deadline !== null && $this->deadline->lt(now()->toDateString());
    }

    public function registerView(): void
    {
        $this->increment('views_count');
    }
}
