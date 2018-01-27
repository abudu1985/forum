<?php

namespace App\Policies;

use App\User;
use App\Tread;
use Illuminate\Auth\Access\HandlesAuthorization;

class TreadPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the tread.
     *
     * @param  \App\User  $user
     * @param  \App\Tread  $tread
     * @return mixed
     */
    public function view(User $user, Tread $tread)
    {
        //
    }

    /**
     * Determine whether the user can create treads.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the tread.
     *
     * @param  \App\User  $user
     * @param  \App\Tread  $tread
     * @return mixed
     */
    public function update(User $user, Tread $tread)
    {
        return $tread->user_id == $user->id;
    }

    /**
     * Determine whether the user can delete the tread.
     *
     * @param  \App\User  $user
     * @param  \App\Tread  $tread
     * @return mixed
     */
    public function delete(User $user, Tread $tread)
    {
        //
    }
}
