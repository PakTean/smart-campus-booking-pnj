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
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Contoh: "Laboratorium Komputer", "Oscilloscope Rig A"
            $table->enum('category', ['ruangan', 'alat_elektronik', 'kit_iot']);
            $table->text('description')->nullable();
            $table->boolean('is_available')->default(true); // Status alat rusak/bisa dipinjam
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facilities');
    }
};
