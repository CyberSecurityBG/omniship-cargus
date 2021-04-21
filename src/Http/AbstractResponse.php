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
        if(isset($this->getClient()->getError()['error']->Error) || is_array($this->getClient()->getError()['error']) || !empty($this->getClient()->getError()['error']) || isset($this->data->Error)){
            if(isset($this->data->Error)) {
                $message =  $this->data->Error;
            }
            if( is_array($this->getClient()->getError()['error'])){
                $message = implode('<br />', $this->getClient()->getError()['error']);
            } elseif(!empty($this->getClient()->getError()['error'])){
                $message = $this->getClient()->getError()['error'];
            } else {
                $message = $this->getClient()->getError()['error']->Error;
            }
        }
        if(isset($message)) {
            return $message;
        }
    }

    /**
     * @return null|string
     */
    public function getCode()
    {
        if(!is_null($this->getClient()->getError()) && $this->getClient()->getError()['code'] != 200) {
            if($this->getMessage() != 'There is no active user with the provided credential!') {
                return $this->getMessage();
            } else {
                return null;
            }
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
