<?php
namespace Omniship\Cargus\Http;
use Omniship\Cargus\Http\GetPdfResponse;
class GetPdfRequest extends AbstractRequest
{
    /**
     * @return integer
     */
    public function getData() {
        $type = 0;
        if($this->getOtherParameters('printer_type') == 'a6'){
            $type = 1;
        }
        return [
            'format' => $type,
            'bol_id' => $this->getBolId()
        ];
    }

    /**
     * @param mixed $data
     * @return GetPdfResponse
     */
    public function sendData($data) {
        return $this->createResponse($this->getClient()->SendRequest('GET', 'AwbDocuments?barCodes='.$data['bol_id'].'&type=PDF&format='.$data['format'].'&printMainOnce=0'));
    }

    /**
     * @param $data
     * @return GetPdfResponse
     */
    protected function createResponse($data)
    {
        return $this->response = new GetPdfResponse($this, $data);
    }

}
