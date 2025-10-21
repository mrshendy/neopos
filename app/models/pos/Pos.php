<?php

namespace App\Models\pos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Pos extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $table = 'pos';

    protected $fillable = [
        'sale_no','sale_date','delivery_date','warehouse_id','customer_id',
        'status','notes','subtotal','discount','tax','grand_total','user_id'
    ];

    public $translatable = ['notes'];

    // علاقات
    public function lines()     { return $this->hasMany(PosLine::class, 'pos_id'); }
    public function warehouse() { return $this->belongsTo(\App\Models\inventory\Warehouse::class, 'warehouse_id'); }
    public function customer()  { return $this->belongsTo(\App\Models\crm\Customer::class, 'customer_id'); }
    public function user()      { return $this->belongsTo(\App\Models\User::class, 'user_id'); }
}
