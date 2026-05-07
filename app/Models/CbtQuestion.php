<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CbtQuestion extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'matching_pairs' => 'array',
        'score_weight' => 'integer',
    ];

    // Question type constants
    const TYPE_PG = 'pilihan_ganda';
    const TYPE_PGK = 'ganda_komplek';
    const TYPE_PENJODOHAN = 'penjodohan';
    const TYPE_ESSAY = 'essay';
    const TYPE_URAIAN = 'uraian';

    public static function typeLabels(): array
    {
        return [
            self::TYPE_PG => 'Pilihan Ganda',
            self::TYPE_PGK => 'Ganda Komplek',
            self::TYPE_PENJODOHAN => 'Penjodohan',
            self::TYPE_ESSAY => 'Essay',
            self::TYPE_URAIAN => 'Uraian',
        ];
    }

    public static function typeBadgeColors(): array
    {
        return [
            self::TYPE_PG => 'primary',
            self::TYPE_PGK => 'purple',
            self::TYPE_PENJODOHAN => 'warning',
            self::TYPE_ESSAY => 'success',
            self::TYPE_URAIAN => 'info',
        ];
    }

    public function getTypeLabelAttribute(): string
    {
        return self::typeLabels()[$this->question_type] ?? $this->question_type;
    }

    public function getTypeBadgeAttribute(): string
    {
        $color = self::typeBadgeColors()[$this->question_type] ?? 'secondary';
        $label = $this->type_label;
        return "<span class=\"badge badge-{$color} px-2 py-1\">{$label}</span>";
    }

    public function isMultipleChoice(): bool
    {
        return $this->question_type === self::TYPE_PG;
    }

    public function isMultipleCorrect(): bool
    {
        return $this->question_type === self::TYPE_PGK;
    }

    public function isMatching(): bool
    {
        return $this->question_type === self::TYPE_PENJODOHAN;
    }

    public function isEssay(): bool
    {
        return $this->question_type === self::TYPE_ESSAY;
    }

    public function isUraian(): bool
    {
        return $this->question_type === self::TYPE_URAIAN;
    }

    public function hasOptions(): bool
    {
        return in_array($this->question_type, [self::TYPE_PG, self::TYPE_PGK]);
    }

    public function bank()
    {
        return $this->belongsTo(CbtBank::class, 'cbt_bank_id');
    }

    public function options()
    {
        return $this->hasMany(CbtOption::class);
    }
}
