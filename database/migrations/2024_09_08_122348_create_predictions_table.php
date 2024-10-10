<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('predictions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('race_session_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('p1_id')->constrained('drivers');
            $table->foreignId('p2_id')->constrained('drivers');
            $table->foreignId('p3_id')->constrained('drivers');
            $table->foreignId('p4_id')->constrained('drivers');
            $table->foreignId('p5_id')->constrained('drivers');
            $table->foreignId('p6_id')->constrained('drivers');
            $table->foreignId('p7_id')->constrained('drivers');
            $table->foreignId('p8_id')->constrained('drivers');
            $table->foreignId('p9_id')->constrained('drivers');
            $table->foreignId('p10_id')->constrained('drivers');
            $table->tinyInteger('score')->unsigned()->nullable();
            $table->timestamps();

            $table->unique(['race_session_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('predictions');
    }
};
