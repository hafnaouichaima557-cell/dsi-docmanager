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
        DB::statement('
            UPDATE documents
            JOIN users ON users.id = documents.created_by
            SET documents.department = users.department
            WHERE documents.department IS NULL
        ');
    }

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