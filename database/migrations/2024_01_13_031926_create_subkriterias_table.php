<?php

use App\Models\Kriteria;
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
        Schema::create('subkriterias', function (Blueprint $table) {
            $table->uuid('id');
            $table->foreignUuid('kriteria_id');
            $table->string('nama_subkriteria');
            $table->float('bobot_normalisasi')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subkriterias');
    }
};
