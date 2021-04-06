<?php

namespace Omniship\Cargus;

use Carbon\Carbon;
use GuzzleHttp\Client AS HttpClient;
use http\Client\Response;

class Client
{

    protected $key;
    protected $error;

    const SERVICE_PRODUCTION_URL = 'https://api.berry.bg/v2/';

    public function __construct($key)
    {
        $this->key = $key;
    }


    public function getError()
    {
        return $this->error;
    }

    protected function SetHeader($ednpoint, $method, $api_key){
        $header['Content-Type'] = 'application/json';
        $header['Accept'] = 'application/vnd.api+json';
        if($ednpoint == 'users' && $method == 'POST'){
        } else {
            $header['X-BERRY-APIKEY'] = $api_key;
        }
        return $header;
    }

    public function SendRequest($method, $endpoint, $data = [], $ignore = null, $key = null){
        if(is_null($key)){
            $key = $this->key;
        }
        try {
            $client = new HttpClient(['base_uri' => $this->getServiceEndpoint()]);
            $response = $client->request($method, $endpoint, [
                'json' => $data,
                'headers' => $this->SetHeader($endpoint, $method, $this->key)
            ]);
            return json_decode($response->getBody()->getContents());
        } catch (\Exception $e) {
            if($ignore && $ignore == $e->getCode()){
                return true;
            }
            $this->error = [
                'code' => $e->getCode(),
                'error' => $e->getResponse()->getBody()->getContents()
            ];
        }
    }

    /**
     * Get url associated to a specific service
     *
     * @return string URL for the service
     */
    public function getServiceEndpoint()
    {
        return static::SERVICE_PRODUCTION_URL;
    }

    public function CreateUser($data){
        $SendRequest = $this->SendRequest('POST', 'users', $data);
        if($SendRequest != null){
            return $SendRequest->api_app_keys[0];
        } else {
            return json_decode($this->error['error']);
        }
    }

    public function GetWarehouses($api_key){
        return $this->SendRequest('GET', 'addresses','', '' ,$api_key);
    }

    public function GetProfile($api_key){
        return $this->SendRequest('GET', 'users','', '' ,$api_key);
    }

    public function GetServices(){
        $AvailableSlots = $this->SendRequest('get', 'packages/next_available_slots?count=6');
        $slots = [];
        foreach ($AvailableSlots as $service) {
            $ServivePickUp = Carbon::createFromTimeString($service[0], 'UTC');
            $ServiceId = $ServivePickUp->format('Y-m-d_H-i');
            $ServivePickUp->setTimezone('Europe/Sofia');
            $ServiceDropOff = Carbon::createFromTimeString($service[1], 'UTC');
            $ServiceId = $ServiceId . '__' . $ServiceDropOff->format('Y-m-d_H-i');
            $ServiceDropOff->setTimezone('Europe/Sofia');
            $slots[] = [
                'id' => $ServiceId,
                'name' => 'Доставка на ' . $ServivePickUp->format('d.m.Y') . ' от ' . $ServivePickUp->format('H:i') . ' до ' . $ServiceDropOff->format('H:i'),
            ];
        }
        return $slots;
    }
}
