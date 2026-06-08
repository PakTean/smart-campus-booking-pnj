<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Mahasiswa - Smart Campus</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-slate-50 text-slate-800">

    <!-- Navbar -->
    <nav class="bg-white shadow-sm border-b border-slate-200 px-6 py-4 flex justify-between items-center">
        <div class="flex items-center space-x-4">
            <!-- Logo PNJ Resmi -->
            <img src="{{ asset('img/logo_pnj.jpg') }}" alt="Logo PNJ" class="h-12 w-auto object-contain">
            <div>
                <h1 class="text-xl font-bold text-blue-600">Smart Campus Booking</h1>
                <p class="text-xs text-slate-500">Akses Mahasiswa: {{ Auth::user()->name }} (NIM: {{ Auth::user()->identifier_number }})</p>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white text-sm px-4 py-2 rounded-md transition">
                Keluar
            </button>
        </form>
    </nav>

    <main class="max-w-7xl mx-auto p-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        @if($errors->has('booking_error'))
            <div class="col-span-1 lg:col-span-3 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-2 text-sm font-medium">
                ⚠️ {{ $errors->first('booking_error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="col-span-1 lg:col-span-3 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-2 text-sm font-medium">
                ✅ {{ session('success') }}
            </div>
        @endif
        
        <div class="lg:col-span-2 space-y-4">
            <h2 class="text-lg font-semibold text-slate-700">Fasilitas & Alat Lab Tersedia</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @if(isset($facilities) && count($facilities) > 0)
                    @foreach ($facilities as $facility)
                        <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-200 flex flex-col justify-between">
                            <div>
                                <span class="inline-block text-xs font-semibold px-2 py-1 rounded bg-blue-50 text-blue-600 mb-2 uppercase">
                                    {{ str_replace('_', ' ', $facility->category) }}
                                </span>
                                <h3 class="font-bold text-slate-800 text-base mb-1">{{ $facility->name }}</h3>
                                <p class="text-xs text-slate-500 line-clamp-3 mb-4">{{ $facility->description }}</p>
                            </div>
                            <button onclick="openModal('{{ $facility->id }}', '{{ $facility->name }}')" 
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium py-2 rounded transition">
                                Ajukan Peminjaman
                            </button>
                        </div>
                    @endforeach
                @else
                    <div class="bg-white p-6 rounded-xl border border-slate-200 text-center text-sm text-slate-400 col-span-2">
                        Tidak ada fasilitas yang tersedia saat ini.
                    </div>
                @endif
            </div>
        </div>

        <div class="space-y-4">
            <h2 class="text-lg font-semibold text-slate-700">Riwayat Peminjaman Kamu</h2>

            <div class="space-y-3">
                @if(!isset($myReservations) || count($myReservations) === 0)
                    <div class="bg-white p-6 rounded-xl border border-slate-200 text-center text-sm text-slate-400">
                        Kamu belum pernah mengajukan peminjaman.
                    </div>
                @else
                    @foreach($myReservations as $res)
                        <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-bold text-sm text-slate-800">{{ $res->facility->name }}</h4>
                                
                                @if($res->status === 'pending')
                                    <span class="text-xs bg-amber-50 text-amber-600 px-2 py-0.5 rounded font-medium">Pending</span>
                                @elseif($res->status === 'approved')
                                    <span class="text-xs bg-green-50 text-green-600 px-2 py-0.5 rounded font-medium">Disetujui</span>
                                @elseif($res->status === 'rejected')
                                    <span class="text-xs bg-red-50 text-red-600 px-2 py-0.5 rounded font-medium">Ditolak</span>
                                @else
                                    <span class="text-xs bg-slate-100 text-slate-600 px-2 py-0.5 rounded font-medium">Selesai</span>
                                @endif
                            </div>
                            <p class="text-xs text-slate-500 mb-1">📅 {{ date('d M Y, H:i', strtotime($res->start_time)) }}</p>
                            
                            <p class="text-xs text-slate-600 italic bg-slate-50 p-2 rounded border border-slate-100 mt-2">
                                "{{ $res->purpose }}"
                            </p>

                            @if($res->status === 'rejected' && $res->rejection_note)
                                <div class="mt-2 p-2 bg-red-50 border border-red-100 rounded text-xs text-red-700">
                                    <span class="font-bold">Alasan Ditolak:</span> {{ $res->rejection_note }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </main>

    <div id="bookingModal" class="hidden fixed inset-0 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-xl shadow-lg max-w-md w-full p-6 space-y-4">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <h3 class="text-lg font-bold text-slate-800">Form Peminjaman</h3>
                <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
            </div>

            <form action="{{ route('mahasiswa.reservations.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" id="modal_facility_id" name="facility_id">

                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Nama Fasilitas / Alat</label>
                    <input type="text" id="modal_facility_name" disabled class="w-full bg-slate-100 px-3 py-2 rounded border border-slate-200 text-sm text-slate-600 font-medium">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="start_time" class="block text-xs font-semibold text-slate-500 mb-1">Waktu Mulai</label>
                        <input type="datetime-local" id="start_time" name="start_time" required class="w-full px-3 py-2 rounded border border-slate-200 text-sm text-slate-800">
                    </div>
                    <div>
                        <label for="end_time" class="block text-xs font-semibold text-slate-500 mb-1">Waktu Selesai</label>
                        <input type="datetime-local" id="end_time" name="end_time" required class="w-full px-3 py-2 rounded border border-slate-200 text-sm text-slate-800">
                    </div>
                </div>

                <div>
                    <label for="purpose" class="block text-xs font-semibold text-slate-500 mb-1">Keperluan Penggunaan</label>
                    <textarea id="purpose" name="purpose" rows="3" required placeholder="Contoh: Praktikum Mini Project Telekomunikasi" class="w-full px-3 py-2 rounded border border-slate-200 text-sm text-slate-800"></textarea>
                </div>

                <div class="flex justify-end space-x-2 pt-2 border-t border-slate-100">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-medium rounded transition">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded transition">Kirim Pengajuan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(id, name) {
            document.getElementById('modal_facility_id').value = id;
            document.getElementById('modal_facility_name').value = name;
            document.getElementById('bookingModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('bookingModal').classList.add('hidden');
        }
    </script>
</body>
</html>