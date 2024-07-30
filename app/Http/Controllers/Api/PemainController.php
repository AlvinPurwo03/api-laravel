<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pemain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PemainController extends Controller
{
    public function index()
    {
        $pemain = Pemain::latest()->get();
        return response()->json([
            'success' => true,
            'message' => 'Daftar pemain Sepak Bola',
            'data' => $pemain,
        ], 200);

    }
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'nama_pemain' => 'required|unique:pemains',
            'tgl_lahir' => 'required',
            'harga_pasar' => 'required',
            'posisi' => 'required|in:gk,df,mf,fw',
            'negara' => 'required',
            'foto' => 'required|image|max:2048',
            'id_klub' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi Gagal',
                'data' => $validate->errors(),
            ], 422);
        }

        try {
            $path = $request->file('foto')->store('public/pemain'); //menyimpan gambar
            $pemain = new Pemain;
            $pemain->nama_pemain = $request->nama_pemain;
            $pemain->tgl_lahir = $request->tgl_lahir;
            $pemain->harga_pasar = $request->harga_pasar;
            $pemain->posisi = $request->posisi;
            $pemain->negara = $request->negara;
            $pemain->foto = $path;
            $pemain->id_klub = $request->id_klub;
            $pemain->save();

            return response()->json([
                'success' => true,
                'message' => 'Data Berhasil Dibuat',
                'data' => $pemain,
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
            $pemain = Pemain::findOrFail($id);
            return response()->json([
                'success' => true,
                'message' => 'Detail pemain',
                'data' => $pemain,
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
            'nama_pemain' => 'required',
            'tgl_lahir' => 'required',
            'harga_pasar' => 'required|numeric',
            'posisi' => 'required|in:gk,df,mf,fw',
            'negara' => 'required',
            'foto' => 'required|image|max:2048',
            'id_klub' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi Gagal',
                'data' => $validate->errors(),
            ], 422);
        }

        try {
            $path = $request->file('foto')->store('public/pemain'); //menyimpan gambar
            $pemain = Pemain::findOrFail($id);
            $pemain->nama_pemain = $request->nama_pemain;
            $pemain->tgl_lahir = $request->tgl_lahir;
            $pemain->harga_pasar = $request->harga_pasar;
            $pemain->posisi = $request->posisi;
            $pemain->negara = $request->negara;
            $pemain->foto = $path;
            $pemain->id_klub = $request->id_klub;
            $pemain->save();

            return response()->json([
                'success' => true,
                'message' => 'Data Berhasil Diubah',
                'data' => $pemain,
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
            $pemain = Pemain::findOrFail($id);
            Storage::delete($pemain->foto); //menghapus gambar lama / foto lama
            $pemain->delete();
            return response()->json([
                'success' => true,
                'message' => 'Data ' . $pemain->nama_pemain . ' Berhasil Dihapus',
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
