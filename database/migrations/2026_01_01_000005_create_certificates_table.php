<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->string('certificate_number')->unique();
            $table->string('full_name');
            $table->string('title'); // e.g. "Web Development Internship"
            $table->foreignId('application_id')->nullable()->constrained('applications')->nullOnDelete();
            $table->date('issue_date');
            $table->date('completion_date')->nullable();
            $table->enum('status', ['valid', 'revoked'])->default('valid');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('certificate_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};