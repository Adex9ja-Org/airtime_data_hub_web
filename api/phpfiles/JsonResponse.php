<?php


class JsonResponse
{
    public $responseCode;
    public $responseStatus;

    /**
     * JsonResponse constructor.
     */
    public function __construct($responseCode, $responseStatus)
    {
        $this->responseCode = $responseCode;
        $this->responseStatus = $responseStatus;
    }
}