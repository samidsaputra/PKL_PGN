<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];
        for ($i = 1; $i <= 25; $i++) {
            $data[] = [
                'id' => 'BRG' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'Nama_Barang' => 'Item ' . $i,
                'Kategori' => $this->getRandomCategory(),
                'Deskripsi' => 'Deskripsi untuk Item ' . $i,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('barang')->insert($data);
    }

    /**
     * Get a random category for the items.
     *
     * @return string
     */
    private function getRandomCategory()
    {
        $categories = ['Elektronik', 'Furniture', 'Aksesoris Komputer', 'Peralatan Rumah', 'Olahraga'];
        return $categories[array_rand($categories)];
    }
}
