<?php
//inclube('config.php');
class DB
{
    protected $dBMain;
    public function __construct()
    {
        $this->dBMain = new PDO('mysql:host=localhost;dbname=user2', 'user2', 'tuser2');
       // $this->dBMain = new PDO('mysql:host=127.0.0.1;dbname=shop', 'mysql', 'mysql');
        if (!$this->dBMain)
        {
            throw new PDOException("Error db");
        }
    }
}

?>
