<?php

namespace App\models\finance;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class finance_receipt extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $table = 'finance_receipts';

    public $translatable = ['notes','cancel_reason'];

    protected $fillable = [
        'finance_settings_id',
        'receipt_date',
        'doc_no',
        'reference',
        'method',
        'amount_total',
        'return_amount',
        'status',
        'cancel_reason',
        'canceled_by',
        'canceled_at',
        'notes',
        'currency_id',
        'customer_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'receipt_date'  => 'datetime',
        'canceled_at'   => 'datetime',
        'amount_total'  => 'decimal:2',
        'return_amount' => 'decimal:2',
    ];

    // علاقات
    public function cashbox(){ return $this->belongsTo(finance_settings::class, 'finance_settings_id'); }
}
