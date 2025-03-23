<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('m_supplier')->insert([
            [
                'nama_supplier' => 'PT. Sentosa Abadi',
                'email' => 'sentosaabadi@example.com',
                'telepon' => '082345678901',
                'alamat' => 'Jl. Sudirman No. 10, Jakarta',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_supplier' => 'PT. Berkah Jaya',
                'email' => 'berkahjaya@example.com',
                'telepon' => '082198765432',
                'alamat' => 'Jl. Diponegoro No. 20, Surabaya',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_supplier' => 'PT. Makmur Sentosa',
                'email' => 'makmursentosa@example.com',
                'telepon' => '083312345678',
                'alamat' => 'Jl. Ahmad Yani No. 55, Kalimantan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_supplier' => 'PT. Indah Logistik',
                'email' => 'indahlogistik@example.com',
                'telepon' => '083398765432',
                'alamat' => 'Jl. Gajah Mada No. 88, Bengkulu',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
