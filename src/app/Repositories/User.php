<?php

namespace App\Repositories;

use \App\Models\User as UserModel;

class User extends Repository
{
    public function __construct()
    {
        $this->model = UserModel::class;
    }

    /**
     * @param string $name
     * @param string $email
     * @param string $password
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function createOrGetUser(string $name, string $email, string $password): \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
    {
        return $this
            ->getBuilder()
            ->create([
                'name'    => $name,
                'email'   => $email,
                'password'=> bcrypt($password),
            ]);
    }

    /**
     * @param string $name
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|null
     */
    public function getByName(string $name): \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|null
    {
        return $this
            ->getBuilder()
            ->firstWhere(['name', '=', $name]);
    }

    /**
     * @param string $email
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|null
     */
    public function getByEmail(string $email): \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|null
    {
        return $this
            ->getBuilder()
            ->firstWhere('email', '=', $email);
    }

    public function setEmailVerifiedNow(int $user_id)
    {
        $user = $this
            ->getBuilder()
            ->find($user_id);

        $user->email_verified_at = now();
        $user->save();
    }

    /**
     * Удаление старых пользователей без подтвержденной почты
     *
     * @param int $days По умолчанию 1 день
     */
    public function removeUsersUnconfirmedEmail(int $days = 1)
    {
        $this
            ->getBuilder()
            ->where('created_at', '<', now()->subDays($days))
            ->where('email_verified_at', '=', null)
            ->delete();
    }

    /**
     * Обновление аватара
     * @param int $user_id
     * @param string $avatar_name
     * @param bool $minimizeLoading если true, то будут прописаны загрушки loading.png
     */
    public function updateAvatar(int $user_id, string $avatar_name, bool $minimizeLoading = true)
    {
        $user = $this
            ->getBuilder()
            ->find($user_id);

        $user->avatar = $avatar_name;

        if ($minimizeLoading) {
            $user->avatar_x32 = '/x32/loading.png';
            $user->avatar_x64 = '/x64/loading.png';
            $user->avatar_x128 = '/x128/loading.png';
        }

        $user->save();
    }
}
