<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Intercept attribute checks for pro modules and check subscription expiration.
     */
    public function getAttribute($key)
    {
        $modules = [
            'is_workflow_pro_active' => 'workflow_expires_at',
            'is_announcements_pro_active' => 'announcements_expires_at',
            'is_teachers_pro_active' => 'teachers_expires_at',
            'is_students_pro_active' => 'students_expires_at',
            'is_curriculum_pro_active' => 'curriculum_expires_at',
            'is_achievements_pro_active' => 'achievements_expires_at',
            'is_cbt_pro_active' => 'cbt_expires_at',
            'is_grades_pro_active' => 'grades_expires_at',
            'is_attendance_pro_active' => 'attendance_expires_at',
            'is_mail_pro_active' => 'mail_expires_at',
            'is_savings_pro_active' => 'savings_expires_at',
            'is_bos_pro_active' => 'bos_expires_at',
            'is_ppdb_pro_active' => 'ppdb_expires_at',
            'is_website_pro_active' => 'website_expires_at',
            'is_wa_gateway_pro_active' => 'wa_gateway_expires_at',
            'is_users_pro_active' => 'users_expires_at',
            'is_system_pro_active' => 'system_expires_at',
        ];

        if (array_key_exists($key, $modules)) {
            $rawVal = parent::getAttribute($key);
            if (!$rawVal) {
                return false;
            }

            $expiresKey = $modules[$key];
            $expiresAt = parent::getAttribute($expiresKey);
            if (is_null($expiresAt)) {
                return true; // Lifetime subscription
            }

            return \Carbon\Carbon::parse($expiresAt)->isFuture();
        }

        return parent::getAttribute($key);
    }
}
