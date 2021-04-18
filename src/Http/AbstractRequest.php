<?php

namespace Omniship\Cargus\Http;

use Omniship\Cargus\Client as CargusClient;

use Omniship\Message\AbstractRequest as BaseAbstractRequest;

abstract class AbstractRequest extends BaseAbstractRequest
{
    protected $client;

    public function getUsername(){
        return $this->getParameter('username');
    }

    public function setUsername($value){
        return $this->setParameter('username', $value);
    }

    public function getPassword(){
        return $this->getParameter('password');
    }

    public function setPassword($value){
        return $this->setParameter('password', $value);
    }

    public function getKeyPrimary(){
        return $this->getParameter('key_primary');
    }

    public function setKeyPrimary($value){
        return $this->setParameter('key_primary', $value);
    }

    public function getKeySecondary(){
        return $this->getParameter('key_secondary');
    }

    public function setKeySecondary($value){
        return $this->setParameter('key_secondary', $value);
    }


    public function getClient()
    {
        if(is_null($this->client)) {
            $this->client = new CargusClient($this->getUsername(), $this->getPassword(), $this->getKeyPrimary(), $this->getKeySecondary());
        }

        return $this->client;
    }

    abstract protected function createResponse($data);

}
