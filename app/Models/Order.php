<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'customer_number',
        'customer_name',
        'fiscal_data',
        'order_date',
        'delivery_address',
        'notes',
        'status',
        'load_photo',
        'delivery_photo',
        'deleted',
        'created_by',
    ];

    protected $casts = [
        'order_date' => 'datetime',
        'deleted'    => 'boolean',
    ];

    // ─── Status constants ─────────────────────────────────────────────────────

    const STATUS_ORDERED    = 'ordered';
    const STATUS_IN_PROCESS = 'in_process';
    const STATUS_IN_ROUTE   = 'in_route';
    const STATUS_DELIVERED  = 'delivered';

    const STATUS_LABELS = [
        'ordered'    => 'Ordenado',
        'in_process' => 'En Proceso',
        'in_route'   => 'En Ruta',
        'delivered'  => 'Entregado',
    ];

    const STATUS_SEQUENCE = [
        'ordered', 'in_process', 'in_route', 'delivered',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    /**
     * The Sales user who created this order.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * All status change log entries for this order.
     */
    public function statusLogs(): HasMany
    {
        return $this->hasMany(OrderStatusLog::class, 'order_id')->orderBy('changed_at');
    }

    /**
     * Latest status log entry.
     */
    public function latestStatusLog(): HasMany
    {
        return $this->hasMany(OrderStatusLog::class, 'order_id')->latest('changed_at');
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('deleted', false);
    }

    public function scopeArchived($query)
    {
        return $query->where('deleted', true);
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }

    /**
     * Returns the next status in the lifecycle sequence, or null if delivered.
     */
    public function getNextStatus(): ?string
    {
        $idx = array_search($this->status, self::STATUS_SEQUENCE);
        return self::STATUS_SEQUENCE[$idx + 1] ?? null;
    }

    /**
     * Whether a given role is allowed to advance this order's status.
     */
    public function canAdvanceStatus(User $user): bool
    {
        return match($this->status) {
            'ordered'    => $user->isRole('Almacen'),
            'in_process' => $user->isRole('Almacen'),
            'in_route'   => $user->isRole('Ruta'),
            default      => false,
        };
    }

    /**
     * Whether the given user can upload photos.
     */
    public function canUploadPhoto(User $user): bool
    {
        return $user->isRole('Ruta') && in_array($this->status, ['in_route', 'delivered']);
    }
}
