<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class TaskSubmission extends Model
{
    protected $fillable = [
        'task_id', 'task_applicant_id', 'link', 'file_path', 'file_original_name', 'notes',
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
}