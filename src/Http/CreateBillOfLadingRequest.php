<?php


namespace  Omniship\Cargus\Http;

use Carbon\Carbon;

class CreateBillOfLadingRequest extends AbstractRequest
{
    /**
     * @return array
     */
    public function getData() {
        if($this->getPayer() == 'SENDER'){
            $payer = 1;
        } else{
            $payer = 2;
        }
        $cash_on_delivery  = 0;
        $declared_amount = 0;
      //  dd($this->getReceiverAddress());
        if($this->getCashOnDeliveryAmount() != null && $this->getCashOnDeliveryAmount() > 0){
            $cash_on_delivery = $this->getCashOnDeliveryAmount();
        }
        if($this->getOtherParameters('declared') == 1 && $this->getDeclaredAmount() != null && $this->getDeclaredAmount() > 0){
            $declared_amount = $this->getDeclaredAmount();
        }
        $items= array();

        foreach($this->getItems()->toArray() as $item){
            if(is_null($item['weight'])){
                $item['weight'] = 0;
            }
            $items[] = [
                'Code' => $item['id'],
                'Type' => 1,
                'Weight' => $item['weight'],
                'Length' => 10,
                'Width' => 10,
                'Height' => 10,
                'ParcelContent' => $item['name']
            ];
        }
        $data = [
            'SenderClientId' => null,
            'TertiaryClientId' => null,
            'TertiaryLocationId' => 0,
            'Sender' => [
                'LocationId' => 0,
                'Name' => $this->getSenderAddress()->getFullName(),
                'CountyId' => $this->getSenderAddress()->getState()->getId(),
                'CountyName' => '',
                'LocalityId'=> $this->getSenderAddress()->getCity()->getId(),
                'LocalityName' => $this->getSenderAddress()->getCity()->getName(),
                'StreetId' => '',
                'StreetName' => '',
                'BuildingNumber' => '',
                'AddressText' => '',
                'ContactPerson' => $this->getSenderAddress()->getFullName(),
                'PhoneNumber' => '',
                'Email' => '',
                'CodPostal' => $this->getSenderAddress()->getPostCode(),
                'CountryId' => $this->getSenderAddress()->getCountry()->getId()
            ],
            'Recipient' => [
                'LocationId' => 0,
                'Name' => $this->getReceiverAddress()->getFullName(),
                'CountyId' => $this->getReceiverAddress()->getState()->getId(),
                'CountyName' =>  '',
                'LocalityId'=> $this->getReceiverAddress()->getCity()->getId(),
                'LocalityName' => $this->getReceiverAddress()->getCity()->getName(),
                'StreetId' => '',
                'StreetName' => $this->getReceiverAddress()->getStreet()->getName().' '.$this->getReceiverAddress()->getStreetNumber(),
                'BuildingNumber' => $this->getReceiverAddress()->getBuilding(),
                'AddressText' => $this->getReceiverAddress()->getCity()->getName().','.$this->getReceiverAddress()->getStreet()->getName().' '.$this->getReceiverAddress()->getStreetNumber(),
                'ContactPerson' => $this->getReceiverAddress()->getFullName(),
                'PhoneNumber' => $this->getReceiverAddress()->getPhone(),
                'Email' => '',
                'CodPostal' => $this->getReceiverAddress()->getPostCode(),
                'CountryId' => $this->getReceiverAddress()->getCountry()->getId()
            ],
            'Parcels' => count($items),
            'Envelopes' => 0,
            'TotalWeight' => (int)$this->getWeight(),
            'ServiceId' => 1,
            'DeclaredValue' => $declared_amount,
            'CashRepayment' => $cash_on_delivery,
            'BankRepayment' => 0,
            'OtherRepayment' => '',
            'BarCodeRepayment' => '',
            'PaymentInstrumentId' => 0,
            'PaymentInstrumentValue' => 0,
            'HasTertReimbursement' => false,
            'OpenPackage' => $this->getOtherParameters('open_package'),
            'PriceTableId' => 0,
            'ShipmentPayer' => $payer,
            'ShippingRepayment' => '',
            'SaturdayDelivery' => false,
            'MorningDelivery' => false,
            'Observations' => '',
            'PackageContent' => '',
            'CustomString' => '',
            'BarCode' => null,
            'ParcelCodes' => $items
            ];
        return $data;
    }

    public function sendData($data) {
        return $this->createResponse($this->getClient()->SendRequest('POST', 'Awbs/WithGetAwb', $data));
    }

    /**
     * @param $data
     * @return ShippingQuoteResponse
     */
    protected function createResponse($data)
    {
        return $this->response = new CreateBillOfLadingResponse($this, $data);
    }

}
