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
    public function __construct($username, $password, $primary_key, $secondary_key, $token = null)
    {
        $this->username = $username;
        $this->password = $password;
        $this->key_primary = $primary_key;
        $this->key_secondary = $secondary_key;
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
                return json_decode($response->getBody()->getContents());
            } catch (\Exception $e) {
                 $this->error = [
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
        $coutries = $this->SendRequest('GET', 'Localities?countryId='.$country_id.'&countyId='.$county_id);
        if(is_null($coutries)){
            return $this->getError();
        }
        return $coutries;
    }

    public function getCities($country_id){
        $coutries = $this->SendRequest('GET', 'Counties?countryId='.$country_id);
        if(is_null($coutries)){
            return $this->getError();
        }
        return $coutries;
    }
}
