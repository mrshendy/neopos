<?php

namespace App\models\finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class finance extends Model
{
    use HasFactory, HasTranslations, SoftDeletes;

    protected $table = 'finance';

    protected $fillable = [
        'name', 'branch_id', 'currency_id', 'receipt_prefix', 'next_number',
        'allow_negative', 'status', 'notes', 'gl_account_id', 'created_by', 'updated_by',
    ];

    public $translatable = ['name', 'notes'];

    // علاقات مقترحة — عدّل الـ namespace حسب مشروعك
    public function branch()
    {
        return $this->belongsTo(\App\models\General\Branch::class, 'branch_id');
    }

    public function currency()
    {
        return $this->belongsTo(\App\models\General\Currencies::class, 'currency_id');
    }

    public function glAccount()
    {
        return $this->belongsTo(\App\models\Finance\Account::class, 'gl_account_id');
    }
}
