<?php

namespace App\models\purchases;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\models\purchases\purchases;
use App\models\product\product;
use App\models\unit\unit;

class purchase_line extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'purchase_lines';

    protected $fillable = [
        'purchase_id',
        'product_id',
        'category_id',
        'unit_id',
        'uom',
        'qty',
        'unit_price',
        'line_total',
        'expiry_date',
        'batch_no',
    ];

    protected $casts = [
        'qty'         => 'decimal:4',
        'unit_price'  => 'decimal:4',
        'line_total'  => 'decimal:4',
        'expiry_date' => 'date',
    ];

    /* Relations */
    public function purchase() { return $this->belongsTo(purchases::class, 'purchase_id'); }
    public function product()  { return $this->belongsTo(product::class,   'product_id'); }
    public function unit()     { return $this->belongsTo(unit::class,      'unit_id'); }
}
