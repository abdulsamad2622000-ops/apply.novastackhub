<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('task_submissions', function (Blueprint $table) {
            $table->string('github_link')->nullable()->after('link');
            $table->string('live_demo_url')->nullable()->after('github_link');
            $table->string('tech_stack')->nullable()->after('live_demo_url');
            $table->string('linkedin_post_link')->nullable()->after('notes');
            $table->string('linkedin_screenshot_path')->nullable()->after('linkedin_post_link');
            $table->string('linkedin_screenshot_original_name')->nullable()->after('linkedin_screenshot_path');
            $table->boolean('confirmed_own_work')->default(false)->after('linkedin_screenshot_original_name');
            $table->string('status')->default('pending')->after('confirmed_own_work');
            $table->text('admin_feedback')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('task_submissions', function (Blueprint $table) {
            $table->dropColumn([
                'github_link',
                'live_demo_url',
                'tech_stack',
                'linkedin_post_link',
                'linkedin_screenshot_path',
                'linkedin_screenshot_original_name',
                'confirmed_own_work',
                'status',
                'admin_feedback',
            ]);
        });
    }
};