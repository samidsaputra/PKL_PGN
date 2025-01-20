<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    // Nama tabel (opsional jika nama tabel berbeda dengan plural nama model)
    protected $table = 'kategori';

    // Kolom yang dapat diisi (mass assignable)
    protected $fillable = ['Kategori'];

    public function barang()
    {
        return $this->hasMany(Barang::class, 'Kategori_Id', 'id');
    }
}
