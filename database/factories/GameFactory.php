<?php

namespace Database\Factories;

use App\Models\Game;
use App\Models\Player;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class GameFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Game::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'white_player_id' => Player::factory(),
            'black_player_id' => Player::factory(),
            'result_id' => $this->faker->numberBetween(1,3),
            'game_type_id' => $this->faker->numberBetween(1,8),
            'played_at' => $this->faker->dateTime(),
            'PGN' => '[Event "Live Chess"]
[Site "PawnStorm.com"]
[Date "2025.02.26"]
[Round "-"]
[White "darkosel"]
[Black "Dandy280715"]
[Result "1-0"]
[CurrentPosition "2Qk3r/p2P1ppp/4p3/2B2n2/5P2/P7/1P4PP/3K4 b - -"]
[Timezone "UTC"]
[ECO "A03"]
[ECOUrl "https://www.chess.com/openings/Birds-Opening-Dutch-Williams-Gambit"]
[UTCDate "2025.02.26"]
[UTCTime "10:40:13"]
[WhiteElo "1152"]
[BlackElo "1140"]
[TimeControl "60"]
[Termination "darkosel won by checkmate"]
[StartTime "10:40:13"]
[EndDate "2025.02.26"]
[EndTime "10:41:14"]
[Link "https://www.chess.com/analysis/game/live/123309551428?tab=analysis&move=44"]
[WhiteUrl "https://www.chess.com/bundles/web/images/noavatar_l.84a92436.gif"]
[WhiteCountry "231"]
[WhiteTitle ""]
[BlackUrl "https://images.chesscomfiles.com/uploads/v1/user/328112555.9d774e24.50x50o.a525d5bbef6d.jpg"]
[BlackCountry "133"]
[BlackTitle ""]

1. e4 {[%clk 0:00:59.3]} 1... d5 {[%clk 0:00:59.3]} 2. f4 $2 {[%clk 0:00:59.2]}
2... d4 $9 {[%clk 0:00:58.5]} 3. d3 $6 {[%clk 0:00:57.9]} 3... c5 {[%clk
0:00:57.3]} 4. Nf3 {[%clk 0:00:57.6]} 4... Bg4 $6 {[%clk 0:00:55.7]} 5. c3 {[%clk
0:00:57.5]} 5... Bxf3 {[%clk 0:00:54.3]} 6. Qxf3 {[%clk 0:00:57.4]} 6... dxc3 $6
{[%clk 0:00:53.3]} 7. Nxc3 $2 {[%clk 0:00:56.7]} 7... Nc6 {[%clk 0:00:50.6]} 8.
Be3 {[%clk 0:00:55.9]} 8... Qxd3 $4 {[%clk 0:00:48.6]} 9. Bxd3 {[%clk 0:00:54.3]}
9... O-O-O {[%clk 0:00:47.7]} 10. O-O-O {[%clk 0:00:53]} 10... Nb4 {[%clk
0:00:46]} 11. a3 $6 {[%clk 0:00:50.6]} 11... Nxd3+ {[%clk 0:00:45.1]} 12. Rxd3
{[%clk 0:00:49.5]} 12... Rxd3 {[%clk 0:00:44.3]} 13. Kc2 {[%clk 0:00:49.4]}
13... Rd8 {[%clk 0:00:42.9]} 14. Rd1 {[%clk 0:00:48.3]} 14... Rxd1 {[%clk
0:00:41.6]} 15. Kxd1 {[%clk 0:00:48.2]} 15... e6 {[%clk 0:00:41.2]} 16. e5
{[%clk 0:00:46.3]} 16... Be7 {[%clk 0:00:40.3]} 17. Nb5 {[%clk 0:00:44.4]} 17...
Nh6 {[%clk 0:00:38.7]} 18. Nd6+ {[%clk 0:00:43.3]} 18... Bxd6 {[%clk 0:00:36.9]}
19. exd6 {[%clk 0:00:43.2]} 19... Kd7 $6 {[%clk 0:00:35.6]} 20. Bxc5 {[%clk
0:00:42.1]} 20... Nf5 $2 {[%clk 0:00:34.5]} 21. Qxb7+ {[%clk 0:00:40.4]} 21... Ke8
{[%clk 0:00:32.1]} 22. d7+ {[%clk 0:00:37.7]} 22... Kd8 {[%clk 0:00:29.8]} 23.
Qc8# {[%clk 0:00:36.7]} 1-0',
        ];
    }
}
