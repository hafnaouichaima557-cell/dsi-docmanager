<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->foreignId('validated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('validated_at')->nullable();
            $table->foreignId('final_validated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('final_validated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropForeign(['validated_by']);
            $table->dropForeign(['final_validated_by']);
            $table->dropColumn(['validated_by', 'validated_at', 'final_validated_by', 'final_validated_at']);
        });
    }
};