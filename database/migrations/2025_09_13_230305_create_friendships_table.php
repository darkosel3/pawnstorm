<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
      Schema::create('friendships', function (Blueprint $table) {
        $table->bigIncrements('friendship_id');
        $table->unsignedBigInteger('player1_id');
        $table->unsignedBigInteger('player2_id');
        $table->enum('status', ['pending', 'accepted', 'declined'])->default('pending');
        $table->timestamps();

        $table->foreign('player1_id')->references('player_id')->on('players')->onDelete('cascade');
        $table->foreign('player2_id')->references('player_id')->on('players')->onDelete('cascade');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('friendships');
    }
};