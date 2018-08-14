<?php
namespace nattaponra\sociallara;

use Illuminate\Http\Request;

class SocialLara
{
    const FACEBOOK_PROVIDER = 'facebook';
    const GOOGLE_PROVIDER   = 'google';
    private $providerFactory;

    public function __construct(ProviderFactory $factory)
    {
        $this->providerFactory = $factory;
    }

    public function login($providerName,Request $request){
        $provider = $this->providerFactory->getProvider($providerName);
        $data = $provider->callback($request);

       return $data;
    }


    public function redirectLogin($providerName){
        $provider = $this->providerFactory->getProvider($providerName);
        return $provider->redirectLogin();
    }

    public function test(){
        $config = [
            'app_id'     => config("sociallara.providers.facebook.client_id",null),
            'app_secret' => config("sociallara.providers.facebook.client_secret",null),
            'default_graph_version' => 'v2.3',
        ];

        dd($config);

    }
}