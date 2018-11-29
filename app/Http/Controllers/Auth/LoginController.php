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
            $linkedAccounts = $socialite_user->linkedAccounts;
            $byond_key = $linkedAccounts[15]['value'];
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
            $ipb_groups = array();
            foreach($socialite_user->secondaryGroups as $ipb_group){
                $ipb_groups[] = $ipb_group->id;
            }
            $ipb_groups[] = $socialite_user->primaryGroup->id;

            $wi_roles = array_unique($user->roles()->get());
            Log::debug('login.permsync - Synchronizing permissions',['user_id' => $user->id, 'wi_roles' => $wi_roles, 'ipb_groups' => $ipb_groups]);

            $groupmap = config("aurora.group_mappings");
            if (is_array($groupmap)) {
                foreach ($groupmap as $wi_role => $ipb_group) {
                    $user_has_ug = in_array($wi_role, $wi_roles);
                    if (in_array($ipb_group, $ipb_groups) && !$user_has_ug) {
                        $wi_roles[] = $wi_role;
                    } elseif (!in_array($ipb_group, $ipb_groups) && $user_has_ug) {
                        if (($key = array_search($wi_role, $wi_roles)) !== false) {
                            unset($wi_role[$key]);
                        }
                    }
                }
                Log::debug('login.permsync.save - Saving new permissions',['user_id' => $user->id, 'wi_roles' => $wi_roles]);
                $user->groups->sync($wi_roles);
            }
        }

        //Login the user and remember them.
        //TODO: Change that to use the auth/refresh token instead (and then look that up at the forums if they come back later and their session is expired)
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
