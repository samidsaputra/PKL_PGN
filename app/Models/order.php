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
    protected $primaryKey = 'noorder'; // Tetapkan noorder sebagai primary key

    protected $fillable = [
        'noorder',
        'acara',
        'tanggal_acara',
        'tanggal_yang_diharapkan',
        'status',
        'revision_note',
        'user_id'  
    ];

    protected $attributes = [
        'status' => 'pending', 
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

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'noorder', 'noorder');
    }
}
