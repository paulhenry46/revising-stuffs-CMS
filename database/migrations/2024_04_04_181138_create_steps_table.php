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
        Schema::create('steps', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id')
            ->constrained()
            ->onDelete('CASCADE');
            $table->foreignId('post_id')
            ->constrained()
            ->onDelete('CASCADE');
            $table->float('mastery');
            $table->float('percent');
            $table->date('next_step')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('steps');
    }
};
