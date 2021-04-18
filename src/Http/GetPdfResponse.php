<?php

namespace Omniship\Cargus\Http;

class GetPdfResponse extends AbstractResponse
{

    /**
     * @return bool
     */
    public function getData(){
        if(is_null($this->data)  || isset($this->data['error']->Error)){
            return $this->error;
        }
        return base64_decode($this->data);
    }

}
