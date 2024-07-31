<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Klub;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class KlubController extends Controller
{
    public function index()
    {
        $klub = Klub::latest()->get();
        return response()->json([
            'success' => true,
            'message' => 'Daftar klub Sepak Bola',
            'data' => $klub,
        ], 200);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'nama_klub' => 'required|unique:klubs',
            'logo' => 'required|image|max:2048',
            'id_liga' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi Gagal',
                'data' => $validate->errors(),
            ], 422);
        }

        try {
            $path = $request->file('logo')->store('public/klub'); //menyimpan gambar
            $klub = new Klub;
            $klub->nama_klub = $request->nama_klub;
            $klub->logo = $path;
            $klub->id_liga = $request->id_liga;
            $klub->save();

            return response()->json([
                'success' => true,
                'message' => 'Data Berhasil Dibuat',
                'data' => $klub,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi Kesalahan',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $klub = Klub::findOrFail($id);
            return response()->json([
                'success' => true,
                'message' => 'Detail klub',
                'data' => $klub,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data Tidak Ada',
                'errors' => $e->getMessage(),
            ], 404);
        }

    }

    public function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'nama_klub' => 'required',
            'logo' => 'required|image|max:2048',
            'id_liga' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi Gagal',
                'data' => $validate->errors(),
            ], 422);
        }

        try {
            $klub = Klub::findOrFail($id);

            if ($request->hasFile('logo')) {
                Storage::delete($klub->logo); //menghapus gambar lama / foto lama
                $path = $request->file('logo')->store('public/klub');
                $klub->logo = $path;
            }

            $klub->nama_klub = $request->nama_klub;
            $klub->id_liga = $request->id_liga;
            $klub->save();

            return response()->json([
                'success' => true,
                'message' => 'Data Berhasil Diubah',
                'data' => $klub,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi Kesalahan',
                'errors' => $e->getMessage(),
            ], 500);
        }

    }

    public function destroy($id)
    {
        try {
            $klub = Klub::findOrFail($id);
            Storage::delete($klub->logo); //menghapus gambar lama / foto lama
            $klub->delete();
            return response()->json([
                'success' => true,
                'message' => 'Data ' . $klub->nama_klub . ' Berhasil Dihapus',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data Tidak Ada',
                'errors' => $e->getMessage(),
            ], 404);
        }

    }
}
