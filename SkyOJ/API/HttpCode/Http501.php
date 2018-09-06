<?php namespace SkyOJ\API\HttpCode;

trait Http501
{
    public function http501($data = null): HttpResponse
    {
        return new HttpResponse(501, $data);
    }
}