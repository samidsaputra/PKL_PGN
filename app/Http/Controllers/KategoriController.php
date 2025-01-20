<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;

class KategoriController extends Controller
{
    public function index()
    {
        $kategori = Kategori::all();
        return view('admin.kategori', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Kategori' => 'required|string|max:255',
        ]);

        Kategori::create([
            'Kategori' => $request->Kategori,
        ]);

        return response()->json(['success' => true, 'message' => 'Kategori berhasil ditambahkan.']);
    }

    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id);
        $kategori->delete();

        return response()->json(['success' => true, 'message' => 'Kategori berhasil dihapus.']);
    }
}
