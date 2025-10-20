<?php

namespace App\models\purchases;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

// علاقات
use App\models\inventory\warehouse;
use App\models\supplier\supplier;
use App\models\purchases\purchase_line;

class purchases extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $table = 'purchases';

    public $translatable = ['notes'];

    protected $fillable = [
        'purchase_no',
        'purchase_date',
        'supply_date',
        'warehouse_id',
        'supplier_id',
        'status',
        'notes',
        'subtotal',
        'discount',
        'tax',
        'grand_total',
        'user_id',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'supply_date'   => 'date',
        'subtotal'      => 'decimal:4',
        'discount'      => 'decimal:4',
        'tax'           => 'decimal:4',
        'grand_total'   => 'decimal:4',
    ];

    /* Relations */
    public function warehouse() { return $this->belongsTo(warehouse::class, 'warehouse_id'); }
    public function supplier()  { return $this->belongsTo(supplier::class,  'supplier_id'); }
    public function lines()     { return $this->hasMany(purchase_line::class, 'purchase_id'); }
}
