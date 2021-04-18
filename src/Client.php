<?php

namespace Omniship\Cargus;

use Carbon\Carbon;
use GuzzleHttp\Client AS HttpClient;
use http\Client\Response;

class Client
{

    protected $username;
    protected $password;
    protected $key_primary;
    protected $key_secondary;
    protected $error;
    protected $token;
    const SERVICE_PRODUCTION_URL = 'https://urgentcargus.azure-api.net/api/';
    public function __construct($username, $password, $token = null)
    {
        $this->username = $username;
        $this->password = $password;
        $this->key_primary = '2e43b07e28f443559b6c3832c46da64b';
        $this->key_secondary = 'de662014db2240189e7578370b03b975';
        $this->token = $token;
    }


    public function getError()
    {
        return $this->error;
    }

    protected function SetHeader($ednpoint, $method, $key = null){
        $header['Content-Type'] = 'application/json';
        $header['Accept'] = 'application/vnd.api+json';
        if($ednpoint == 'LoginUser' && $method == 'post'){
            $header['Ocp-Apim-Subscription-Key'] = $this->key_primary;
        } else {
            $header['Authorization'] = 'Bearer '.$key;
            $header['Ocp-Apim-Subscription-Key'] = $this->key_secondary;
        }
        return $header;
    }

    public function getToken(){
        try {
            $client = new HttpClient(['base_uri' => self::SERVICE_PRODUCTION_URL]);
            $response = $client->request('POST', 'LoginUser', [
                'json' =>  ['UserName' => $this->username, 'password' => $this->password],
                'headers' => $this->SetHeader('LoginUser', 'POST', $this->key_primary)
            ]);
            return json_decode($response->getBody()->getContents());
        } catch (\Exception $e) {
             $this->error = [
                'code' => $e->getCode(),
                'error' => json_decode($e->getResponse()->getBody()->getContents())
            ];
        }
    }

    public function SendRequest($method, $endpoint, $data = []){
        $Token = $this->getToken();
        if(!is_null($Token)) {
            try {
                $client = new HttpClient(['base_uri' => self::SERVICE_PRODUCTION_URL]);
                $response = $client->request($method, $endpoint, [
                    'json' => $data,
                    'headers' => $this->SetHeader($endpoint, $method, $Token)
                ]);
               // dd($response->getBody()->getContents());
                return json_decode($response->getBody()->getContents());
            } catch (\Exception $e) {
                return  $this->error = [
                    'code' => $e->getCode(),
                    'error' => json_decode($e->getResponse()->getBody()->getContents())
                ];
            }
        }
    }

    public function getCountries(){
        $coutries = $this->SendRequest('GET', 'Countries');
        if(is_null($coutries)){
            return $this->getError();
        }
        return $coutries;
    }

    public function getLocalities($country_id, $county_id){
        $localities = $this->SendRequest('GET', 'Localities?countryId='.$country_id.'&countyId='.$county_id);
        if(is_null($localities)){
            return $this->getError();
        }
        return $localities;
    }

    public function getCounties($country_id){
        $cities = $this->SendRequest('GET', 'Counties?countryId='.$country_id);
        if(is_null($cities)){
            return $this->getError();
        }
        return $cities;
    }

    public function getStreets($locality_id){
        $streets = $this->SendRequest('GET', 'Streets?localityId='.$locality_id);
        if(is_null($streets)){
            return $this->getError();
        }
        return $streets;
    }

    public function getOffices(){
        $offices = $this->SendRequest('GET', 'Branches');
        if(is_null($offices)){
            return $this->getError();
        }
        return $offices;
    }

    public function getPdf($bol_id, $format){
        if($format == 'A4'){
            $format = 0;
        } else {
            $format = 1;
        }
        return $this->SendRequest('GET', 'AwbDocuments?barCodes='.$bol_id.'&type=PDF&format='.$format.'&printMainOnce=0');
    }
}
