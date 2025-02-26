<?php

namespace App\Policies;

use App\Models\Game;
use App\Models\Player;
use Illuminate\Auth\Access\HandlesAuthorization;

class GamePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the game can view any models.
     */
    public function viewAny(Player $player): bool
    {
        return true;
    }

    /**
     * Determine whether the game can view the model.
     */
    public function view(Player $player, Game $model): bool
    {
        return true;
    }

    /**
     * Determine whether the game can create models.
     */
    public function create(Player $player): bool
    {
        return true;
    }

    /**
     * Determine whether the game can update the model.
     */
    public function update(Player $player, Game $model): bool
    {
        return true;
    }

    /**
     * Determine whether the game can delete the model.
     */
    public function delete(Player $player, Game $model): bool
    {
        return true;
    }

    /**
     * Determine whether the player can delete multiple instances of the model.
     */
    public function deleteAny(Player $player): bool
    {
        return true;
    }

    /**
     * Determine whether the game can restore the model.
     */
    public function restore(Player $player, Game $model): bool
    {
        return false;
    }

    /**
     * Determine whether the game can permanently delete the model.
     */
    public function forceDelete(Player $player, Game $model): bool
    {
        return false;
    }
}
