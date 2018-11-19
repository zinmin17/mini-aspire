<?php
namespace App\Repositories\AdminRepository;


interface AdminInterface
{

  public function createNewAdmin($data);

  public function getDetails($id);

  public function getApiToken($id);

  public function accessLogin($email, $password);
}