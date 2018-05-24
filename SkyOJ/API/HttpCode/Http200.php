<?php namespace SkyOJ\API\HttpCode;

trait Http200
{
    public function http200($data = null): HttpResponse
    {
        return new HttpResponse(200, $data);
    }
}