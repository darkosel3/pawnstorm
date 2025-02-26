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
        Schema::table('games', function (Blueprint $table) {
            $table
                ->foreign('black_player_id')
                ->references('player_id')
                ->on('players')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table
                ->foreign('white_player_id')
                ->references('player_id')
                ->on('players')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table
                ->foreign('result_id')
                ->references('result_id')
                ->on('results')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table
                ->foreign('game_type_id')
                ->references('game_type_id')
                ->on('game_types')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropForeign(['black_player_id']);
            $table->dropForeign(['white_player_id']);
            $table->dropForeign(['result_id']);
            $table->dropForeign(['game_type_id']);
        });
    }
};
