<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaskApplicant extends Model
{
    protected $fillable = [
        'full_name', 'email', 'phone', 'city', 'education', 'skills', 'form_submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'form_submitted_at' => 'datetime',
        ];
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(TaskSubmission::class);
    }
}