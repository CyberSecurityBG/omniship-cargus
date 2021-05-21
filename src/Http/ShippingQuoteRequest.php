<?php

namespace Omniship\Cargus\Http;
use Doctrine\Common\Collections\ArrayCollection;

class ShippingQuoteRequest extends AbstractRequest
{

    public function getData()
    {
        $cash_on_delivery = 0;
        if($this->getCashOnDeliveryAmount() > 0){
            $cash_on_delivery = $this->getCashOnDeliveryAmount();
        }
        return [
           'FromLocalityId' => $this->getSenderAddress()->getCity()->getId(),
           'ToLocalityId' => $this->getReceiverAddress()->getCity()->getId(),
           'Parcels' => count($this->getItems()),
           'Envelopes' => 0,
           'TotalWeight' => (int)$this->getWeight(),
           'DeclaredValue' => ($this->getOtherParameters('declared') == 1) ? $this->getDeclaredAmount() : 0,
           'CashRepayment' => ($this->getOtherParameters('cd') == 1) ? $cash_on_delivery : 0,
           'BankRepayment' => 0,
           'PaymentInstrumentId' => 0,
           'PaymentInstrumentValue' => 0,
           'OpenPackage' => $this->getOtherParameters('open_package'),
           'SaturdayDelivery' => false,
           'MorningDelivery' => false,
           'ShipmentPayer' => ($this->getPayer() == 'SENDER') ? 1 : 2,
       ];
    }

    public function sendData($data)
    {
        return $this->createResponse($this->getClient()->SendRequest('POST', 'ShippingCalculation', $data));
    }

    protected function createResponse($data)
    {
        return $this->response = new ShippingQuoteResponse($this, $data);
    }
}
