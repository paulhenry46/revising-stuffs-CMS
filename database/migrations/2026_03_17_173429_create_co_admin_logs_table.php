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
        Schema::create('co_admin_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('action');          // e.g. 'created_course', 'deleted_post'
            $table->string('subject_type');    // e.g. 'Course', 'Post'
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->string('subject_label');   // human-readable name at the time of action
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('co_admin_logs');
    }
};
