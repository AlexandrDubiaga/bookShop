<?php
//inclube('config.php');
class DB
{
    protected $dBMain;
    public function __construct()
    {
       // $this->dBMain = new PDO('mysql:host=10.3.149.74;dbname=work', 'bti', 'bti');
        $this->dBMain = new PDO('mysql:host=127.0.0.1;dbname=shop', 'mysql', 'mysql');
        if (!$this->dBMain)
        {
            throw new PDOException("Error db");
        }
    }
}

?>
