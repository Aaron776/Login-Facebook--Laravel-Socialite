<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use Illuminate\Support\Facades\Auth;


class SocialiteController extends Controller
{
    public function redirectFacebook(){
        return Socialite::driver('facebook')->redirect();
    }

    public function callbackFacebook(){
        try {
           $userFacebook= Socialite::driver('facebook')->user();
           $findUser=User::where('fb_id',$userFacebook->id)->first();
           if($findUser){
               Auth::login($findUser);
               return redirect()->intended('dashboard');
           }else{
               $newUser=User::create([
                   'name'=>$userFacebook->name,
                    'email'=>$userFacebook->email,
                    'fb_id'=>$userFacebook->id,
                    'password'=>bcrypt('12345678'),
               ]);
               Auth::login($newUser);
               return redirect()->intended('dashboard');
           }
        }catch (Exception $e){
            dd($e->getMessage());
        }
    }
}
