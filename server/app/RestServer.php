<?php
include ('DB.php');
class RestServer extends DB
{
     protected $cookies;
    protected $reqMethod;
    protected $url;
    protected $param;
    protected $encode;
    protected $db;
    protected $data;

    public function __construct()
    {
        parent::__construct();
        $this->db = $this->dBMain;
    }
    
    public function run()
    {
      $this->url = list($s, $user, $REST, $server, $api, $dir, $index, $class, $data) = explode("/", $_SERVER['REQUEST_URI'], 7);
        $this->reqMethod = $_SERVER['REQUEST_METHOD'];
        $this->encode = $this->url[6];
        header('Access-Control-Allow-Origin:*');
        header('Access-Control-Allow-Methods: PUT, POST, GET, DELETE');
        header('Access-Control-Allow-Headers: Authorization, Content-Type');
        switch ($this->reqMethod)
        {
                case 'GET':
                $this->setMethod('get'.ucfirst($dir), explode('/', $index));
                break;
                case 'DELETE':
                    $this->param = $this->setMethod('delete'.ucfirst($dir), explode('/',  $index));
                break;
                case 'POST':
                    $putV = (explode('&', file_get_contents("php://input")));
                    $put = array();
                    foreach ($putV as $value)
                    {
                        $keyValue = explode('=', $value);
                        $put[$keyValue[0]]=$keyValue[1];
                    }
                $this->setMethod('post'.ucfirst($dir), explode('/', $index),$put);
                break;
                case 'PUT':
                    $put = json_decode(file_get_contents("php://input"), true);
                    $this->setMethod('put'.ucfirst($dir), explode('/', $index), $put);
                    break;
            case 'OPTIONS':
                header('Access-Control-Allow-Methods: PUT');
                header('Access-Control-Allow-Origin:*');
                exit();
                break;
        }
        
        
    }

    public function setMethod($classMethod, $param=false, $outPutt = false)
    {

                echo $this->$classMethod($param,$outPutt);
      
    }
     protected function encodedData($data)
    {
        switch ($this->encode)
        {
            case '.json':
               return  $this->convertToJson($data);
                break;
            case '.txt':
                return  $this->convertToTxt($data);
                break;
            case '.html':
                return  $this->convertToHtml($data);
                break;
            case '.xml':
                 return $this->convertToXml($data,'root');
                break;
            default:
                 return  $this->convertToJson($data);
        }
    }
    
    public function convertToJson($data)
    {
        //header('Content-Type: application/json');
       echo json_encode($data);
    }
     public function convertToTxt($data)
    {
         header("Content-Type: text/javascript");
         print_r($data);
        
    }
     public function convertToHtml($data)
    {
            header("Content-type: text/html\n");
            $out = '<li>';
            foreach($data as $v){
                if(is_array($v)){
                    $out .= '<ul>'.recurseTree($v).'</ul>';
                }else{
                    $out .= $v;
            }
            }
           $out = '</li>';
         return '<ul>'.convertToHtml($data).'</ul>';
    }  
    
    public function convertToXml($data, $root)
    {
        header("Content-type: text/xml");
        $xml = new SimpleXMLElement( '<' . $root . '/>' );
        foreach( $data as $element=>$value )
        {
            $element = is_numeric( $element ) ? $root : $element;
            if ( is_array( $value ) )
            {
                if ( $this->isNumericKeys( $value ) ) {
                    $this->array2xml( $value, $xml, $element );
                } else
                {
                    $elementS = $xml->addChild( $element );
                    $this->array2xml( $value, $elementS, $element );
                }
            } else
            {
                $xml->addChild( $element, $value );
            }
        }
        return $xml->asXML();
}
 
    function array2xml( $array, &$xml, $root )
    {
        foreach( $array as $element=>$value )
        {
        $element = is_numeric( $element ) ? $root : $element;
        if ( is_array( $value ) ) {
            if (  $this->isNumericKeys( $value ) )
            {
                $this->array2xml( $value, $xml, $element );
            } else
            {
                $elementS = $xml->addChild( $element );
                $this->array2xml( $value, $elementS, $element );
            }
        } else
        {
            if ( preg_match( '/^@/', $element) )
            {
                $xml->addAttribute( str_replace( '@', '', $element ), $value );
            } else
                {
                $xml->addChild( $element, $value );
            }
        }
    }
}
 
    function isNumericKeys( $array )
    {
        foreach( $array as $key=>$value )
        {
            if ( ! is_numeric( $key ) )
            {
                return false;
            }
        }
        return true;
    }
    
}

