<?php

namespace App\models\customer;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class customercontact extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $table = 'customer_contacts';

    protected $fillable = [
        'customer_id','name','position','email','phone','preferred_channel','notes'
    ];

    public $translatable = ['name','position'];

    public function customer() { return $this->belongsTo(customer::class, 'customer_id'); }
}
