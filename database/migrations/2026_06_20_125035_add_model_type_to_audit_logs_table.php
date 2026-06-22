<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->string('model_type')->nullable()->after('module');
            $table->unsignedBigInteger('model_id')->nullable()->after('model_type');
            $table->string('user_agent')->nullable()->after('ip_address');
            $table->timestamp('performed_at')->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropColumn(['model_type', 'model_id', 'user_agent', 'performed_at']);
        });
    }
};