<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('mahasiswas', function (Blueprint $table) {
      // $table->string('nim', 11)->primary();
      $table->uuid('id');
      $table->string('nim', 11);
      $table->string('nama', 50);
      $table->year('angkatan');
      $table->enum('status', ['Aktif', 'Tidak aktif']);
      $table->string('jurusan', 50);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('mahasiswas');
  }
};
