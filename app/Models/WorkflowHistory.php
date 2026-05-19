<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkflowHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_id',
        'user_id',
        'action',
        'from_status',
        'to_status',
        'comment',
        'ip_address',
        'performed_at',
    ];

    protected function casts(): array
    {
        return [
            'performed_at' => 'datetime',
        ];
    }

    // تنتمي لوثيقة — diag 2
    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    // من قام بالعملية — diag 2
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}