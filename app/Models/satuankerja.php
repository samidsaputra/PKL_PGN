<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SatuanKerja extends Model
{
    protected $table = 'satuan_kerja';
    
    // Use 'nama' as the primary key
    protected $primaryKey = 'nama';
    
    // Since we're using a string as primary key
    public $incrementing = false;
    protected $keyType = 'string';
    
    public $timestamps = false;
    
    protected $fillable = [
        'id',
        'nama',
        'perusahaan',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'satuan_kerja', 'nama');
    }

}