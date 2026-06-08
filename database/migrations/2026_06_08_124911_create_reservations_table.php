<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            // Membuat foreign key yang terhubung ke tabel users dan facilities
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('facility_id')->constrained()->onDelete('cascade');
            
            $table->dateTime('start_time'); // Waktu mulai pinjam
            $table->dateTime('end_time');   // Waktu selesai pinjam
            $table->text('purpose');        // Keperluan peminjaman
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
            $table->text('rejection_note')->nullable(); // Catatan jika ditolak laboran
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
