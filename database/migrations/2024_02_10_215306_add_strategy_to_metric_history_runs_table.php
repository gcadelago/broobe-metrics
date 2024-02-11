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
        Schema::table('metric_history_runs', function (Blueprint $table) {
            $table->foreignId('strategy_id')
                ->after('url')
                ->nullable()
                ->constrained('strategies')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('metric_history_runs', function (Blueprint $table) {
            $table->dropForeign(['strategy_id']);
            $table->dropColumn(['strategy_id']);
        });
    }
};
