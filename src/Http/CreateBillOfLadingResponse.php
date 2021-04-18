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
        if(is_null($this->data)  || isset($this->data['error']->Error)){
            return $this->error;
        }
        $data = $this->data;
        $result = new Create();
        $result->setBolId($data[0]->BarCode);
        $result->setBillOfLadingSource($this->getClient()->getPdf($data[0]->BarCode, 1));
        $result->setBillOfLadingType($result::PDF);
        $result->setTotal($data[0]->ShippingCost->GrandTotal);
        return $result;
    }

}
