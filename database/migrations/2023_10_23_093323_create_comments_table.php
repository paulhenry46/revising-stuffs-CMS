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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->boolean('validated');
            $table->string('type');
            $table->string('pseudo')->nullable();
            $table->string('content');
            //Link with the user who created the comment
            $table->foreignId('user_id')
            ->constrained()
            ->onUpdate('restrict')
            ->onDelete('restrict');
            //Link with the post
            $table->foreignId('post_id')
            ->constrained()
            ->onUpdate('restrict')
            ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
