<?php namespace SkyOJ\Core\Router;

use \SkyOJ\Helper\ParamTypeChecker;

class Router
{
    private $m_api = [];
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
        throw new \Exception('No such method : '.$method);
    }

    public function run(&$skyoj)
    {
        try
        {
            $method = $_SERVER['REQUEST_METHOD']??'';
            $path = $_SERVER['PATH_INFO']??'/';
            $input = $this->getParamsByMethod($method);

            if( !isset($this->m_api[$path]) || !isset($this->m_api[$path][$method]) )
                throw new \Exception('No such route : '.$path);

            $class = $this->m_api[$path][$method][1];
            $params = [];
            foreach( $this->m_api[$path][$method][0] as $param ) //[type,name]
            {
                if( !isset($input[$param[1]]) )
                    throw new \Exception('missing param : '.$param[1]);
                ParamTypeChecker::check($param[0],$input[$param[1]]);
                $params[] = $input[$param[1]];
            }
            
            if( !class_exists($class) )
                throw new \Exception('not imp : '.$class);
            $c = new $class($skyoj);

            return $c->run(...$params);

            $this->exit($res);
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    private function exit($data)
    {
        die( json_encode($data) );
    }
}