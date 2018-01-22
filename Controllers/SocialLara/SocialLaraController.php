<?php

namespace App\Http\Controllers\Auth\SocialLara;

use App\SocialLara;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialLaraController extends Controller
{
    private $socailLaraModel;
    private $userModel;

    public function __construct(SocialLara $socialLara,User $user)
    {
       $this->socailLaraModel = $socialLara;
       $this->userModel       = $user;

    }

    public function facebookAuthen()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function facebookCallback()
    {
        $facebook      = Socialite::driver('facebook')->user();
        $userData      = $facebook->user;
        $results       = $this->socailLaraModel->getExistPlatform("facebook",$userData["id"]);
        $users         = $this->userModel->where("email",$userData["email"]);
        $finalUser     = $users;
        $avatarContent = file_get_contents($facebook->avatar_original);

        if($results->count()== 0){

            if($users->count() == 0){
                //create new user
                $user                   = new User();
                $user->name             = $userData["name"];
                $user->email            = $userData["email"];
                $user->password         = bcrypt($this->randomPassword(30));
                $user->avatar_content   = $avatarContent;
                $user->avatar_extension = "png";
                $user->save();
                $user_id                = $user->id;
                $finalUser              = $user;

            }else{
                $user_id = $users->first()->id;
            }

            $socialAuth                   = new SocialLara();
            $socialAuth->user_id          = $user_id;
            $socialAuth->platform         = 'facebook';
            $socialAuth->platform_id      = $userData["id"];
            $socialAuth->email            = $userData["email"];
            $socialAuth->token            = $facebook->token;
            $socialAuth->platform_id      = $userData["id"];
            $socialAuth->avatar_content   = $avatarContent;
            $socialAuth->avatar_extension = "png";
            $this->socailLaraModel->createAuth($socialAuth);

        }else{
            $user_id     = $results->first()->user_id;
            $finalUser   = $this->userModel->where("id",$user_id);
        }

        //Update avatar
        $finalUser->update(["avatar_content" => $avatarContent ,"avatar_extension" => "png"]);

        if (Auth::loginUsingId($finalUser->first()->id)) {
            return redirect("/");
        }

    }

    public function googleAuthen()
    {
        return Socialite::driver('google')->redirect();
    }

    public function googleCallback()
    {
        $google        = Socialite::driver('google')->user();
        $userData      = $google->user;
        $userEmail     = "";
        
        foreach ($userData["emails"] as $email) {
            $userEmail = $email["value"];
            break;
        }

        $results       = $this->socailLaraModel->getExistPlatform("google",$google->id);
        $users         = $this->userModel->where("email",$userEmail);
        $finalUser     = $users;
        $avatarContent = file_get_contents($google->avatar_original);

        if($results->count()== 0){

            if($users->count() == 0){
                //create new user
                $user                   = new User();
                $user->name             = $userData["displayName"];
                $user->email            = $userEmail;
                $user->password         = bcrypt($this->randomPassword(30));
                $user->avatar_content   = $avatarContent;
                $user->avatar_extension = "png";
                $user->save();
                $user_id                = $user->id;
                $finalUser              = $user;

            }else{
                $user_id = $users->first()->id;
            }

            $socialAuth                   = new SocialLara();
            $socialAuth->user_id          = $user_id;
            $socialAuth->platform         = 'google';
            $socialAuth->email            = $userEmail;
            $socialAuth->token            = $google->token;
            $socialAuth->platform_id      = $google->id;
            $socialAuth->avatar_content   = $avatarContent;
            $socialAuth->avatar_extension = "png";

            $this->socailLaraModel->createAuth($socialAuth);

        }else{
            $user_id     = $results->first()->user_id;
            $finalUser   = $this->userModel->where("email",$userEmail)->where("id",$user_id);

        }

        //Update avatar
        $finalUser->update(["avatar_content" => $avatarContent ,"avatar_extension" => "png"]);


        if (Auth::loginUsingId($finalUser->first()->id)) {
            return redirect("/");
        }
    }


    private function randomPassword($length) {

        $alphabet    = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass        = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache

        for ($i = 0; $i < $length; $i++) {
            $n      = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

}
