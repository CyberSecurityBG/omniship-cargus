<?php

namespace Omniship\Cargus\Http;

use Carbon\Carbon;
use Omniship\Common\Bill\Create;

class CreateBillOfLadingResponse extends AbstractResponse
{
    /**
     * @var Parcel
     */
    protected $data;
    /**
     * @return Create
     */
    public function getData()
    {
        if(is_null($this->data) || isset($this->data->Error)){
            return $this->error;
        }
        $respons = $this->data->ACSOutputResponce->ACSValueOutput[0];
        $result = new Create();
        $result->setBolId($respons->Voucher_No);
        $result->setBillOfLadingSource($this->getClient()->PrintVoucher($respons->Voucher_No));
        $result->setBillOfLadingType($result::PDF);
        return $result;
    }

}
