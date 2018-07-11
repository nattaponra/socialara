<?php
namespace nattaponra\sociallara;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use nattaponra\sociallara\Providers\FacebookProvider;
use nattaponra\sociallara\Providers\GoogleProvider;


class SocialLara extends Model
{

    protected $fillable = ["user_id","provider","provider_id","email","avatar_path"];

    private $provider;

    private function getProvider($providerName){

        if($providerName == "facebook"){
            return new FacebookProvider();

        }else if($providerName == "google"){
            return new GoogleProvider();

        }

        return null;
    }

    public function redirectLogin($providerName){
        return $this->getProvider($providerName)->redirectLogin();
    }

    public function existUser($email){
        return User::where("email",$email)->count() == 1;
    }

    public function existSocialLara($email,$providerName){
        return $this->where("email",$email)->where("provider",$providerName)->count() == 1;
    }

    public function login($providerName ,Request $request){

        $this->provider = $this->getProvider($providerName);
        $result = $this->provider->callback($request);

        if($result["status"]){

            if($this->existUser($result["email"])){
                $user = User::where("email",$result["email"])->first();

            }else{

                $user = User::create([
                    'name' =>  $result['name'],
                    'email' => $result['email'],
                    'password' => Hash::make($result["token"]),
                ]);
            }

            if($this->existSocialLara($result["email"], $this->provider->providerName())){

            }else{
                $sociallara = new SocialLara();
                $sociallara->user_id     = $user->id;
                $sociallara->provider    = $this->provider->providerName();
                $sociallara->provider_id = $result["id"];
                $sociallara->email       = $result["email"];
                $sociallara->avatar_path = '';
                $sociallara->save();
            }

            Auth::login($user);
            $accessToken = Auth::user()->createToken("App");
            return ["status" => true ,"accessToken" => $accessToken];
        }else{

        }
    }






}