<?php
include('../../app/lib/BooksModel.php');
class Books extends RestServer
{
    private $lib;

    public function __construct()
    {
        $this->lib = new BooksModel();
        $this->run();
    }

    public function getBooks($param=false)
    {
                $result = $this->lib->getBooks($param);
                $result = $this->encodedData($result);
                return $result;
    }
}
$books = new Books();