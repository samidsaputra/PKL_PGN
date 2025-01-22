<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    public $incrementing = false; // UUID tidak auto-increment
    protected $keyType = 'string'; // UUID adalah string

    protected $fillable = [
        'noorder',
        'acara',
        'tanggal_acara',
        'tanggal_yang_diharapkan',
        'status',
        'penerima'
    ];

    protected $attributes = [
        'status' => 'pending', 
        'penerima' => 'saya',// Default status
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->noorder)) {
                $model->noorder = (string) Str::uuid();
            }
        });
    }
}
