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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('background');
            $table->string('wheel');
            $table->string('pin');
            $table->string('logo;');
            $table->timestamps();
        });

        DB::table('assets')->insert(
            array(
                'background' => 'background0.png',
                'wheel' => 'wheel0.png',
                'pin' => 'pin0.png',
                'logo' => 'logo0.png'
            )
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
