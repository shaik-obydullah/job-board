<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';

    public const STATUS_REVIEWED = 'reviewed';

    public const STATUS_SHORTLISTED = 'shortlisted';

    public const STATUS_REJECTED = 'rejected';

    public const STATUS_HIRED = 'hired';

    public const STATUS_WITHDRAWN = 'withdrawn';

    public const STATUSES = [
        self::STATUS_PENDING,
        self::STATUS_REVIEWED,
        self::STATUS_SHORTLISTED,
        self::STATUS_REJECTED,
        self::STATUS_HIRED,
        self::STATUS_WITHDRAWN,
    ];

    protected $fillable = [
        'job_id',
        'user_id',
        'cover_letter',
        'cv_path',
        'status',
        'employer_note',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'reviewed_at' => 'datetime',
        ];
    }

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
