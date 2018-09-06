<?php namespace SkyOJ\API\HttpCode;

trait Http404
{
    public function http404($data = null): HttpResponse
    {
        return new HttpResponse(404, $data);
    }
}