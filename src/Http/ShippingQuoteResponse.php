<?php

namespace Omniship\Cargus\Http;
use Omniship\Common\ShippingQuoteBag;

class ShippingQuoteResponse extends AbstractResponse
{
    public function getData()
    {
        if(is_null($this->data) || isset($this->data->Error)){
            return $this->error;
        }
        $result = new ShippingQuoteBag();
        $result->push( [
            'id' => 1,
            'name' => 'Shipping to adress',
            'description' => null,
            'price' => (float)$this->data->GrandTotal,
            'pickup_date' => null,
            'pickup_time' => null,
            'delivery_date' => null,
            'delivery_time' => null,
            'currency' => $this->getRequest()->getCurrency(),
            'tax' => null,
            'insurance' => null,
            'exchange_rate' => null,
            'payer' =>$this->getRequest()->getPayer(),
            'allowance_fixed_time_delivery' => false,
            'allowance_cash_on_delivery' => true,
            'allowance_insurance' => true,
        ]);
        return $result;
    }
}
