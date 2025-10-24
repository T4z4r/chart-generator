<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('charts', function (Blueprint $table) {
            $table->string('package_type')->default('chartjs')->after('chart_type');
            $table->json('colors')->nullable()->after('package_type');
        });
    }

    public function down(): void
    {
        Schema::table('charts', function (Blueprint $table) {
            $table->dropColumn(['package_type', 'colors']);
        });
    }
};
