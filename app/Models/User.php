<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'department',
        'avatar',
        'is_active',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
            'last_login_at'     => 'datetime',
        ];
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'created_by');
    }

    public function workflowSteps()
    {
        return $this->hasMany(WorkflowStep::class, 'assigned_to');
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('administrateur');
    }

    public function isResponsable(): bool
    {
        return $this->hasRole('responsable');
    }

    public function isActive(): bool
    {
        return $this->is_active === true;
    }
}