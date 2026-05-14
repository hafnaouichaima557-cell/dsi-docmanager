<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DocumentVersion extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_id',
        'version_number',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'checksum',
        'uploaded_by',
        'change_notes',
        'is_current',
    ];

    protected function casts(): array
    {
        return [
            'is_current' => 'boolean',
        ];
    }

    // النسخة تنتمي لوثيقة — diag 2
    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    // من رفع هذه النسخة — diag 2
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    // حجم الملف بصيغة مقروءة
    public function getFileSizeFormattedAttribute(): string
    {
        $size = $this->file_size;
        if ($size < 1024) return $size . ' B';
        if ($size < 1048576) return round($size / 1024, 2) . ' KB';
        return round($size / 1048576, 2) . ' MB';
    }
}