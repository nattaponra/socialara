<?php
namespace nattaponra\sociallara\Providers;

use Illuminate\Http\Request;

interface Provider
{
    public function callback(Request $request);
    public function redirectLogin();

}