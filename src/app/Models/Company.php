<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'logo',
        'banner',
        'description',
        'website',
        'location',
        'industry',
        'size',
        'founded_year',
        'email',
        'phone',
        'mission',
        'is_verified',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_verified' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    public function publishedJobs()
    {
        return $this->hasMany(Job::class)->where('status', 'published')->where(function ($q) {
            $q->whereNull('deadline')->orWhere('deadline', '>=', now()->toDateString());
        });
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'company_followers')->withTimestamps();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
