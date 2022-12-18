<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function view(User $user, User $model)
    {
        return  $user->id == $model->id || ($user->type == "EM" && $model->type != "EM");
    }
    public function update(User $user, User $model)
    {
        return $user->id == $model->id || ($user->type == "EM" && $model->type != "EM");
    }
    public function updatePassword(User $user, User $model)
    {
        return $user->id == $model->id;
    }
}
