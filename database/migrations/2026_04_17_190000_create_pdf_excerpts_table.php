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
        Schema::create('pdf_excerpts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('post_id')
                ->constrained()
                ->onUpdate('restrict')
                ->onDelete('cascade')
                ->unique();
            $table->text('excerpt')->nullable();
            $table->json('toc')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pdf_excerpts');
    }
};
