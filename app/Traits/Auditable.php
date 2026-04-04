<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Request;

trait Auditable
{
    protected static function bootAuditable()
    {
        static::created(function ($model) {
            $model->logAudit('created', null, $model->getAttributes());
        });

        static::updated(function ($model) {
            $model->logAudit('updated', $model->getOriginal(), $model->getChanges());
        });

        static::deleted(function ($model) {
            $model->logAudit('deleted', $model->getOriginal(), null);
        });
    }

    protected function logAudit($event, $old = null, $new = null)
    {
        // Skip logging for specific fields if needed
        $old = $old ? array_diff_key($old, array_flip(['updated_at', 'created_at', 'password'])) : null;
        $new = $new ? array_diff_key($new, array_flip(['updated_at', 'created_at', 'password'])) : null;

        if ($event === 'updated' && empty($new)) {
            return;
        }

        AuditLog::create([
            'user_id' => auth()->id(),
            'event' => $event,
            'auditable_type' => get_class($this),
            'auditable_id' => $this->id,
            'old_values' => $old,
            'new_values' => $new,
            'url' => Request::fullUrl(),
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }
}
