<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\genre;
use Illuminate\Http\Request;

class genreController extends Controller
{
    public function index()
    {
        $genre = Genre::latest()->get();
        $response = [
            'success' => true, // Ubah 'succes' menjadi 'success'
            'message' => 'Daftar Genre',
            'data' => $genre,
        ];
        return response()->json($response, 200);
    }

    public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'nama_Genre' => 'required',
    ], [
        'nama_Genre.required' => 'Nama Genre$Genre harus diisi.',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Silakan isi nama Genre$Genre dengan benar.',
            'errors' => $validator->errors(),
        ], 400);
    }

    try {
        $genre = new Genre();
        $genre->nama_genre = $request->nama_genre;
        $genre->save();

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan.',
        ], 201);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan saat menyimpan data.',
            'error' => $e->getMessage(),
        ], 500);
    }
}

    public function show($id)
    {
        $genre = Genre::find($id);
        if($genre){
            return response()->json([
                'success' => true,
                'message' => 'Detail Genre', // Ubah 'detail Gendre$Gendre' menjadi 'Detail Gendre$Gendre'
                'data' => $genre,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan', // Ubah 'data tidak ditemukan' menjadi 'Data tidak ditemukan'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $genre = Genre::find($id);
        if($genre){
            $genre->nama_genre = $request->nama_genre;
            $genre->save();
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diperbarui', // Ubah 'data berhasil diperbarui' menjadi 'Data berhasil diperbarui'
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan', // Ubah 'data tidak ditemukan' menjadi 'Data tidak ditemukan'
            ], 404);
        }
    }

    public function destroy($id)
    {
        try {
            $genre = Genre::findOrFail($id);
            $genre->delete();

            return response()->json([
                'success' => true,
                'message' => 'Genre berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus Genre$Genre.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
