<?php

namespace App\Http\Controllers\Api; // Perbaiki penulisan namespace

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kategori;
use Validator;

class KategoriController extends Controller
{
    public function index()
    {
        $kategori = Kategori::latest()->get();
        $response = [
            'success' => true, // Ubah 'succes' menjadi 'success'
            'message' => 'Daftar Kategori',
            'data' => $kategori,
        ];
        return response()->json($response, 200);
    }

    public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'nama_kategori' => 'required',
    ], [
        'nama_kategori.required' => 'Nama kategori harus diisi.',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Silakan isi nama kategori dengan benar.',
            'errors' => $validator->errors(),
        ], 400);
    }

    try {
        $kategori = new Kategori();
        $kategori->nama_kategori = $request->nama_kategori;
        $kategori->save();

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
        $kategori = Kategori::find($id);
        if($kategori){
            return response()->json([
                'success' => true,
                'message' => 'Detail kategori', // Ubah 'detail kategori' menjadi 'Detail kategori'
                'data' => $kategori,
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
        $kategori = Kategori::find($id);
        if($kategori){
            $kategori->nama_kategori = $request->nama_kategori;
            $kategori->save();
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
            $kategori = Kategori::findOrFail($id);
            $kategori->delete();

            return response()->json([
                'success' => true,
                'message' => 'Kategori berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus kategori.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

}
