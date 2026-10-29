<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'entity_type',
        'entity_id',
        'description',
        'ip_address',
        'metadata',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Record a new audit log entry.
     */
    public static function record(string $action, string $description, ?string $entityType = null, ?int $entityId = null, ?int $userId = null): self
    {
        return self::create([
            'user_id' => $userId ?? auth()->id(),
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'description' => $description,
            'ip_address' => request()->ip(),
            'metadata' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Alias for record() – supports both styles.
     */
    public static function log(string $action, string $description, ?string $entityType = null, ?int $entityId = null, ?int $userId = null): self
    {
        return self::record($action, $description, $entityType, $entityId, $userId);
    }
}