<?php
//Copyright (c) 2016 "Werner Maisl"
//
//This file is part of the Aurora Webinterface
//
//The Aurora Webinterface is free software: you can redistribute it and/or modify
//it under the terms of the GNU Affero General Public License as
//published by the Free Software Foundation, either version 3 of the
//License, or (at your option) any later version.
//
//This program is distributed in the hope that it will be useful,
//but WITHOUT ANY WARRANTY; without even the implied warranty of
//MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//GNU Affero General Public License for more details.
//
//You should have received a copy of the GNU Affero General Public License
//along with this program. If not, see <http://www.gnu.org/licenses/>.


namespace App\Services\Auth;

use App\Services\Auth\ForumUserModel as ForumUser;
use App\Services\Auth\PasswordHash;
use Carbon\Carbon;
use Illuminate\Auth\GenericUser;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;

class PhpbbUserProvider implements UserProvider
{

    private $use_remember_me = FALSE;
    private $hash_password = FALSE;

    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed $identifier
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveById($identifier)
    {
        $qry = ForumUser::where('user_id', '=', $identifier);
        if ($qry->count() > 0) {
            $user = $qry->select('user_id', 'username', 'username_clean', 'user_password', 'user_email')->first();
            return $user;
        }
        return NULL;
    }

    /**
     * Retrieve a user by by their unique identifier and "remember me" token.
     *
     * @param  mixed  $identifier
     * @param  string $token
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByToken($identifier, $token)
    {
        if ($this->use_remember_me == TRUE) {
            $qry = ForumUserModel::where('username_clean', '=', strtolower($identifier))->where('remember_token', '=', $token);
            if ($qry->count() > 0) {
                $user = $qry->select('user_id', 'username', 'username_clean', 'user_password', 'user_email')->first();
                return $user;
            }
        }
        return NULL;
    }

    /**
     * Update the "remember me" token for the given user in storage.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable $user
     * @param  string                                     $token
     *
     * @return void
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {
        if ($this->use_remember_me == TRUE) {
            $user->setRememberToken($token);
            $user->save();
        }
    }

    public function retrieveByCredentials(array $credentials)
    {
        // TODO: Implement retrieveByCredentials() method.
        $qry = ForumUser::where('username_clean', '=', strtolower($credentials['username']));
        if ($qry->count() > 0) {
            $user = $qry->select('user_id', 'username', 'username_clean', 'user_password', 'user_email')->first();
            return $user;
        }
        return NULL;
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable $user
     * @param  array                                      $credentials
     *
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        if($this->hash_password)
        {
            $passwordhash = new PasswordHash;
            $pwmatch = $passwordhash->CheckPassword($credentials['password'],$user->getAuthPassword());
        }
        else
        {
            $pwmatch = $credentials['password'] == $user->getAuthPassword();
        }


        if ($user->username_clean == strtolower($credentials['username']) && $pwmatch == true) {
            return TRUE;
        }
        return FALSE;
    }
}