<?php

namespace App\models\product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class unit extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $table = 'units';
    protected $fillable = ['name','code','abbreviation','kind','parent_id','ratio_to_parent','is_default_minor','status'];
    public $translatable = ['name'];

    /* علاقات */
    public function parent() { return $this->belongsTo(self::class, 'parent_id'); }
    public function minors() { return $this->hasMany(self::class, 'parent_id')->where('kind','minor'); }
    public function products(){ return $this->hasMany(product::class, 'unit_id'); }

    /* سكوبات */
    public function scopeMajors($q){ return $q->where('kind','major'); }
    public function scopeMinors($q){ return $q->where('kind','minor'); }
    public function scopeActive($q){ return $q->where('status','active'); }

    /* الصغرى الافتراضية لوحدة كبرى */
    public function defaultMinor()
    {
        return $this->hasOne(self::class, 'parent_id')->where('is_default_minor',true)->where('kind','minor');
    }

    /* عامل التحويل للوحدة الصغرى (كم صغرى في 1 من هذه الوحدة) */
    public function factorToMinor(): float
    {
        if ($this->kind === 'minor') return 1.0;

        $def = $this->defaultMinor()->first();
        if ($def && $def->ratio_to_parent > 0) {
            // ratio_to_parent للصغرى = (كم صغرى في 1 كبرى)
            return (float)$def->ratio_to_parent;
        }
        return 1.0;
    }
}
