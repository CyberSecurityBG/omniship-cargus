<?php

namespace Omniship\Cargus\Http;
use Omniship\Cargus\Client;
class ValidateCredentialsRequest extends AbstractRequest
{


    public function getData()
    {

    }

    public function sendData($data)
    {
        $getusers = $this->getClient()->getToken();
        return $this->createResponse($getusers);
    }

    protected function createResponse($data)
    {
        return $this->response = new ValidateCredentialsResponse($this, $data);
    }

}
