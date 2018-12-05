<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * @className UserPolicy
 * @namespace App\Policies
 * @description 用户策略
 */
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


    /**
     * 判断是否管理员。
     *
     * @param  \App\User  $user
     * @return bool
     */
    public function isSuperAdmin(User $user)
    {
        if ($user->role_id === 1) {
            return true;
        }
        return false;
    }

    /**
     * 判断是否管理员或操作员。
     *
     * @param  \App\User  $user
     * @return bool
     */
    public function isAdmin(User $user)
    {
        if ($user->role_id < 3) {
            return true;
        }
        return false;
    }
}
