<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserReference extends Model
{
    use HasFactory;

    protected $table = 'user_references';

    protected $fillable = ['user_id', 'name', 'job_title', 'company_name', 'email', 'phone', 'relationship'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
