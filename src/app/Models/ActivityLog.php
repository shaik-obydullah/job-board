<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    public const UPDATED_AT = null;

    protected $fillable = ['user_id', 'type', 'description', 'data'];

    protected function casts(): array
    {
        return ['data' => 'array'];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function record(?User $user, string $type, ?string $description = null, array $data = []): void
    {
        static::create([
            'user_id' => $user?->id,
            'type' => $type,
            'description' => $description,
            'data' => $data,
        ]);
    }
}
