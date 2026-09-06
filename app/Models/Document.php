<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'reference',
        'description',
        'category_id',
        'created_by',
        'status',
        'priority',
        'department',
        'current_step',
        'submitted_at',
        'approved_at',

        'published_at',
        'disabled_at',
        'disabled_by',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
            'approved_at'  => 'datetime',
            'published_at' => 'datetime',
            'disabled_at'  => 'datetime',
        ];
    }

    // ── Relations ──────────────────────────

    // من خلق الوثيقة
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // تصنيف الوثيقة
    public function category()
    {
        return $this->belongsTo(DocumentCategory::class);
    }

    // نسخ الوثيقة — diag 2
    public function versions()
    {
        return $this->hasMany(DocumentVersion::class)
                    ->orderByDesc('version_number');
    }

    // خطوات الworkflow — diag 2
    public function workflowSteps()
    {
        return $this->hasMany(WorkflowStep::class)
                    ->orderBy('step_order');
    }

    // تاريخ الworkflow
    public function workflowHistories()
    {
        return $this->hasMany(WorkflowHistory::class);
    }

    // من عطل الوثيقة — diag 3
    public function disabledBy()
    {
        return $this->belongsTo(User::class, 'disabled_by');
    }

    // ── Helpers ────────────────────────────

    // diag 2 : الوثيقة في مرحلة المسودة
    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    // diag 5 : الوثيقة منشورة
    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    // diag 3 : الوثيقة معطلة
    public function isDisabled(): bool
    {
        return $this->status === 'disabled';
    }

    // diag 2 : يقدر يتبعث للworkflow
    public function canBeSubmitted(): bool
    {
        return in_array($this->status, ['draft', 'rejected']);
    }

    // diag 5 : يقدر ينشر
    public function canBePublished(): bool
    {
        return $this->status === 'approved';
    }

    // ── Auto Reference ─────────────────────

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($doc) {
            $year        = now()->year;
            $count       = self::whereYear('created_at', $year)->count() + 1;
            $doc->reference = sprintf('DOC-%d-%04d', $year, $count);
        });
    }
}