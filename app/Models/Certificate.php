<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificate extends Model
{
    protected $fillable = [
        'certificate_number', 'full_name', 'email', 'title', 'application_id',
        'start_date', 'end_date',
        'issue_date', 'completion_date', 'status', 'emailed_at', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'issue_date' => 'date',
            'completion_date' => 'date',
            'start_date' => 'date',
            'end_date' => 'date',
            'emailed_at' => 'datetime',
        ];
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function isValid(): bool
    {
        return $this->status === 'valid';
    }
}