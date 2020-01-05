<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Provider;
use App\Models\ProviderUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        $getInfo = Socialite::driver($provider)->user();
        $user = self::createUser($getInfo, $provider);
        auth()->login($user);
        return redirect()->route('home', [$user]);
    }

    public function createUser($getInfo, $provider)
    {
        // Get if This User is Register in ProvideUser
        $provider_user = ProviderUser::where('socialite_id', $getInfo->id)->first();
        // Get User By Email
        $user = User::where("email", $getInfo->email)->first();
        // Check Some One
        if (!$provider_user || !$user) {
            // Get Username
            $username = strstr($getInfo->email, '@', true); // . "-" . $getInfo->id; //"username"
            // Check User
            if (!$user) {
                // Make User
                $user = User::create([
                    'name' => $getInfo->name,
                    'username' => $username,
                    'avatar' => $getInfo->avatar,
                    'email' => $getInfo->email,
                    'password' => Hash::make(12345678),
                    'email_verified_at' => now(),
                    'is_verified' => true,
                    'active' => true,
                    'activation_token' => ''
                ]);
            }

            // Get Provider By ID
            $provider_id = Provider::where("provider", $provider)->first()->id;

            // Ser provider Id User
            $provider_user = ProviderUser::create([
                'socialite_id' => $getInfo->id,
                'provider_id' => $provider_id,
                'user_id' => $user->id
            ]);

            // Check Made
            if ($provider_user) {
                return $user;
            } else {
                return null;
            }
        } else {
            $user->update([
                'active' => 1
            ]);
        }
        return $user;
    }
}
