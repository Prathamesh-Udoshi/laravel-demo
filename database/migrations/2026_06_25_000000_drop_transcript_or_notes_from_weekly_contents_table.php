<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasColumn('weekly_contents', 'transcript_or_notes')) {
            Schema::table('weekly_contents', function (Blueprint $table) {
                $table->dropColumn('transcript_or_notes');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('weekly_contents', function (Blueprint $table) {
            $table->longText('transcript_or_notes')->nullable();
        });
    }
};
