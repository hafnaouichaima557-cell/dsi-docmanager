<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            if (!Schema::hasColumn('documents', 'department')) {
                $table->string('department')->nullable()->after('category_id');
            }
        });

        // Backfill : pour les documents existants, on prend le département du créateur
       DB::table('documents')
    ->whereNull('department')
    ->update([
        'department' => DB::raw(
            '(SELECT department FROM users WHERE users.id = documents.created_by)'
        ),
    ]);

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            if (Schema::hasColumn('documents', 'department')) {
                $table->dropColumn('department');
            }
        });
    }
};