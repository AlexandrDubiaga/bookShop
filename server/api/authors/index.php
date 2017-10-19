<?php
include('../../app/lib/AuthorsModel.php');
class Authors extends RestServer
{
    private $lib;

    public function __construct()
    {
        $this->lib = new AuthorsModel();
        $this->run();
    }

    public function getAuthors($param=false)
    {
                $result = $this->lib->getAuthors($param);
                $result = $this->encodedData($result);
                return $result;
    }
}
$books = new Authors();