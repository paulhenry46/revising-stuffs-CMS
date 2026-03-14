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
        Schema::create('email_domain_rules', function (Blueprint $table) {
            $table->id();
            $table->string('domain')->unique();
            $table->string('role');
            $table->timestamps();
        });

        Schema::create('email_domain_rule_curriculum', function (Blueprint $table) {
            $table->foreignId('email_domain_rule_id')->constrained('email_domain_rules')->onDelete('cascade');
            $table->foreignId('curriculum_id')->constrained()->onDelete('cascade');
            $table->primary(['email_domain_rule_id', 'curriculum_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_domain_rule_curriculum');
        Schema::dropIfExists('email_domain_rules');
    }
};
