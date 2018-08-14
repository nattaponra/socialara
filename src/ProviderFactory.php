<?php
/**
 * Created by PhpStorm.
 * User: nattaponra
 * Date: 14/8/2561
 * Time: 16:07 น.
 */

namespace nattaponra\sociallara;

use nattaponra\sociallara\Providers\FacebookProvider;
use nattaponra\sociallara\Providers\GoogleProvider;

class ProviderFactory
{
    function getProvider($provider) {

        switch ($provider) {
            case SocialLara::FACEBOOK_PROVIDER:
                return new FacebookProvider();

            case SocialLara::GOOGLE_PROVIDER:
                return new GoogleProvider();

            default:
               return null;

        }

    }

}