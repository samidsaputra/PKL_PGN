<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang'; // Nama tabel di database

    protected $primaryKey = 'id'; // Tentukan kolom id sebagai primary key
    public $incrementing = false; // Nonaktifkan auto-increment
    protected $keyType = 'string'; // Tentukan tipe data primary key sebagai string

    protected $fillable = [
        'id',
        'Nama_Barang',
        'Kategori_Id',
        'Kategori',
        'Deskripsi'
    ];
    // Relasi dengan kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'Kategori_Id', 'id');
    }
    public function getKategoriNamaAttribute()
    {
        return $this->kategori ? $this->kategori->Kategori : $this->Kategori;
    }
}
