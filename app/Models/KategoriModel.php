<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriModel extends Model
{
    use HasFactory;

    protected $table = 'm_kategori'; // Explicitly define the correct table name
    protected $primaryKey = 'kategori_id'; // Define the primary key

    protected $fillable = ['kategori_id', 'kategori_kode', 'kategori_nama']; // Pastikan kolom bisa diisi

    public function users(): HasMany
    {
        return $this->hasMany(UserModel::class, 'kategori_id', 'kategori_id');
    }
}