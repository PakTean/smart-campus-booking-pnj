<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;

class LaboranController extends Controller
{
    // Menampilkan semua pengajuan masuk ke dashboard laboran
    public function dashboard()
    {
        // Mengambil data reservasi beserta data user dan facility (Eager Loading)
        $reservations = Reservation::with(['user', 'facility'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('laboran.dashboard', compact('reservations'));
    }

    // Memproses persetujuan atau penolakan (Penilaian: Operasi CRUD - Update)
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'rejection_note' => 'required_if:status,rejected|nullable|string|max:500',
        ]);

        $reservation = Reservation::findOrFail($id);
        
        $reservation->update([
            'status' => $request->status,
            'rejection_note' => $request->status === 'rejected' ? $request->rejection_note : null,
        ]);

        return redirect()->route('laboran.dashboard')->with('success', 'Status peminjaman berhasil diperbarui!');
    }
}