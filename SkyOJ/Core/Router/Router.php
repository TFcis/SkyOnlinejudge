<?php namespace SkyOJ\Core\Router;

use \SkyOJ\Helper\ParamTypeChecker;
use \SkyOJ\API\ApiInterfaceException;
use \SkyOJ\API\HttpCode\HttpResponse;

class Router
{
    private $m_api = [];

    use \SkyOJ\API\HttpCode\Http200;
    use \SkyOJ\API\HttpCode\Http400;
    use \SkyOJ\API\HttpCode\Http501;

    public function addRouter(string $method, string $path, array $param, string $class)
    {
        if( !isset($this->m_api[$path]) )
            $this->m_api[$path] = [];
        
        $this->m_api[$path][$method] = [$param,$class];
    }

    private function getParamsByMethod(string $method)
    {
        switch($method)
        {
            case 'POST':
                return $this->getPostData();
            case 'GET':
                return $_GET;
        }
        throw $this->http422('No such method : '.$method);
    }

    private function getPostData()
    {
        $content_type = $_SERVER['CONTENT_TYPE']??'application/x-www-form-urlencoded';
        $content_type = explode(';',$content_type,1)[0];
        switch( $content_type )
        {
            case 'application/x-www-form-urlencoded':
            case 'multipart/form-data':
                return $_POST;
            case 'application/json':
                $json = json_decode(file_get_contents("php://input"), true);
                if( !$json )
                    $json = [];
                return $json;
        }
        return [];
    }

    public function getApi()
    {
        return $this->m_api;
    }

    public function run(&$skyoj): HttpResponse
    {
        try
        {
            $method = $_SERVER['REQUEST_METHOD']??'';
            $path = $_SERVER['PATH_INFO']??'/';

            //TODO: Check CROS
            if( $method == 'OPTIONS' )
                throw $this->http200();
            $input = $this->getParamsByMethod($method);

            if( !isset($this->m_api[$path]) || !isset($this->m_api[$path][$method]) )
                throw $this->http400('No such route : '.$path);

            $class = $this->m_api[$path][$method][1];
            $params = [];
            foreach( $this->m_api[$path][$method][0] as $param ) //[type,name]
            {
                if( !isset($input[$param[1]]) )
                    throw $this->http400('missing param : '.$param[1]);
                if( !ParamTypeChecker::check($param[0],$input[$param[1]]) )
                    throw $this->http400('param format error : '.$param[1]);
                $params[] = $input[$param[1]];
            }

            if( !class_exists($class) )
                throw $this->http501('not imp : '.$class);
            $c = new $class($skyoj);

            $res =  $c->run(...$params);

            return $res;
        }
        catch(HttpResponse $e)
        {
            return $e;
        }
        catch(\Exception $e)
        {
            return new HttpResponse(500, $e->getMessage());
        }
    }
}
