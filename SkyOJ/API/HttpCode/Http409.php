<?php namespace SkyOJ\API\HttpCode;

trait Http409
{
    public function http409($data = null): HttpResponse
    {
        return new HttpResponse(409, $data);
    }
}