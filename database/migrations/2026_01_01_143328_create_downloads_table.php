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
        Schema::create('downloads', function (Blueprint $table) {
            $table->id();
            
            // Foreign key to posts table with cascade delete
            $table->foreignId('post_id')
                ->constrained('posts')
                ->cascadeOnDelete();
            
            // Timestamp for when the download occurred
            $table->timestamp('downloaded_at');
            
            // Indexes for optimizing temporal queries
            $table->index('post_id');
            $table->index('downloaded_at');
            $table->index(['post_id', 'downloaded_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('downloads');
    }
};
