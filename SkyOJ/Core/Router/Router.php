<?php namespace SkyOJ\Core\Router;

use \SkyOJ\Helper\ParamTypeChecker;
use \SkyOJ\API\ApiInterfaceException;

class Router
{
    private $m_api = [];
    private $m_status_code = 0;
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
                return $_POST;
            case 'GET':
                return $_GET;
        }
        throw new ApiInterfaceException(-1, 'No such method : '.$method);
    }

    public function lastStateCode()
    {
        return $this->m_status_code;
    }

    public function getApi()
    {
        return $this->m_api;
    }

    public function run(&$skyoj)
    {
        try
        {
            $method = $_SERVER['REQUEST_METHOD']??'';
            $path = $_SERVER['PATH_INFO']??'/';
            $input = $this->getParamsByMethod($method);

            if( !isset($this->m_api[$path]) || !isset($this->m_api[$path][$method]) )
                throw new ApiInterfaceException(-1, 'No such route : '.$path);

            $class = $this->m_api[$path][$method][1];
            $params = [];
            foreach( $this->m_api[$path][$method][0] as $param ) //[type,name]
            {
                if( !isset($input[$param[1]]) )
                    throw new ApiInterfaceException(-1, 'missing param : '.$param[1]);
                ParamTypeChecker::check($param[0],$input[$param[1]]);
                $params[] = $input[$param[1]];
            }
            
            if( !class_exists($class) )
                throw new ApiInterfaceException(-1, 'not imp : '.$class);
            $c = new $class($skyoj);


            $res =  $c->run(...$params);
            $this->m_status_code = 0;
            return $res;
        }
        catch(\Exception $e)
        {
            $this->m_status_code = $e->getCode()??-1;
            return $e->getMessage();
        }
    }

    private function exit($data)
    {
        die( json_encode($data) );
    }
}
