<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jobs_listings', function (Blueprint $table) {
            // 'professional' = default form, 'internship' = student form
            $table->string('form_type')->default('professional')->after('type');
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->string('city')->nullable()->after('phone');
            $table->string('education')->nullable()->after('city');
            $table->text('skills')->nullable()->after('education');
            // make professional-only fields nullable for internship applicants
            $table->string('linkedin_url')->nullable()->change();
            $table->text('experience_description')->nullable()->change();
            $table->string('experience_years')->nullable()->change();
            $table->string('cv_path')->nullable()->change();
            $table->string('cv_original_name')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('jobs_listings', function (Blueprint $table) {
            $table->dropColumn('form_type');
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn(['city', 'education', 'skills']);
        });
    }
};
