<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('curricula', function (Blueprint $table) {
            $table->string('app_name')->nullable()->after('subdomain_enabled');
        });
    }

    public function down(): void
    {
        Schema::table('curricula', function (Blueprint $table) {
            $table->dropColumn('app_name');
        });
    }
};
