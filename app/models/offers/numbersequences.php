<?php

namespace App\models\offers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

/**
 * جدول تسلسل الأرقام العام للعروض/الكوبونات
 */
class numbersequences extends Model
{
    use HasFactory;

    protected $table = 'number_sequences';

    protected $fillable = [
        'module', 'prefix', 'padding', 'current_no', 'active',
    ];

    /**
     * إرجاع رقم جديد مع إقفال تشاركي آمن.
     */
    public static function next(string $module, string $defaultPrefix = '', int $pad = 4): string
    {
        return DB::transaction(function () use ($module, $defaultPrefix, $pad) {
            /** @var self $seq */
            $seq = self::lockForUpdate()->firstOrCreate(
                ['module' => $module],
                ['prefix' => $defaultPrefix, 'padding' => $pad, 'current_no' => 0, 'active' => true]
            );

            $seq->current_no++;
            $seq->save();

            return $seq->prefix . str_pad((string) $seq->current_no, $seq->padding, '0', STR_PAD_LEFT);
        });
    }
}
