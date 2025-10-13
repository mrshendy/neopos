<?php

namespace App\Models\supplier;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class suppliersetting extends Model
{
    use HasFactory;

    protected $table = 'supplier_settings';

    protected $fillable = ['alert_days_before_expiry'];

    protected $casts = ['alert_days_before_expiry'=>'integer'];
}
