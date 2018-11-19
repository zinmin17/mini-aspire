<?php
namespace App\Repositories\UserRepository;


interface UserInterface
{

  public function createNewUser($data);

  public function getDetails($id);

  public function getApiToken($id);

  public function accessLogin($email, $password);


}
