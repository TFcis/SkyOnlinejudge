<?php namespace SkyOJ\API\HttpCode;

trait Http400
{
    public function http400($data = null): HttpResponse
    {
        return new HttpResponse(400, $data);
    }
}