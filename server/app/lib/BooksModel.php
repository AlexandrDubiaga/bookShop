<?php
include ('../../app/RestServer.php');
class BooksModel extends RestServer
{
    protected $link;
    public function __construct()
    {
        parent::__construct();
        $this->link = $this->db;
    }

    public function getBooks($param=false)
    {
        if($param[0] == "" || $param[0]==".txt" || $param[0]==".json" || $param[0]==".html" || $param[0]==".xml" ) {
            $sql = 'SELECT b.id as id,'
                . ' b.title,'
                . ' b.price,'
                . ' b.description,'
                . ' b.discount,'
                . ' b.img,'
                . ' a.id as a_id,'
                . ' a.name as a_name,'
                . ' g.id as g_id,'
                . ' g.name as g_name'
                . ' FROM books b '
                . ' LEFT JOIN book_to_author ba'
                . ' ON b.id=ba.id_book'
                . ' LEFT JOIN authors a'
                . ' ON a.id=ba.id_author'
                . ' LEFT JOIN book_to_genre bg'
                . ' ON b.id=bg.id_book'
                . ' LEFT JOIN genres g'
                . ' ON bg.id_genre=g.id'
                . ' WHERE active="yes" ORDER BY b.id';
            $sth = $this->link->prepare($sql);
            $result = $sth->execute();
            $data = $sth->fetchAll(PDO::FETCH_ASSOC);
            return $data;
        }
        if ($param !== false && $param[0]!==".txt" && $param[0]!==".json" && $param[0]!==".html" && $param[0]!==".xml")
        {
                $sql = 'SELECT b.id as id,'
                    . ' b.title,'
                    . ' b.price,'
                    . ' b.description,'
                    . ' b.discount,'
                    . ' b.img,'
                    . ' a.id as a_id,'
                    . ' a.name as a_name,'
                    . ' g.id as g_id,'
                    . ' g.name as g_name'
                    . ' FROM books b '
                    . ' LEFT JOIN book_to_author ba'
                    . ' ON b.id=ba.id_book'
                    . ' LEFT JOIN authors a'
                    . ' ON a.id=ba.id_author'
                    . ' LEFT JOIN book_to_genre bg'
                    . ' ON b.id=bg.id_book'
                    . ' LEFT JOIN genres g'
                    . ' ON bg.id_genre=g.id'
                    . ' WHERE active="yes" ';

                $sql .= 'AND '. 'b.'.'id'.'='.$this->link->quote($param[0]).' AND ';
                $sql = substr($sql, 0, -5);
                $sql .= ' ORDER BY b.id';
                $sth = $this->link->prepare($sql);
                $result = $sth->execute();
                $data = $sth->fetchAll(PDO::FETCH_ASSOC);
                return $data;
            }


    }

}