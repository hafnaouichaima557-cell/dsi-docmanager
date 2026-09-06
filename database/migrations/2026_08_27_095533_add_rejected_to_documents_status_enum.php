<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // SQLite ne supporte pas MODIFY COLUMN
        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            return;
        }

        DB::statement("ALTER TABLE documents MODIFY COLUMN status ENUM(
            'draft',
            'submitted',
            'under_review',
            'approved',
            'rejected',
            'published',
            'disabled'
        ) NOT NULL DEFAULT 'draft'");
    }

    public function down(): void
    {
        // SQLite ne supporte pas MODIFY COLUMN
        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            return;
        }

        DB::statement("ALTER TABLE documents MODIFY COLUMN status ENUM(
            'draft',
            'submitted',
            'under_review',
            'approved',
            'published',
            'disabled'
        ) NOT NULL DEFAULT 'draft'");
    }
};