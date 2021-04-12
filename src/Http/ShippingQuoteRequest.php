<?php

namespace Omniship\Cargus\Http;
use Doctrine\Common\Collections\ArrayCollection;

class ShippingQuoteRequest extends AbstractRequest
{

    public function getData()
    {
       return [
           'FromLocalityId' => $this->getSenderAddress()->getState()->getId(),
           'ToLocalityId' => $this->getReceiverAddress()->getState()->getId(),
           'Parcels' => 2,
           'Envelopes' => 0,
           'TotalWeight' => 5,
           'DeclaredValue' => 0,
           'CashRepayment' => 173.42,
           'BankRepayment' => 0,
           'PaymentInstrumentId' => 0,
           'PaymentInstrumentValue' => 0,
           'OpenPackage' => true,
           'SaturdayDelivery' => false,
           'MorningDelivery' => false,
           'ShipmentPayer' => 1,
       ];
    }

    public function sendData($data)
    {
        return $this->createResponse($this->getClient()->SendRequest('POST', 'ShippingCalculation', $data));
    }

    protected function createResponse($data)
    {
       // dd($data);
        return $this->response = new ShippingQuoteResponse($this, $data);
    }
}
