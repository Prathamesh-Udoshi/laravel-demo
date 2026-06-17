<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add email column to students table
        Schema::table('students', function (Blueprint $table) {
            $table->string('email')->nullable()->unique();
        });

        // 2. Create the pivot table for course registrations
        Schema::create('course_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->integer('progress_percent')->default(0); // 0 to 100%
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('last_reminded_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_student');
        
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('email');
        });
    }
};
