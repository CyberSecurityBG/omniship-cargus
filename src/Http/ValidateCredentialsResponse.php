<?php

namespace Omniship\Cargus\Http;

class ValidateCredentialsResponse extends AbstractResponse
{

    /**
     * @return bool
     */
    public function getData()
    {
        return $this->data;
    }

}
