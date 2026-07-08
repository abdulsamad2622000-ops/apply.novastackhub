<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificate extends Model
{
    protected $fillable = [
        'certificate_number', 'full_name', 'title', 'application_id',
        'issue_date', 'completion_date', 'status', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'issue_date' => 'date',
            'completion_date' => 'date',
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