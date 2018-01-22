<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SocialLara extends Model
{
    protected $fillable = ['user_id','platform','platform_id','email','token'];
    public function createAuth(SocialLara $socialLara){
      return  $socialLara->save();
    }


    public function getExistPlatform($platform, $platformId){
         return $this->where("platform",$platform)->where("platform_id",$platformId);
    }
}


