<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Aktor; // Pastikan menggunakan namespace model yang benar
use Validator;

class AktorController extends Controller
{
    public function index()
    {
        $aktor = Aktor::latest()->get();
        $response = [
            'success' => true,
            'message' => 'Daftar Aktor',
            'data' => $aktor,
        ];
        return response()->json($response, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_aktor' => 'required',
        ], [
            'nama_aktor.required' => 'Nama Aktor harus diisi.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan isi nama Aktor dengan benar.',
                'errors' => $validator->errors(),
            ], 400);
        }

        try {
            $aktor = new Aktor();
            $aktor->nama_aktor = $request->nama_aktor;
            $aktor->save();

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
        $aktor = Aktor::find($id);
        if ($aktor) {
            return response()->json([
                'success' => true,
                'message' => 'Detail Aktor',
                'data' => $aktor,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $aktor = Aktor::find($id);
        if ($aktor) {
            $aktor->nama_aktor = $request->nama_aktor;
            $aktor->save();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diperbarui',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }
    }

    public function destroy($id)
    {
        try {
            $aktor = Aktor::findOrFail($id);
            $aktor->delete();

            return response()->json([
                'success' => true,
                'message' => 'Aktor berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus Aktor.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
