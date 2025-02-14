<?php
namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Barang;
use Illuminate\Http\Request;

class addBarangController extends Controller
{
    // Menampilkan daftar barang
    public function daftarBarang()
    {
        $barang = Barang::with('kategori')->get(); // Relasi untuk mengambil nama kategori
        $kategori = Kategori::all(); // Mengambil semua kategori untuk dropdown
        return view('admin.addBarang', compact('barang', 'kategori'));
    }


    public function createBarang(Request $request)  
    {  
        // Validasi input
        $validated = $request->validate([  
            'Nama_Barang' => 'required|string|max:255',  
            'Kategori_Id' => 'required|exists:kategori,id',  // Validasi berdasarkan ID kategori
            'Stok'=> 'required|string|min:0',
            'Deskripsi' => 'required|string|max:255', 
            'Gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif'
        ]);
        
        if ($request->hasFile('Gambar')) {
            $gambarPath = $request->file('Gambar')->store('barang', 'public');  
        } else {
            $gambarPath = null;
        }

        // Ambil nama kategori berdasarkan ID yang dipilih
        $kategori = Kategori::find($validated['Kategori_Id']); // Ambil kategori berdasarkan ID

        // Menambahkan barang baru ke dalam database
        $barang = Barang::create([  
            'Nama_Barang' => $validated['Nama_Barang'],  
            'Kategori_Id' => $validated['Kategori_Id'],  // Simpan ID kategori
            'Kategori' => $kategori->Kategori,  // Simpan nama kategori dari tabel kategori
            'Stok'=> $validated['Stok'],
            'Deskripsi' => $validated['Deskripsi'],
            'Gambar' => $gambarPath
        ]);  
        
        return response()->json([  
            'success' => true,  
            'message' => 'Barang berhasil ditambahkan!',  
            'data' => $barang 
        ]);  
    }

    
    public function updateBarang(Request $request, $id)
    {
        // Validasi input
        $validated = $request->validate([
            'Nama_Barang' => 'required|string|max:255',
            'Kategori_Id' => 'required|exists:kategori,id',  // Validasi kategori berdasarkan ID
            'Stok'=> 'required|string',
            'Deskripsi' => 'required|string|max:255',
        ]);

        // Cari barang berdasarkan ID
        $barang = Barang::findOrFail($id);

        // Ambil nama kategori berdasarkan ID yang dipilih
        $kategori = Kategori::find($validated['Kategori_Id']);

        // Update barang dengan kategori yang baru
        $barang->update([
            'Nama_Barang' => $validated['Nama_Barang'],
            'Kategori_Id' => $validated['Kategori_Id'],  // Update Kategori_Id
            'Kategori' => $kategori->Kategori,  // Update kolom Kategori dengan nama kategori
            'Stok' => $validated['Stok'],
            'Deskripsi' => $validated['Deskripsi']
        ]);

        return response()->json(['success' => true, 'message' => 'Barang berhasil diperbarui.']);
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
