<?php

namespace App\models\product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Translatable\HasTranslations;

/**
 * @property string|null $image_path
 * @property string|null $sku
 * @property string|null $barcode
 * @property array|string $name
 * @property array|string|null $description
 * @property int|null $unit_id
 * @property int|null $category_id
 * @property float|int|null $tax_rate
 * @property float|int|null $opening_stock
 * @property bool $track_batch
 * @property bool $track_serial
 * @property int|null $reorder_level
 * @property string $status
 * @property-read string|null $thumb_url
 */
class product extends Model
{
    use HasFactory, HasTranslations, SoftDeletes;

    protected $table = 'products';

    /**
     * الحقول القابلة للتعبئة
     */
    protected $fillable = [
        'image_path', 'sku', 'barcode', 'name', 'description',
        'unit_id', 'category_id', 'tax_rate', 'opening_stock',
        'track_batch', 'track_serial', 'reorder_level', 'status',
    ];

    /**
     * حقول قابلة للترجمة
     */
    public $translatable = ['name', 'description'];

    /**
     * إظهار الـ accessor ضمن المخرجات
     */
    protected $appends = ['thumb_url'];

    /**
     * تحويل أنواع الحقول
     */
    protected $casts = [
        'name'          => 'array',   // Spatie will still handle accessors
        'description'   => 'array',
        'unit_id'       => 'integer',
        'category_id'   => 'integer',
        'tax_rate'      => 'decimal:2',
        'opening_stock' => 'decimal:2',
        'track_batch'   => 'boolean',
        'track_serial'  => 'boolean',
        'reorder_level' => 'integer',
        'deleted_at'    => 'datetime',
    ];

    /* ================= Relations ================= */

    public function unit()
    {
        return $this->belongsTo(\App\models\product\unit::class);
    }

    public function category()
    {
        return $this->belongsTo(\App\models\product\category::class);
    }

    public function priceItems()
    {
        return $this->hasMany(\App\models\pricing\price_item::class, 'product_id');
    }

    /* ================= Scopes ================= */

    public function scopeActive($q)
    {
        return $q->where('status', 'active');
    }

    /* ================= Accessors ================= */

    /**
     * يعيد رابط الصورة الجاهز للعرض (أولوية: رابط كامل > مسار public/attachments > تخزين disk("public"))
     */
    public function getThumbUrlAttribute(): ?string
    {
        $p = $this->image_path;
        if (!$p) {
            return null;
        }

        // 1) لو رابط كامل أو يبدأ بشرطة مائلة (رابط مطلق داخل الموقع)
        if (Str::startsWith($p, ['http://', 'https://', '/'])) {
            return $p;
        }

        // 2) جرّب مجلد attachments/products داخل public (الأكثر شيوعًا عندك)
        $publicAttachments = 'attachments/products/' . ltrim($p, '/');
        $publicFullPath = public_path($publicAttachments);
        if (is_file($publicFullPath)) {
            return asset($publicAttachments);
        }

        // 3) لو الصورة محفوظة على disk('public') (symlink: public/storage -> storage/app/public)
        //    جرّب كما هي:
        if (Storage::disk('public')->exists($p)) {
            return Storage::url($p); // سيُرجع /storage/...
        }

        //    وجرّب داخل مجلد images أو attachments/products على القرص العام (لو بترفع هناك)
        $candidates = [
            'images/' . ltrim($p, '/'),
            'attachments/products/' . ltrim($p, '/'),
        ];
        foreach ($candidates as $cand) {
            if (Storage::disk('public')->exists($cand)) {
                return Storage::url($cand);
            }
        }

        // مفيش صورة متاحة
        return null;
    }
}
