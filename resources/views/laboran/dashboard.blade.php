<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Laboran - Smart Campus</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-slate-50 text-slate-800">

    <!-- Navbar -->
    <nav class="bg-white shadow-sm border-b border-slate-200 px-6 py-4 flex justify-between items-center">
        <div class="flex items-center space-x-4">
            <!-- Logo PNJ Resmi -->
            <img src="{{ asset('img/logo_pnj.jpg') }}" alt="Logo PNJ" class="h-12 w-auto object-contain">
            <div>
                <h1 class="text-xl font-bold text-emerald-600">Smart Campus (Panel Laboran)</h1>
                <p class="text-xs text-slate-500">Log In Sebagai: {{ Auth::user()->name }}</p>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white text-sm px-4 py-2 rounded-md transition">
                Keluar
            </button>
        </form>
    </nav>

    <main class="max-w-7xl mx-auto p-6">
        <h2 class="text-xl font-bold text-slate-700 mb-4">Daftar Pengajuan Peminjaman Fasilitas & Alat</h2>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-4 text-sm font-medium">
                ✅ {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-100 border-b border-slate-200 text-xs font-semibold text-slate-600 uppercase">
                        <th class="p-4">Peminjam</th>
                        <th class="p-4">Fasilitas / Alat</th>
                        <th class="p-4">Waktu Peminjaman</th>
                        <th class="p-4">Keperluan</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @if($reservations->isEmpty())
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400">Belung ada pengajuan masuk.</td>
                        </tr>
                    @else
                        @foreach($reservations as $res)
                            <tr class="hover:bg-slate-50/50">
                                <td class="p-4">
                                    <p class="font-bold text-slate-800">{{ $res->user->name }}</p>
                                    <p class="text-xs text-slate-500">NIM: {{ $res->user->identifier_number }}</p>
                                </td>
                                <td class="p-4 font-medium text-slate-700">{{ $res->facility->name }}</td>
                                <td class="p-4 text-xs text-slate-600 space-y-1">
                                    <p>🛫 {{ date('d M Y, H:i', strtotime($res->start_time)) }}</p>
                                    <p>🛬 {{ date('d M Y, H:i', strtotime($res->end_time)) }}</p>
                                </td>
                                <td class="p-4 text-xs text-slate-500 max-w-xs truncate" title="{{ $res->purpose }}">
                                    "{{ $res->purpose }}"
                                </td>
                                <td class="p-4">
                                    @if($res->status === 'pending')
                                        <span class="text-xs bg-amber-50 text-amber-600 px-2.5 py-1 rounded-full font-semibold">Pending</span>
                                    @elseif($res->status === 'approved')
                                        <span class="text-xs bg-green-50 text-green-600 px-2.5 py-1 rounded-full font-semibold">Disetujui</span>
                                    @elseif($res->status === 'rejected')
                                        <span class="text-xs bg-red-50 text-red-600 px-2.5 py-1 rounded-full font-semibold">Ditolak</span>
                                    @endif
                                </td>
                                <td class="p-4">
                                    @if($res->status === 'pending')
                                        <div class="flex items-center justify-center space-x-2">
                                            <form action="{{ route('laboran.reservations.status', $res->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white text-xs py-1 px-3 rounded font-medium transition">
                                                    Setuju
                                                </button>
                                            </form>

                                            <form action="{{ route('laboran.reservations.status', $res->id) }}" method="POST" class="flex items-center space-x-1">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="rejected">
                                                <input type="text" name="rejection_note" placeholder="Alasan tolak" required class="text-xs px-2 py-1 border border-slate-200 rounded w-28 text-slate-800">
                                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white text-xs py-1 px-2 rounded font-medium transition">
                                                    Tolak
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <p class="text-center text-xs text-slate-400 font-medium">Selesai Diproses</p>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </main>

</body>
</html>