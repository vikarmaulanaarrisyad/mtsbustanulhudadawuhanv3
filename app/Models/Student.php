<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\AutoNumberTrait;

class Student extends Model
{
    use HasFactory, SoftDeletes, AutoNumberTrait;
    
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($student) {
            if (empty($student->qr_token)) {
                $student->qr_token = \Illuminate\Support\Str::random(40);
            }
        });
    }

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_masuk' => 'date',
        'tanggal_keluar' => 'date',
        'is_active' => 'boolean',
        'cbt_wave' => 'integer',
        'cbt_session' => 'integer',
    ];

    // ==================== RELATIONSHIPS ====================
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function profile()
    {
        return $this->hasOne(StudentProfile::class);
    }

    public function parents()
    {
        return $this->hasOne(StudentParent::class);
    }

    public function histories()
    {
        return $this->hasMany(StudentHistory::class);
    }

    public function classGroup()
    {
        return $this->belongsTo(ClassGroup::class, 'student_class_group_id');
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function studentStatus()
    {
        return $this->belongsTo(StudentStatus::class);
    }

    public function residence()
    {
        return $this->belongsTo(Residence::class, 'student_residence_id');
    }

    public function behaviorLogs()
    {
        return $this->hasMany(BehaviorLog::class);
    }

    public function sppBillings()
    {
        return $this->hasMany(SppBilling::class);
    }

    public function savings()
    {
        return $this->hasOne(StudentSaving::class);
    }

    public function mutabaahLogs()
    {
        return $this->hasMany(MutabaahLog::class);
    }

    public function tahfidzLogs()
    {
        return $this->hasMany(TahfidzLog::class);
    }

    // ==================== ACCESSORS ====================

    public function getKelasLengkapAttribute()
    {
        if ($this->classGroup) {
            return $this->classGroup->class_group . ' ' . $this->classGroup->sub_class_group;
        }
        return '-';
    }

    /**
     * Generate automatic NIS
     * Format: {NSM}{Year2Digit}{Sequence4Digit}
     */
    public static function generateNIS()
    {
        $setting = \App\Models\Setting::first();
        $nsm = $setting->nsm ?? '000000000000'; // Default placeholder
        
        $year = date('y'); 
        $prefix = $nsm . $year;
        
        $lastStudent = self::where('nis', 'like', $prefix . '%')
            ->orderBy('nis', 'desc')
            ->first();
            
        $nextNumber = 1;
        if ($lastStudent) {
            $lastNIS = $lastStudent->nis;
            // Extract the last 4 digits
            $lastSequence = substr($lastNIS, strlen($prefix));
            if (is_numeric($lastSequence)) {
                $nextNumber = (int)$lastSequence + 1;
            }
        }
        
        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
