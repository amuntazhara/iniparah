<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('prizes', function (Blueprint $table) {
            $table->id();
            $table->string('hadiah');
            $table->string('deskripsi');
        });

        $data = [
            ['hadiah' => 'Rp.5.000', 'deskripsi' => 'Hadiah Terkecil'],
            ['hadiah' => 'Rp.10.000', 'deskripsi' => 'Hadiah 2'],
            ['hadiah' => 'Rp.15.000', 'deskripsi' => 'Hadiah 3'],
            ['hadiah' => 'Rp.20.000', 'deskripsi' => 'Hadiah 4'],
            ['hadiah' => 'Rp.25.000', 'deskripsi' => 'Hadiah 5'],
            ['hadiah' => 'Rp.50.000', 'deskripsi' => 'Hadiah 6'],
            ['hadiah' => 'Rp.75.000', 'deskripsi' => 'Hadiah 7'],
            ['hadiah' => 'Rp.100.000', 'deskripsi' => 'Jackpot'],
        ];
        DB::table('prizes')->insert($data);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prizes');
    }
};
