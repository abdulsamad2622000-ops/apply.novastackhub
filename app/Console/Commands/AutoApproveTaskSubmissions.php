<?php

namespace App\Console\Commands;

use App\Models\TaskSubmission;
use Illuminate\Console\Command;

class AutoApproveTaskSubmissions extends Command
{
    protected $signature = 'task-submissions:auto-approve';

    protected $description = 'Auto-approve task submissions that have been pending for 30+ minutes';

    public function handle(): int
    {
        $count = TaskSubmission::where('status', 'pending')
            ->where('created_at', '<=', now()->subMinutes(30))
            ->update(['status' => 'approved']);

        $this->info("Auto-approved {$count} submission(s).");

        return self::SUCCESS;
    }
}
