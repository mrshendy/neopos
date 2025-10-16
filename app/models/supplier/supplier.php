<?php

namespace App\models\supplier;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;

class supplier extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $table = 'suppliers';

    protected $fillable = [
        'code','name','commercial_register','tax_number',
        'supplier_category_id','payment_term_id',
        'country_id','governorate_id','city_id','area_id',
        'status',
    ];

    public $translatable = ['name'];

    protected $casts = [
        'name' => 'array',
    ];

    // العلاقات المرجعية
    public function category()     { return $this->belongsTo(suppliercategory::class,'supplier_category_id'); }
    public function paymentTerm()  { return $this->belongsTo(paymentterm::class,'payment_term_id'); }

    // الجغرافيا (تعتمد موديلاتك الموجودة مسبقًا)
    public function country()      { return $this->belongsTo(\App\models\country::class,'country_id'); }
    public function governorate()  { return $this->belongsTo(\App\models\governorate::class,'governorate_id'); }
    public function city()         { return $this->belongsTo(\App\models\city::class,'city_id'); }
    public function area()         { return $this->belongsTo(\App\models\area::class,'area_id'); }

    // علاقات فرعية
    public function addresses()    { return $this->hasMany(supplieraddress::class); }
    public function contacts()     { return $this->hasMany(suppliercontact::class); }
    public function qualityDocs()  { return $this->hasMany(supplierqualitydoc::class); }
    public function contracts()    { return $this->hasMany(suppliercontract::class); }
    public function discounts()    { return $this->hasMany(supplierdiscount::class); }
    public function blacklists()   { return $this->hasMany(supplierblacklist::class); }
    public function evaluations()  { return $this->hasMany(supplierevaluation::class); }

    // التحقق الموحّد قبل الشراء
    public function isReadyToBuy(\DateTimeInterface $date = null): bool
    {
        $today = $date?->format('Y-m-d') ?? date('Y-m-d');

        $hasActiveContract = $this->contracts()
            ->where('status','active')
            ->where('start_date','<=',$today)
            ->where('end_date','>=',$today)
            ->exists();

        $isBlocked = $this->blacklists()
            ->where('start_date','<=',$today)
            ->where(function($q) use($today){
                $q->whereNull('end_date')->orWhere('end_date','>=',$today);
            })->exists();

        $requiredTypeIds = qualitydoctype::where('required',true)->pluck('id');
        $missingRequired = $requiredTypeIds->isNotEmpty() && !$this->qualityDocs()
            ->whereIn('quality_doc_type_id',$requiredTypeIds)
            ->where('expiry_date','>=',$today)
            ->exists();

        return $this->status === 'active' && $hasActiveContract && !$isBlocked && !$missingRequired;
    }
}
