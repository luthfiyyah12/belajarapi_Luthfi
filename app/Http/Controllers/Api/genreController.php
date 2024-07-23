<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\genre;
use Illuminate\Http\Request;

class genreController extends Controller
{
    public function index()
    {
        $gendre = Gendre::latest()->get();
        $response = [
            'success' => true, // Ubah 'succes' menjadi 'success'
            'message' => 'Daftar Gendre',
            'data' => $gendre,
        ];
        return response()->json($response, 200);
    }

    public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'nama_Gendre' => 'required',
    ], [
        'nama_Gendre.required' => 'Nama Gendre$Gendre harus diisi.',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Silakan isi nama Gendre$Gendre dengan benar.',
            'errors' => $validator->errors(),
        ], 400);
    }

    try {
        $gendre = new Gendre();
        $gendre->nama_gendre = $request->nama_gendre;
        $gendre->save();

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
        $gendre = Gendre::find($id);
        if($gendre){
            return response()->json([
                'success' => true,
                'message' => 'Detail Gendre', // Ubah 'detail Gendre$Gendre' menjadi 'Detail Gendre$Gendre'
                'data' => $gendre,
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
        $gendre = Gendre::find($id);
        if($gendre){
            $gendre->nama_gendre = $request->nama_gendre;
            $gendre->save();
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
            $gendre = Gendre::findOrFail($id);
            $gendre->delete();

            return response()->json([
                'success' => true,
                'message' => 'Gendre berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus Gendre$Gendre.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
