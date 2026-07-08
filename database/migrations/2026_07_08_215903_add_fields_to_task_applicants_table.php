<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('task_applicants', function (Blueprint $table) {
            $table->string('full_name')->after('id');
            $table->string('email')->nullable()->after('full_name');
            $table->string('phone')->nullable()->after('email');
            $table->string('city')->nullable()->after('phone');
            $table->string('education')->nullable()->after('city');
            $table->string('skills')->nullable()->after('education');
            $table->timestamp('form_submitted_at')->nullable()->after('skills');

            $table->index('email');
            $table->index('phone');
        });
    }

    public function down(): void
    {
        Schema::table('task_applicants', function (Blueprint $table) {
            $table->dropColumn(['full_name', 'email', 'phone', 'city', 'education', 'skills', 'form_submitted_at']);
        });
    }
};
