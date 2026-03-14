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
        Schema::table('curricula', function (Blueprint $table) {
            $table->string('subdomain')->nullable()->unique()->after('description');
            $table->boolean('subdomain_enabled')->default(false)->after('subdomain');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('curricula', function (Blueprint $table) {
            $table->dropColumn(['subdomain', 'subdomain_enabled']);
        });
    }
};
