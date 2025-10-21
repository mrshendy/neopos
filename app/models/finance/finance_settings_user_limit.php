<?php

namespace App\models\finance;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class finance_settings_user_limit extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'finance_settings_user_limits';

    protected $fillable = [
        'finance_settings_id','user_id',
        'daily_count_limit','daily_amount_limit',
        'require_supervisor','active'
    ];

    public function settings() { return $this->belongsTo(finance_settings::class, 'finance_settings_id'); }
    public function user()     { return $this->belongsTo(\App\Models\User::class, 'user_id'); }
}
