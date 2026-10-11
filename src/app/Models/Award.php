<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Award extends Model
{
    use HasFactory;

    protected $table = 'awards';

    protected $fillable = ['user_id', 'title', 'issuer', 'date', 'description'];

    protected function casts(): array
    {
        return ['date' => 'date'];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
