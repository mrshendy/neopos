<?php

namespace App\models\finance;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class finance_movement extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $table = 'finance_movements';

    public $translatable = ['notes'];

    protected $fillable = [
        'finance_settings_id',
        'movement_date',
        'direction',
        'amount',
        'currency_id',
        'method',
        'doc_no',
        'reference',
        'status',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'movement_date' => 'date',
        'amount'        => 'decimal:2',
    ];

    // علاقات
    public function cashbox()
    {
        return $this->belongsTo(finance_settings::class, 'finance_settings_id');
    }
}
