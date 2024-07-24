<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Film;
use Illuminate\Http\Request;
use Storage;
use Validator;

class FilmController extends Controller
{

    public function index()
    {
        $film = Film::with(['genre','aktor'])->get();
        return response()->json([
            'succes' => true,
            'message' => 'Data Film',
            'data' => $film,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|unique:films',
            'deskripsi' => 'required|string',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'url_video' => 'required|string',
            'id_kategori' => 'required|exists:kategoris,id',
            'genre' => 'required|string',
            'aktor' => 'required|string',
        ]);

        if($validator->fails()) {
            return response()->json([
                'succes' => false,
                'message' => 'Validasi Gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $path = $request->file('foto')->store('public/foto');

            $film = Film::create([
                'judul' => $required->judul,
                'deskripsi' => $request->deskripsi,
                'foto' => $path,
                'url_video' => $request->url_video,
                'id_kategori' => $request->id_kategori,
            ]);

            $film->genre()->sync($request->genre);
            $film->aktor()->sync($request->aktor);

            return response()->json([
                'success' => true,
                'message' => 'Data Berhasil Disimpan',
                'errors' => $film,
            ], 201);
        } catch(\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi Kesalahan',
                'data' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        try{
            $film = Fillm::with(['genre','aktor'])->findOrFail($id);
            return response()->json([
                'success' => true,
                'message' => 'Detail Film',
                'data' => $film,
            ], 200);
        } catch(\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
                'data' => $e->getMessage(),
            ], 404);
        }
    }

    public function update(Request $request,$id)
    {
        $film = Film::findOrFail($id);

        $validator = Validator::make($request->all(),[
            'judul' => 'required|string|unique:films',
            'deskripsi' => 'required|string',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'url_video' => 'required|string',
            'id_kategori' => 'required|exists:kategoris,id',
            'genre' => 'required|array',
            'aktor' => 'required|array',
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi Gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            if($request->hasFile('foto')) {
                // Delete old photo
                Storage::delete($film->foto);

                $path = $request->file('foto')->store('public/foto');
                $film->foto = $path;
            }

            $film->update($request->only(['judul','deskripsi','url_video','id_kategori']));

            if($request->has('genre')) {
                $film->genre()->sync($request->genre);
            }

            if($request->has('aktor')) {
                $film->aktor()->sync($request->aktor);
            }

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diperbarui',
                'data' => $film,
            ], 200);
        } catch(\Exception $e) {
            return response()->json([
                'succes' => false,
                'message' => 'An error occurred',
                'errors' => $e->getMessage,
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $film = Film::findOrFails($id);

            // Delete photo
            Storage::delete($film->foto);

            $film->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data deleted successfully',
                'data' => null,
            ], 204);
        } catch(\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found',
                'errors' => $e->getMessage(),
            ], 404);
        }
    }

    // use HasFactory;
    // public function kategori()
    // {
    //     return $this->belongsTo(kategori::class, 'id_kategori');
    // }

    // public function gendre()
    // {
    //     return $this->belongsToMany(Gendre::class, 'gendre_film', 'id_film');
    // }

    // public function aktor()
    // {
    //     return $this->belongsToMany(Aktor::class, 'gendre_film', id_film);
    // }
}
