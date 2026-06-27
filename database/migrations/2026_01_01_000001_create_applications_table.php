<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->nullable()->constrained('jobs_listings')->nullOnDelete();
            $table->string('full_name');
            $table->string('email');
            $table->string('phone');
            $table->string('linkedin_url');
            $table->string('portfolio_url')->nullable();
            $table->string('experience_years');
            $table->text('experience_description');
            $table->boolean('commission_only')->nullable();
            $table->json('outreach_platforms')->nullable();
            $table->string('cv_path');
            $table->string('cv_original_name');
            $table->text('additional_info')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            $table->index('email');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
