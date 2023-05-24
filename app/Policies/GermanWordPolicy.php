<?php

namespace App\Policies;

use App\Models\GermanWord;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class GermanWordPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, GermanWord $germanWord): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, GermanWord $germanWord): bool
    {
        return $germanWord->user()->is($user);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, GermanWord $germanWord): bool
    {
        return $this->update($user, $germanWord);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, GermanWord $germanWord): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, GermanWord $germanWord): bool
    {
        //
    }
}
