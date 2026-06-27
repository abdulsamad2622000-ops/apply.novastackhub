<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Application extends Model
{
    /** Available recruitment statuses. */
    public const STATUSES = ['Pending', 'Shortlisted', 'Rejected', 'Hired'];

    /**
     * @var list<string>
     */
    protected $fillable = [
        'job_id',
        'status',
        'full_name',
        'email',
        'phone',
        'city',
        'education',
        'skills',
        'linkedin_url',
        'portfolio_url',
        'experience_years',
        'experience_description',
        'commission_only',
        'outreach_platforms',
        'cv_path',
        'cv_original_name',
        'additional_info',
        'notes',
        'ip_address',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'outreach_platforms' => 'array',
            'commission_only'    => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<Job, Application>
     */
    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    /**
     * Public URL to the uploaded CV (requires `php artisan storage:link`).
     */
    public function cvUrl(): ?string
    {
        return $this->cv_path ? Storage::disk('public')->url($this->cv_path) : null;
    }
}
