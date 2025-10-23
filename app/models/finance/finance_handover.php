<?php

namespace App\models\finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class finance_handover extends Model
{
    use HasFactory, HasTranslations, SoftDeletes;

    protected $table = 'finance_handovers';

    public $translatable = ['notes'];

    protected $fillable = [
        'finance_settings_id',
        'delivered_by',
        'received_by',
        'handover_date',
        'received_at',
        'doc_no',
        'amount_expected',
        'amount_counted',
        'difference',
        'status',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'handover_date' => 'datetime',
        'received_at' => 'datetime',
        'amount_expected' => 'decimal:2',
        'amount_counted' => 'decimal:2',
        'difference' => 'decimal:2',
    ];

    // علاقات
    public function cashbox()
    {
        return $this->belongsTo(finance_settings::class, 'finance_settings_id');
    }
}
