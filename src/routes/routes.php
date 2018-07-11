<?php


use Illuminate\Http\Request;

Route::prefix('sociallara/callback')->group(function () {
    Route::get("{provider_name}",function($provider_name,Request $request){
       return SocialLara::login($provider_name,$request);
    });
});

Route::prefix('sociallara/login')->group(function () {
    Route::get("{provider_name}",function($provider_name){
        return redirect(SocialLara::redirectLogin($provider_name));
    });
});