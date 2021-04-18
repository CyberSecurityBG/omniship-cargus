<?php

namespace Omniship\Cargus\Http;

use Omniship\Cargus\Client;
use Omniship\Message\AbstractResponse AS BaseAbstractResponse;

class AbstractResponse extends BaseAbstractResponse
{

    protected $error;

    protected $errorCode;

    protected $client;


    /**
     * Get the initiating request object.
     *
     * @return AbstractRequest
     */
    public function getRequest()
    {
       return  $this->request;
    }

    /**
     * @return null|string
     */
    public function getMessage()
    {
        if(isset($this->getClient()->getError()['error']->Error) || is_array($this->getClient()->getError()['error']) || isset($this->data->Error)){
            if(isset($this->data->Error)) {
                $message =  $this->data->Error;
            }
            $message =  is_array($this->getClient()->getError()['error']) ? implode('<br />', $this->getClient()->getError()['error']) : $this->getClient()->getError()['error']->Error;
        }
        return $message;
    }

    /**
     * @return null|string
     */
    public function getCode()
    {
        if(!is_null($this->getClient()->getError()) && $this->getClient()->getError()['code'] != 200) {
            return $this->getMessage();
        }
        return null;
    }

    /**
     * @return null|Client
     */
    public function getClient()
    {
        return $this->getRequest()->getClient();
    }

    /**
     * @param mixed $client
     * @return AbstractResponse
     */


}
