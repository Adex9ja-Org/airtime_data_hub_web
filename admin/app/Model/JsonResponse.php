<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 2/14/2020
 * Time: 10:20 PM
 */

namespace App\Model;


class JsonResponse
{
    var $responseCode;
    var $responseStatus;
    var $data;

    /**
     * JsonResponse constructor.
     * @param $responseCode
     * @param $responseStatus
     * @param $data
     */
    public function __construct($responseCode = "-01", $responseStatus = "Invalid Post Parameter", $data = null)
    {
        $this->responseCode = $responseCode;
        $this->responseStatus = $responseStatus;
        $this->data = $data;
    }
}
