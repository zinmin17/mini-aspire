<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

#Validation Requests
use App\Http\Requests\User\NewUserRequest;
use App\Http\Requests\User\LoginRequest;

#Interface
use App\Repositories\UserRepository\UserInterface;

class UserController extends Controller
{
    public $successStatus = 200;
    protected $userRepo;

    public function __construct(UserInterface $userRepo)
    {
        $this->userRepo = $userRepo;
    }


    /**
     * create new user (client)
     * 
     * @param vchr $name user name
     * @param vchr $email
     * @param vchr $password
     * 
     * @return token (Json)
     */
    public function register(NewUserRequest $request)
    {   
        $new_user = $this->userRepo->createNewUser($request->all());
      
        $success['token'] =  $this->userRepo->getApiToken($new_user->id);

        return response()->json(['success'=>$success], $this->successStatus);
    }


    /**
     * login access
     * 
     * @param vchr $email
     * @param vchr $password
     * 
     * @return token (Json)
     */
    public function login(LoginRequest $request){

        $success['token'] = $this->userRepo->accessLogin($request->email, $request->password);

        return $success['token'] 
            ?  response()->json(['success' => $success], $this->successStatus)
            :  response()->json(['error'=>'Unauthorised'], 401);
    }


    /**
     * get user details
     * 
     * @return user (Json)
     */
    public function details()
    { 
        $user = $this->userRepo->getDetails(Auth::id());

        return response()->json(['success' => $user], $this->successStatus);
    }

}
