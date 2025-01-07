<?php
namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class addBarangController extends Controller
{
    // Menampilkan daftar barang
    public function daftarBarang()
    {
        // Mengambil semua data barang dari database
        $barang = Barang::all();

        return view('admin/addBarang', compact('barang'));
    }


    // Menambahkan barang ke dalam database
    public function createBarang(Request $request)  
    {  
        $validated = $request->validate([  
            'id' => 'required|string|unique:barang,id',
            'Nama_Barang' => 'required|string|max:255',  
            'Kategori' => 'required|string',  
            'Deskripsi' => 'required|string|max:255',  
        ]);  
    
        $barang = Barang::create([  
            'id'=> $validated['id'],
            'Nama_Barang' => $validated['Nama_Barang'],  
            'Kategori' => $validated['Kategori'],  
            'Deskripsi' => $validated['Deskripsi'],  
        ]);  
    
        // Kembalikan response sukses dalam format JSON  
        return response()->json([  
            'success' => true,  
            'message' => 'barang berhasil ditambahkan!',  
            'data' => $barang 
        ]);  
    }
    public function updateBarang(Request $request, $id)
    {
        // Validasi input
        $validated = $request->validate([
            'Nama_Barang' => 'required|string|max:255',
            'Kategori' => 'required|string',
            'Deskripsi' => 'required|string|max:255',
        ]);

        // Cari barang berdasarkan ID dan update
        $barang = Barang::findOrFail($id);
        $barang->update($validated);

        // Redirect dengan pesan sukses
        return redirect()->route('daftar.barang')->with('success', 'Barang berhasil diperbarui!');
    }

    public function deleteBarang($id)
    {
        $barang = Barang::findOrFail($id);

        if ($barang->delete()) {
            return response()->json([
                'success' => true,
                'message' => 'Barang berhasil dihapus!',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus barang.',
            ]);
        }
    }


}
