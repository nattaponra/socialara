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

    public function providerName()
    {
        return "facebook";
    }

    public function callback(Request $request)
    {
        $accessToken = $request->input("accessToken");

        $fb = new  Facebook([
            'app_id'     => config("socillara.providers.facebook.client_id",null),
            'app_secret' => config("socillara.providers.facebook.client_secret",null),
            'default_graph_version' => 'v2.3',
        ]);

        try {

            $response       = $fb->get('/me?fields=id,name,email', $accessToken);
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