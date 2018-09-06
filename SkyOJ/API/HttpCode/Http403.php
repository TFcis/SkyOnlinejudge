<?php namespace SkyOJ\API\HttpCode;

trait Http403
{
    public function http403($data = null): HttpResponse
    {
        return new HttpResponse(403, $data);
    }
}