<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SatuanKerjaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('satuan_kerja')->insert([
            [
                'id' => 'CSS',
                'nama' => 'Corporate Support & Services',
                'perusahaan' => 'PGN Solution'
            ]
        ]);
    }
}
