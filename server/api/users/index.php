<?php
include('../../app/lib/UsersModel.php');
class Users extends RestServer
{
    private $lib;
    public function __construct()
    {
        $this->lib = new UsersModel();
        $this->run();
    }
    public function getUsers($param)
    {
        $result = $this->lib->checkUsers($param);
        return $result;
    }
    public function postUsers($url,$data)
    {
        $result = $this->lib->addUser($url,$data);
        return $result;
    }
    public function putUsers($url,$data)
    {
        $result = $this->lib->loginUser($url,$data);
        return $result;
    }
}
$cars = new Users();
