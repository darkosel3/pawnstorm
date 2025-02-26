<?php

namespace App\Policies;

use App\Models\Player;
use Illuminate\Auth\Access\HandlesAuthorization;

class PlayerPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the player can view any models.
     */
    public function viewAny(Player $player): bool
    {
        return $player->isSuperAdmin();
    }

    /**
     * Determine whether the player can view the model.
     */
    public function view(Player $player, Player $model): bool
    {
        return $player->isSuperAdmin();
    }

    /**
     * Determine whether the player can create models.
     */
    public function create(Player $player): bool
    {
        return $player->isSuperAdmin();
    }

    /**
     * Determine whether the player can update the model.
     */
    public function update(Player $player, Player $model): bool
    {
        return $player->isSuperAdmin();
    }

    /**
     * Determine whether the player can delete the model.
     */
    public function delete(Player $player, Player $model): bool
    {
        return $player->isSuperAdmin();
    }

    /**
     * Determine whether the player can delete multiple instances of the model.
     */
    public function deleteAny(Player $player): bool
    {
        return $player->isSuperAdmin();
    }

    /**
     * Determine whether the player can restore the model.
     */
    public function restore(Player $player, Player $model): bool
    {
        return false;
    }

    /**
     * Determine whether the player can permanently delete the model.
     */
    public function forceDelete(Player $player, Player $model): bool
    {
        return false;
    }
}
