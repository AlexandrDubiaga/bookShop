<?php
include ('../../app/RestServer.php');
class GenresModel extends RestServer
{
    protected $link;
    public function __construct()
    {
        parent::__construct();
        $this->link = $this->db;
    }

    public function getGenres($param=false)
    {
        if($param[0] == "" || $param[0]==".txt" || $param[0]==".json" || $param[0]==".html" || $param[0]==".xml" )
        {
            $sql = 'SELECT id, name FROM genres';
            $sth = $this->link->prepare($sql);
            $result = $sth->execute();
            $data = $sth->fetchAll(PDO::FETCH_ASSOC);
            return $data;
        }
        if ($param !== false && $param[0]!==".txt" && $param[0]!==".json" && $param[0]!==".html" && $param[0]!==".xml")
        {
            $sql = 'SELECT id, name FROM genres';
            $sql .= " WHERE "."id" .'='.$this->link->quote($param[0]).' AND ';

            $sql = substr($sql, 0, -5);
            $sth = $this->link->prepare($sql);
            $result = $sth->execute();
            $data = $sth->fetchAll(PDO::FETCH_ASSOC);
            return $data;
        }
    }
}