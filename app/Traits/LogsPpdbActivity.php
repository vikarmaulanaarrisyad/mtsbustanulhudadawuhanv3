<?php

namespace App\Traits;

use App\Models\PpdbLog;
use Illuminate\Support\Facades\Auth;

trait LogsPpdbActivity
{
    /**
     * Log a PPDB activity.
     *
     * @param int $registrantId
     * @param string $action
     * @param string|null $description
     * @param string|null $newStatus
     * @param string|null $oldStatus
     * @param array $metadata
     * @return void
     */
    public function logPpdbActivity($registrantId, $action, $description = null, $newStatus = null, $oldStatus = null, $metadata = [])
    {
        PpdbLog::create([
            'ppdb_registrant_id' => $registrantId,
            'user_id' => Auth::id(),
            'action' => $action,
            'description' => $description,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'metadata' => array_merge($metadata, [
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]),
        ]);
    }
}
