<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'active'            => 'boolean',
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    /**
     * Orders created by this user (Sales role).
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'created_by');
    }

    /**
     * Status change log entries made by this user.
     */
    public function statusLogs(): HasMany
    {
        return $this->hasMany(OrderStatusLog::class, 'user_id');
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->role === 'Admin';
    }

    public function isRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles);
    }

    /**
     * Human-readable role label (handles Almacen accent).
     */
    public function getRoleLabelAttribute(): string
    {
        return match($this->role) {
            'Almacen' => 'Almacén',
            default   => $this->role,
        };
    }
}
