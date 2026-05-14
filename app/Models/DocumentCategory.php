<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DocumentCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    // تصنيف واحد عنده وثائق متعددة
    public function documents()
    {
        return $this->hasMany(Document::class, 'category_id');
    }

    // فقط التصنيفات المفعلة
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}