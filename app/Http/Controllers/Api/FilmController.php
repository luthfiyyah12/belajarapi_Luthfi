<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FilmController extends Controller
{
    use HasFactory;
    public function kategori()
    {
        return $this->belongsTo(kategori::class, 'id_kategori');
    }

    public function gendre()
    {
        return $this->belongsToMany(Gendre::class, 'gendre_film', 'id_film');
    }

    public function aktor()
    {
        return $this->belongsToMany(Aktor::class, 'gendre_film', id_film);
    }
}
