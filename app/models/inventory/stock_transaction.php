<?php

namespace App\models\inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

use App\models\inventory\warehouse;
use App\models\inventory\stock_transaction_line;
use App\User; // عندك الملف ضمن app/models/User.php

class stock_transaction extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $table = 'stock_transactions';

    public $translatable = ['notes'];

    protected $fillable = [
        'trx_no','trx_date','type','warehouse_from_id','warehouse_to_id',
        'user_id','ref_type','ref_id','notes','status',
    ];

    protected $casts = [
        'trx_date' => 'date',
    ];

    /** الأسماء الأصلية اللي كنتَ كاتبها */
    public function fromWarehouse() { return $this->belongsTo(warehouse::class, 'warehouse_from_id'); }
    public function toWarehouse()   { return $this->belongsTo(warehouse::class, 'warehouse_to_id'); }

    /** ✅ Aliases مطلوبة من الكومبوننت: */
    public function warehouseFrom() { return $this->belongsTo(warehouse::class, 'warehouse_from_id'); }
    public function warehouseTo()   { return $this->belongsTo(warehouse::class, 'warehouse_to_id'); }

    /** المستخدم الذي أنشأ الحركة */
    public function user()          { return $this->belongsTo(User::class, 'user_id'); }

    /** السطور */
    public function lines()         { return $this->hasMany(stock_transaction_line::class, 'stock_transaction_id'); }
}
