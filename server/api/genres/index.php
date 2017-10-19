<?php
include('../../app/lib/GenresModel.php');
class Genres extends RestServer
{
    private $lib;

    public function __construct()
    {
        $this->lib = new GenresModel();
        $this->run();
    }

    public function getGenres($param=false)
    {
                $result = $this->lib->getGenres($param);
                $result = $this->encodedData($result);
                return $result;
    }
}
$books = new Genres();