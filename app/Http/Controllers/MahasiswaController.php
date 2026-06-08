<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MahasiswaController extends Controller
{
    public function dashboard()
    {
        $facilities = Facility::where('is_available', true)->get();

        $myReservations = Reservation::with('facility')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('mahasiswa.dashboard', compact('facilities', 'myReservations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'facility_id' => 'required|exists:facilities,id',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
            'purpose' => 'required|string|max:1000',
        ]);

        // Algoritma validasi irisan waktu agar jadwal booking tidak tabrakan
        $isBentrok = Reservation::where('facility_id', $request->facility_id)
            ->whereIn('status', ['pending', 'approved'])
            ->where(function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('start_time', '<', $request->end_time)
                      ->where('end_time', '>', $request->start_time);
                });
            })->exists();

        if ($isBentrok) {
            return redirect()->back()->withErrors([
                'booking_error' => 'Fasilitas/Alat tersebut sudah dipesan oleh mahasiswa lain pada rentang waktu yang kamu pilih.'
            ]);
        }

        Reservation::create([
            'user_id' => Auth::id(),
            'facility_id' => $request->facility_id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'purpose' => $request->purpose,
            'status' => 'pending',
        ]);

        return redirect()->route('mahasiswa.dashboard')->with('success', 'Pengajuan peminjaman berhasil dikirim!');
    }
}