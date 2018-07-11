<?php
namespace nattaponra\sociallara\Providers;

use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Illuminate\Http\Request;
use nattaponra\sociallara\Contracts\UserHelper;


class FacebookProvider extends AbstractProvider implements Provider
{
    use UserHelper;
    private $facebook;
    public function __construct()
    {
        $config = [
            'app_id'     => config("sociallara.providers.facebook.client_id",null),
            'app_secret' => config("sociallara.providers.facebook.client_secret",null),
            'default_graph_version' => 'v2.3',
        ];

        try {
            $this->facebook = new  Facebook($config);
        } catch (FacebookSDKException $e) {
            return $e->getMessage();
        }

    }

    public function providerName()
    {
        return "facebook";
    }

    public function redirectLogin(){
        $helper      = $this->facebook->getRedirectLoginHelper();
        $permissions = ['email']; // Optional permissions
        $loginUrl    = $helper->getLoginUrl(config("sociallara.providers.facebook.redirect",null), $permissions);
        return $loginUrl;
    }

    public function callback(Request $request)
    {
        $helper = $this->facebook->getRedirectLoginHelper();

        try {
            $accessToken = $helper->getAccessToken();
            $response       = $this->facebook->get('/me?fields=id,name,email', $accessToken);
            $data           = $response->getDecodedBody();
            $data["status"] =  true;
            $data["token"]  =  $accessToken;
            return $data;

        } catch(FacebookResponseException $e) {

            $data["status"] =  false;
            $data["error"]  =  'Graph returned an error: ' . $e->getMessage();
            return $data;

        } catch(FacebookSDKException $e) {

            $data["status"] =  false;
            $data["error"]  =  'Facebook SDK returned an error: ' . $e->getMessage();
            return $data;

        }
    }
}