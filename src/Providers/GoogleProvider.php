<?php
namespace nattaponra\sociallara\Providers;

use Illuminate\Http\Request;

class GoogleProvider implements Provider
{

    public function callback(Request $request)
    {
       echo "Hi Google";
    }
}