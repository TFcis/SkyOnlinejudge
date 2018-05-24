<?php namespace SkyOJ\API;

use \SkyOJ\API\ApiInterface;
use \SkyOJ\API\ApiInterfaceException;

class Openapi extends ApiInterface
{
    private $m_json = [];
    private $m_api = [];
    function apiCall()
    {
        $this->m_json["swagger"] = "2.0";
        $this->appendInfo();
        $this->m_json["host"] = "pc2.tfcis.org";
        $this->m_json["basePath"] = "/dev/api.php/";
        $this->m_api = $this->m_skyoj->getParsedAPI();
        $this->appendTag();
        $this->m_json["schemes"] = ["https"];
        $this->m_json["consumes"] = ["application/json"];
        $this->m_json["produces"] = ["application/json"];
        $this->appendPath();
        die( json_encode($this->m_json) );
    }

    private function appendInfo()
    {
        $info = new \stdClass();
        $info->description = "SkyOJ Open API";
        $info->version = "v0.0";
        $info->title = "Sky Online Judge";
        $info->termsOfService = "to do";
        $info->contact = new \stdClass();
        $info->contact->email = "pc2.noreply@tfcis.org";
        $info->license = new \stdClass();
        $info->license->name = "to do";
        $info->license->url  = "https://pc2.tfcis.org";
        $this->m_json["info"] = $info;
    }

    private function parseTag(string $s)
    {
        $i=1;
        $len = strlen($s);
        $path = "";
        while( $i<$len && $s[$i]!=='/' ) $i++;
        return substr($s,1,$i-1);
    }

    private function appendTag()
    {
        $used = [];
        $tags = [];
        foreach($this->m_api as $url => $info)
        {
            $tagname = $this->parseTag($url);
            if( !isset($used[$tagname]) )
            {
                $used[$tagname] = 1;
                $tag = new \stdClass();
                $tag->name = $tagname;
                $tag->description = "";

                $tags[] = $tag;

            }
        }
        $this->m_json["tags"] = $tags;
    }

    private function appendPath()
    {
        $paths = [];
        foreach($this->m_api as $url => $info)
        {
            $obj = new \stdClass();
            $tag = $this->parseTag($url);
            foreach($info as $method => $source)
            {
                $data = new \stdClass();

                $data->tags = [$tag];
                $data->summary = $url;
                $data->description = "";
                $data->operationId = $url;
                $data->parameters = [];
                foreach( $source[0] as $param  )
                {
                    $p = new \stdClass();
                    $p->name = $param[1];
                    $p->in = $method=="GET"?"query":"body";//TODO: FixMe
                    $p->description = $param[1];
                    $p->required = true;
                    $p->type = $param[0];
                    $p->format = $param[0];
                    $data->parameters[]=$p;
                }
                $data->responses = [];
                $data->responses["200"] = [];
                $data->responses["200"]["description"] = "MAIN JSON FORMAT";
                $method = strtolower($method);
                $obj->$method = $data;
            }
            $paths[$url] = $obj;
        }
        $this->m_json["paths"] = $paths;
    }
}
