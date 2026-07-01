<?php

namespace App\Support;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;

class AuditLogger
{
    public static function log(string $action, ?Model $subject = null, string $description = '', array $properties = []): AuditLog
    {
        $properties = array_merge([
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ], $properties);

        return AuditLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject?->getKey(),
            'description' => $description ?: $action,
            'properties' => $properties,
        ]);
    }
}
