<?php
namespace App\Repositories\UserRepository;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

# Model
use App\Models\User;

# Interface
use App\Repositories\UserRepository\UserInterface;

class UserRepo implements UserInterface
{
    public function __construct()
    {
        //
    }

    public function createNewUser($data)
    {
         return User::create($data);
    }

    public function getDetails($id)
    {
         return User::find($id);
    }

    public function getApiToken($id)
    {    
         $user = $this->getDetails($id);

         return $user->createToken('Aspire')->accessToken;
    }

    public function accessLogin($email, $password)
    {
         if(Auth::attempt(['email' => $email, 'password' => $password])){

            $token =  $this->getApiToken(Auth::id());

            return $token;
        }
    }


}