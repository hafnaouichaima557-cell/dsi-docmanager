<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'module',
        'model_type',
        'model_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'description',
        'performed_at',
    ];

    protected function casts(): array
    {
        return [
            'old_values'    => 'array',  // JSON تلقائي
            'new_values'    => 'array',  // JSON تلقائي
            'performed_at'  => 'datetime',
        ];
    }

    // من قام بالعملية — diag 3, 6, 7, 8, 9
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ── Helper لتسجيل العمليات ─────────────

    // diag 3, 6, 7, 8 : تسجيل تلقائي
    public static function log(
        string $action,
        string $module,
        string $description,
        $model = null,
        array $oldValues = [],
        array $newValues = []
    ): void {
        self::create([
            'user_id'      => auth()->id(),
            'action'       => $action,
            'module'       => $module,
            'model_type'   => $model ? get_class($model) : null,
            'model_id'     => $model?->id,
            'old_values'   => $oldValues,
            'new_values'   => $newValues,
            'ip_address'   => request()->ip(),
            'user_agent'   => request()->userAgent(),
            'description'  => $description,
            'performed_at' => now(),
        ]);
    }
}