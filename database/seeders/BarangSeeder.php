<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'barang_id'   => 1,
                'kategori_id' => 1,
                'barang_kode' => 'BRG001',
                'barang_nama' => 'Laptop Asus',
                'harga_beli'  => 8000000,
                'harga_jual'  => 9000000,
            ],
            [
                'barang_id'   => 2,
                'kategori_id' => 1,
                'barang_kode' => 'BRG002',
                'barang_nama' => 'Smartphone Samsung',
                'harga_beli'  => 5000000,
                'harga_jual'  => 5500000,
            ],
            [
                'barang_id'   => 3,
                'kategori_id' => 2,
                'barang_kode' => 'BRG003',
                'barang_nama' => 'Kaos Polos',
                'harga_beli'  => 50000,
                'harga_jual'  => 75000,
            ],
            [
                'barang_id'   => 4,
                'kategori_id' => 2,
                'barang_kode' => 'BRG004',
                'barang_nama' => 'Celana Jeans',
                'harga_beli'  => 150000,
                'harga_jual'  => 200000,
            ],
            [
                'barang_id'   => 5,
                'kategori_id' => 3,
                'barang_kode' => 'BRG005',
                'barang_nama' => 'Mie Instan',
                'harga_beli'  => 2500,
                'harga_jual'  => 3000,
            ],
            [
                'barang_id'   => 6,
                'kategori_id' => 3,
                'barang_kode' => 'BRG006',
                'barang_nama' => 'Snack Cokelat',
                'harga_beli'  => 5000,
                'harga_jual'  => 7000,
            ],
            [
                'barang_id'   => 7,
                'kategori_id' => 4,
                'barang_kode' => 'BRG007',
                'barang_nama' => 'Susu Kotak',
                'harga_beli'  => 6000,
                'harga_jual'  => 8000,
            ],
            [
                'barang_id'   => 8,
                'kategori_id' => 4,
                'barang_kode' => 'BRG008',
                'barang_nama' => 'Air Mineral 1L',
                'harga_beli'  => 3000,
                'harga_jual'  => 4000,
            ],
            [
                'barang_id'   => 9,
                'kategori_id' => 5,
                'barang_kode' => 'BRG009',
                'barang_nama' => 'Buku Tulis',
                'harga_beli'  => 10000,
                'harga_jual'  => 15000,
            ],
            [
                'barang_id'   => 10,
                'kategori_id' => 5,
                'barang_kode' => 'BRG010',
                'barang_nama' => 'Pulpen Biru',
                'harga_beli'  => 2000,
                'harga_jual'  => 4000,
            ],
        ];
        DB::table('m_barang')->insert($data);
    }
}
