<?php
include ('../../app/RestServer.php');
class UsersModel extends RestServer
{
    private $link;

    public function __construct()
    {
        parent::__construct();
        $this->link = $this->db;
    }

    public function checkUsers($param=false)
    {
        $id = $this->link->quote(($param[0]));
        $sql = "SELECT hash, role, discount FROM clients WHERE id=".$id;
        $sth = $this->link->prepare($sql);
        $result = $sth->execute();
        if (false === $result)
        {
           return false;
        }
        $data = $sth->fetchAll(PDO::FETCH_ASSOC);
        if (empty($data))
        {
            return false;
        }
        $str = json_encode($data);
        return $str;
    }
    public function addUser($url,$param)
    {
        $firstName = $this->link->quote(trim($_POST['first_name']));
        $lastName = $this->link->quote(trim($_POST['last_name']));
        $login = $this->link->quote($param['login']);
        $pass = md5(md5(trim($_POST['pass'])));
        $pass = $this->link->quote($pass);
        $sql = "INSERT INTO clients (first_name, last_name, login, pass) VALUES (".$firstName.", ".$lastName.", ".$login.", ".$pass.")";
        $count = $this->link->exec($sql);
        if ($count === false)
        {
            return false;
        }
        return $count;
    }
    public function loginUser($url,$param)
    {

        $pass = md5(md5(trim($param['pass'])));
        $login = $this->link->quote($param['login']);
        $firstName ='';
        $role = '';
        $sql = "SELECT id, first_name, pass, role, active FROM clients WHERE login=".$login;
        $sth = $this->link->prepare($sql);
        $result = $sth->execute();
        if (false === $result)
        {
            return false;
        }
        $data = $sth->fetchAll(PDO::FETCH_ASSOC);
        if (empty($data))
        {
            return false;
        }
        if (is_array($data))
        {
            foreach ($data as $val)
            {
                if ($pass !== $val['pass'])
                {
                    return false;
                }
                else if ($val['active'] == 'no')
                {
                    return false;
                }
                else
                {
                    $id = $this->link->quote($val['id']);
                    $firstName = $val['first_name'];
                    $role = $val['role'];
                }
            }
        }
        $hash = $this->link->quote(md5($this->generateHash(10)));
        $sql = "UPDATE clients SET hash=".$hash." WHERE id=".$id;
        $count = $this->link->exec($sql);
        if ($count === false)
        {
            return false;
        }
        $id = trim($id, "'");
        $hash = trim($hash, "'");
        $arrRes = array('id'=>$id, 'first_name'=>$firstName, 'hash'=>$hash, 'role'=>$role);
        $str = json_encode($arrRes);
        return $str;
    }

    function generateHash($length=6)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789";
        $code = "";
        $clen = strlen($chars) - 1;
        while (strlen($code) < $length)
        {
            $code .= $chars[mt_rand(0,$clen)];
        }
        return $code;
    }
}
