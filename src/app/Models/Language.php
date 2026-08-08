<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;

    protected $table = 'languages';

    public const PROFICIENCIES = [
        'elementary' => 'Elementary',
        'limited_working' => 'Limited Working',
        'professional_working' => 'Professional Working',
        'full_professional' => 'Full Professional',
        'native' => 'Native / Bilingual',
    ];

    protected $fillable = ['user_id', 'name', 'proficiency'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
