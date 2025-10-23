<?php

namespace App\models\finance;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;

class finance_settings extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $table = 'finance_settings';

    protected $fillable = [
        'name','branch_id','warehouse_id','currency_id',
        'is_available','allow_negative_stock',
        'return_window_days','require_return_approval','approval_over_amount',
        'receipt_prefix','next_return_number',
        'notes','created_by','updated_by'
    ];

    public $translatable = ['name','notes'];

    // علاقات
    public function branch()    { return $this->belongsTo(\App\models\general\branch::class, 'branch_id'); }
    public function warehouse() { return $this->belongsTo(\App\models\inventory\warehouse::class, 'warehouse_id'); }
    public function userLimits(){ return $this->hasMany(finance_settings_user_limit::class, 'finance_settings_id'); }
}
