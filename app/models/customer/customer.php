<?php

namespace App\Models\customer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class customer extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;
 protected $table = 'customer'; 
    protected $primaryKey = 'id';

    protected $fillable = [
        'code','type','name','address','city','country',
        'email','phone','mobile','tax_no','commercial_no',
        'status','notes','user_id',
    ];

    public $translatable = ['name','address','city','notes'];

    protected $casts = [
        'name'    => 'array',
        'address' => 'array',
        'city'    => 'array',
        'notes'   => 'array',
    ];

    // helpers
    public function getDisplayNameAttribute(): string
    {
        $loc = app()->getLocale();
        $n = $this->getTranslation('name', $loc) ?: ($this->name['ar'] ?? $this->name['en'] ?? '');
        return (string) $n;
    }
}
