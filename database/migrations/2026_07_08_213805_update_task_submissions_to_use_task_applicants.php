<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('task_submissions', function (Blueprint $table) {
            $table->dropForeign(['task_id']);
        });

        Schema::table('task_submissions', function (Blueprint $table) {
            $table->dropUnique(['task_id', 'application_id']);
        });

        Schema::table('task_submissions', function (Blueprint $table) {
            $table->dropIndex('task_submissions_application_id_foreign');
        });

        Schema::table('task_submissions', function (Blueprint $table) {
            $table->renameColumn('application_id', 'task_applicant_id');
        });

        Schema::table('task_submissions', function (Blueprint $table) {
            $table->foreign('task_id')->references('id')->on('tasks')->cascadeOnDelete();
            $table->foreign('task_applicant_id')->references('id')->on('task_applicants')->cascadeOnDelete();
        });

        Schema::table('task_submissions', function (Blueprint $table) {
            $table->unique(['task_id', 'task_applicant_id']);
        });
    }

    public function down(): void
    {
        Schema::table('task_submissions', function (Blueprint $table) {
            $table->dropForeign(['task_id']);
            $table->dropForeign(['task_applicant_id']);
        });

        Schema::table('task_submissions', function (Blueprint $table) {
            $table->dropUnique(['task_id', 'task_applicant_id']);
        });

        Schema::table('task_submissions', function (Blueprint $table) {
            $table->renameColumn('task_applicant_id', 'application_id');
        });

        Schema::table('task_submissions', function (Blueprint $table) {
            $table->foreign('task_id')->references('id')->on('tasks')->cascadeOnDelete();
            $table->foreign('application_id')->references('id')->on('applications')->cascadeOnDelete();
        });

        Schema::table('task_submissions', function (Blueprint $table) {
            $table->unique(['task_id', 'application_id']);
        });
    }
};