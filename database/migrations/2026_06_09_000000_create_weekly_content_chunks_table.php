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
        $hasVectorExtension = false;
        
        if (Illuminate\Support\Facades\DB::getDriverName() === 'pgsql') {
            try {
                $result = Illuminate\Support\Facades\DB::select("SELECT 1 FROM pg_available_extensions WHERE name = 'vector'");
                if (!empty($result)) {
                    Schema::ensureVectorExtensionExists();
                    $hasVectorExtension = true;
                }
            } catch (\Exception $e) {
                // Keep false
            }
        }

        Schema::create('weekly_content_chunks', function (Blueprint $table) use ($hasVectorExtension) {
            $table->id();
            $table->foreignId('weekly_content_id')->constrained('weekly_contents')->onDelete('cascade');
            $table->integer('chunk_index');
            $table->text('content');
            
            if ($hasVectorExtension) {
                $table->vector('embedding', 3072);
            } else {
                $table->jsonb('embedding'); // Fallback to JSONB for storing embeddings array
            }
            
            $table->timestamps();
        });

        if ($hasVectorExtension) {
            Schema::table('weekly_content_chunks', function (Blueprint $table) {
                $table->vectorIndex('embedding');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weekly_content_chunks');
    }
};
