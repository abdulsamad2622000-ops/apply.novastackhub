<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class TaskSubmission extends Model
{
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_NEEDS_REVISION = 'needs_revision';

    protected $fillable = [
        'task_id',
        'task_applicant_id',
        'link',
        'github_link',
        'live_demo_url',
        'tech_stack',
        'file_path',
        'file_original_name',
        'notes',
        'linkedin_post_link',
        'linkedin_screenshot_path',
        'linkedin_screenshot_original_name',
        'confirmed_own_work',
        'status',
        'admin_feedback',
    ];

    protected $casts = [
        'confirmed_own_work' => 'boolean',
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function applicant(): BelongsTo
    {
        return $this->belongsTo(TaskApplicant::class, 'task_applicant_id');
    }

    public function fileUrl(): ?string
    {
        return $this->file_path ? Storage::disk('public')->url($this->file_path) : null;
    }

    public function linkedinScreenshotUrl(): ?string
    {
        return $this->linkedin_screenshot_path ? Storage::disk('public')->url($this->linkedin_screenshot_path) : null;
    }

    public static function statuses(): array
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_NEEDS_REVISION => 'Needs Revision',
        ];
    }
}