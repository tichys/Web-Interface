<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;


class LoginController extends Controller
{
    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        if (Auth::check()) {
            return redirect("home");
        }

        return Socialite::driver('ipscommunity')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        $socialite_user = Socialite::driver('ipscommunity')->user();

        try {
            $byond_key = $socialite_user['linkedAccounts'][15]['name'];
        } catch (\ErrorException $e) {
            $byond_key = null;
        }

        //Update or create the user details in the db
        $user = User::updateOrCreate(
            ['id' => $socialite_user->id],
            [
                'name' => $socialite_user->nickname,
                'formatted_name' => $socialite_user->formattedName,
                'email' => $socialite_user->email,
                'password' => $socialite_user->token,
                'refresh_token' => $socialite_user->refreshToken,
                'photo_url' => $socialite_user->avatar,
                'linked_accounts' => $socialite_user->linkedAccounts,
                'primary_group' => $socialite_user->primaryGroup,
                'secondary_groups' => $socialite_user->secondaryGroups,
                'byond_key' => $byond_key,
            ]
        );

        //Sync the groups
        if ($user->sync_groups){
            //Check if the primary groupd is in the sync array
            //TODO: Change that to sync the secondary groups aswell once that is fixed in IPB
            $group_data = config("aurora.group_mappings");
            $group_ids = [];
            if(array_key_exists($user->primary_group,config("aurora.group_mappings"))){
                $group_ids[] = $group_data[$user->primary_group];
            }
            $user->roles()->sync($group_ids);
        }

        //Login the user and remember them.
        Auth::login($user, TRUE);

        return redirect('home');
    }

    public function logout()
    {
        Auth::logout();
        return redirect("/");
    }

    public function showLoginForm(){
        return $this->redirectToProvider();
    }
}
