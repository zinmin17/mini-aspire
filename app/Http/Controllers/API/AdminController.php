<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

#Validation Requests
use App\Http\Requests\Admin\AdminRegisterRequest;
use App\Http\Requests\Admin\AdminLoginRequest;

#Interface
use App\Repositories\AdminRepository\AdminInterface;



class AdminController extends Controller
{
    public $successStatus = 200;
    protected $adminRepo;

    public function __construct(AdminInterface $adminRepo)
    {
        $this->adminRepo = $adminRepo;
    }

    /**
     * create new admin (admin)
     * 
     * @param vchr $name user name
     * @param vchr $email
     * @param vchr $password
     * 
     * @return token (Json)
     */
    public function register(AdminRegisterRequest $request)
    {   
        $new_admin = $this->adminRepo->createNewAdmin($request->all());
      
        $success['token'] =  $this->adminRepo->getApiToken($new_admin->id);

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
    public function login(AdminLoginRequest $request){

        $success['token'] = $this->adminRepo->accessLogin($request->email, $request->password);

        return $success['token'] 
            ?  response()->json(['success' => $success], $this->successStatus)
            :  response()->json(['error'=>'Unauthorised'], 401);
    }
}
