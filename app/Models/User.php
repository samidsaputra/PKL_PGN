<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public $incrementing = false; // UUID tidak auto-increment
    protected $keyType = 'string'; // UUID adalah string

    // Daftar atribut yang dapat diisi secara massal
    protected $fillable = [
        'id', 'name', 'email', 'password', 'role', 'satuan_kerja'
    ];

    // Atribut yang disembunyikan dalam array atau JSON
    protected $hidden = [
        'password', 'remember_token',
    ];

    // Konversi tipe atribut
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Event untuk menetapkan UUID saat model dibuat
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function satuanKerja()
    {
        return $this->belongsTo(SatuanKerja::class, 'satuan_kerja', 'nama');
    }

}
