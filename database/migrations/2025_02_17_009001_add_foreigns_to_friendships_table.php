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
        Schema::table('friendships', function (Blueprint $table) {
            $table
                ->foreign('player1')
                ->references('player_id')
                ->on('players')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table
                ->foreign('player2')
                ->references('player_id')
                ->on('players')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('friendships', function (Blueprint $table) {
            $table->dropForeign(['player1']);
            $table->dropForeign(['player2']);
            $table->dropForeign(['player_id']);
        });
    }
};
