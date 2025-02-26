<?php

namespace App\Policies;

use App\Models\Player;
use App\Models\Friendships;
use Illuminate\Auth\Access\HandlesAuthorization;

class FriendshipsPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the friendships can view any models.
     */
    public function viewAny(Player $player): bool
    {
        return true;
    }

    /**
     * Determine whether the friendships can view the model.
     */
    public function view(Player $player, Friendships $model): bool
    {
        return true;
    }

    /**
     * Determine whether the friendships can create models.
     */
    public function create(Player $player): bool
    {
        return true;
    }

    /**
     * Determine whether the friendships can update the model.
     */
    public function update(Player $player, Friendships $model): bool
    {
        return true;
    }

    /**
     * Determine whether the friendships can delete the model.
     */
    public function delete(Player $player, Friendships $model): bool
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
     * Determine whether the friendships can restore the model.
     */
    public function restore(Player $player, Friendships $model): bool
    {
        return false;
    }

    /**
     * Determine whether the friendships can permanently delete the model.
     */
    public function forceDelete(Player $player, Friendships $model): bool
    {
        return false;
    }
}
