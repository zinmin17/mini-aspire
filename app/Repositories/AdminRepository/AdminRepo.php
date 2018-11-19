<?php
namespace App\Repositories\AdminRepository;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

# Model
use App\Models\Admin;

# Interface
use App\Repositories\AdminRepository\AdminInterface;

class AdminRepo implements AdminInterface
{
    public function __construct()
    {
        //
    }

    public function createNewAdmin($data)
    {
         return Admin::create($data);
    }

    public function getDetails($id)
    {
         return Admin::find($id);
    }

    public function getApiToken($id)
    {    
         $admin = $this->getDetails($id);

         return $admin->createToken('AspireAdmin')->accessToken;
    }

    public function accessLogin($email, $password)
    { 
         $admin = Admin::where('email', $email)->first();
         if($admin){ 
            if(Hash::check($password, $admin->password)){
               
               $token =  $this->getApiToken($admin->id);
               return $token;
            }
         }
    }
}