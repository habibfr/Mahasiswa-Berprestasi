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
    Schema::create('hasils', function (Blueprint $table) {
      // $table->string('nim', 11);
      // $table->foreign('nim', 'fk_hasil_to_mahasiswa')
      //     ->references('nim')->on('mahasiswas')->onUpdate('cascade')->onDelete('cascade');
      $table->foreignUuid('mahasiswa_id');
      $table->integer('peringkat');
      $table->decimal('poin', 8, 4);
      $table->enum('status', ['aktif', 'tidak aktif']);
      $table->year('periode');
      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('hasils');
  }
};
