<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('reference')->unique()->nullable();
            $table->text('description')->nullable();
            $table->foreignId('category_id')
                  ->constrained('document_categories')
                  ->restrictOnDelete();
            $table->foreignId('created_by')
                  ->constrained('users')
                  ->restrictOnDelete();
            $table->enum('status', [
                'draft',
                'submitted',
                'under_review',
                'approved',
                'published',
                'disabled',
            ])->default('draft');
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])
                  ->default('normal');
            $table->integer('current_step')->default(0);
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamp('disabled_at')->nullable();
            $table->foreignId('disabled_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};