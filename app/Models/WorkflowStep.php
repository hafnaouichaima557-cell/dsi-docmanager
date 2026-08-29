<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkflowStep extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_id',
        'step_order',
        'step_name',
        'assigned_to',
        'status',
        'comment',
        'acted_at',
        'deadline',
    ];

    protected function casts(): array
    {
        return [
            'step_order' => 'integer',
            'acted_at'   => 'datetime',
            'deadline'   => 'datetime',
        ];
    }

    // الخطوة تنتمي لوثيقة — diag 2
    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    // المسؤول على هذه الخطوة — diag 2
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // ── Helpers ────────────────────────────

    // diag 2 : الخطوة معلقة
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    // diag 2 : الخطوة في تقدم
    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    // diag 2 : الخطوة مقبولة
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    // diag 2 : الخطوة مرفوضة
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}