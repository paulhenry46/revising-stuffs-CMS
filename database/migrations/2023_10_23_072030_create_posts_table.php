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
        Schema::disableForeignKeyConstraints();
        Schema::create('posts', function (Blueprint $table) {
            //General Infos
            $table->id();
            $table->timestamps();
            $table->string('title');
            $table->string('description');
            $table->string('type');
            //Extra Infos
            $table->string('quizlet_url')->nullable();
            $table->boolean('dark_version');
            $table->boolean('cards')->nullable();
            $table->integer('thanks');
            //State Info
            $table->boolean('published');
            $table->boolean('pinned');
            //Internal Infos
            $table->string('slug')->unique();
            $table->string('public');
            //Link with the course
            $table->foreignId('course_id')
            ->constrained()
            ->onUpdate('restrict')
            ->onDelete('restrict');
            //Link with the course's level
            $table->foreignId('level_id')
            ->constrained()
            ->onUpdate('restrict')
            ->onDelete('restrict');
            //Link with the user who created the post
            $table->foreignId('user_id')
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
        Schema::dropIfExists('posts');
    }
};
