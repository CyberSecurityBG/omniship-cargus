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
        if($this->getCashOnDeliveryAmount() != null && $this->getCashOnDeliveryAmount() > 0){
            $cash_on_delivery = $this->getCashOnDeliveryAmount();
        }
        if($this->getDeclaredAmount() != null && $this->getDeclaredAmount() > 0){
            $declared_amount = $this->getDeclaredAmount();
        }
        $data = [
            'SenderClientId' => null,
            'TertiaryClientId' => null,
            'TertiaryLocationId' => 0,
            'Sender' => [
                'LocationId' => 919089342,
                'Name' => 'Ivan Georgiev',
                'CountyId' => 5,
                'CountyName' => '',
                'LocalityId'=> 919089342,
                'LocalityName' => 'Adunati',
                'StreetId' => '',
                'StreetName' => '',
                'BuildingNumber' => '',
                'AddressText' => '',
                'ContactPerson' => '',
                'PhoneNumber' => '',
                'Email' => '',
                'CodPostal' => '',
                'CountryId' => ''
            ],
            'Recipient' => [
                'LocationId' => '',
                'Name' => 'Ivan Petkov',
                'CountyId' => '',
                'CountyName' => '',
                'LocalityId'=> '',
                'LocalityName' => '',
                'StreetId' => '',
                'StreetName' => '',
                'BuildingNumber' => '',
                'AddressText' => '',
                'ContactPerson' => '',
                'PhoneNumber' => '',
                'Email' => '',
                'CodPostal' => '',
                'CountryId' => ''
            ],
            'Parcels' => 1,
            'Envelopes' => 0,
            'TotalWeight' => 5,
            'ServiceId' => 0,
            'DeclaredValue' => $declared_amount,
            'CashRepayment' => $cash_on_delivery,
            'BankRepayment' => 0,
            'OtherRepayment' => '',
            'BarCodeRepayment' => '',
            'PaymentInstrumentId' => 0,
            'PaymentInstrumentValue' => 0,
            'HasTertReimbursement' => true,
            'OpenPackage' => true,
            'PriceTableId' => 0,
            'ShipmentPayer' => $payer,
            'ShippingRepayment' => '',
            'SaturdayDelivery' => true,
            'MorningDelivery' => true,
            'Observations' => '',
            'PackageContent' => '',
            'CustomString' => '',
            'BarCode' => '',
            'ParcelCodes' => ''
            ];
        return $data;
    }

    public function sendData($data) {
        return $this->createResponse($this->getClient()->SendRequest('POST', 'Awbs', $data));
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
