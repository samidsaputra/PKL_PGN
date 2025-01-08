<?php

namespace App\Http\Controllers;

use App\Models\SatuanKerja;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserReg extends Controller
{
    /**
     * Satuan Kerja
     */
    public function daftarSatker()
    {
        $satuan_kerja = SatuanKerja::all();
        return view('admin.SatuanKerja', compact('satuan_kerja'));
    }

    public function createSatker(Request $request)
    {
        $request->validate([
            'nama' => 'required|unique:satuan_kerja,nama|max:255',
            'contact' => 'required|max:15',
            'email' => 'required|email|unique:satuan_kerja,email',
            'PIC' => 'required|max:255',
        ]);

        SatuanKerja::create($request->all());

        return redirect()->route('daftar.satker')
            ->with('success', 'Satuan Kerja berhasil ditambahkan.');
    }

    public function updateSatker(Request $request, $nama)
    {
        $request->validate([
            'contact' => 'required|max:15',
            'email' => 'required|email|unique:satuan_kerja,email,' . $nama . ',nama',
            'PIC' => 'required|max:255',
        ]);

        $satker = SatuanKerja::findOrFail($nama);
        $satker->update($request->only(['contact', 'email', 'PIC']));

        return response()->json(['success' => true, 'message' => 'Satuan Kerja berhasil diperbarui.']);
    }

    public function deleteSatker($nama)
    {
        $satker = SatuanKerja::findOrFail($nama);
        $satker->delete();

        return response()->json(['success' => true, 'message' => 'Satuan Kerja berhasil dihapus.']);
    }


    /**
     * User Registration
     */
    public function UserReg(){
        
        $users = User::all();
        return view('admin.UserRegistration', compact('users'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => ['required', 'string', 'in:Requester,Approver,admin'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->back()->with('success', 'User created successfully');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('auth.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role' => ['required', 'string', 'in:Requester,Approver,admin'],
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => ['required', 'confirmed', Password::defaults()],
            ]);
            
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }
}