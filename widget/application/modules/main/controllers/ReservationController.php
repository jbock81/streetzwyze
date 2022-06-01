<?php
/**
 * Created by PhpStorm.
 * User: intelWorx
 * Date: 02/11/2015
 * Time: 9:55 AM
 */

namespace main\controllers;


use main\forms\ReservationInitiateHandler;
use models\utils\ArrayToXML;

class ReservationController extends BaseController
{

    const ERR_ERROR = '01';
    const ERR_INPUT = '02';
    const ERR_SUCCESS = '00';
    const ERR_VALIDATION = '03';

    protected function getRawPost()
    {
        $fh = fopen('php://input', 'r');
        $contents = stream_get_contents($fh);
        fclose($fh);
        return $contents;
    }

    private function parseInput()
    {
        $data = trim($this->getRawPost());
        $tmp = preg_split('/\s*;\s*/', strtolower(isset($this->_headers['CONTENT-TYPE']) ? $this->_headers['CONTENT-TYPE'] :
            'application/json'));

        $contentType = $tmp[0];


        if (!strlen($data)) {
            throw new \InvalidArgumentException('Input data cannot be empty');
        }

        $op = false;
        switch ($contentType) {
            case 'application/json':
            case 'text/json':
                $op = json_decode($data, true);
                break;

            case 'text/xml':
            case 'application/xml':
                $op = ArrayToXML::xml2array($data);
                break;

            case 'application/x-www-form-urlencoded':
                parse_str($data, $op);
                break;
            default:
                throw new \InvalidArgumentException('Supplied content type is not valid.');
        }

        if (!$op) {
            throw new \InvalidArgumentException('Parsing of input data failed.');
        }

        return $op;
    }

    public function doDefault()
    {
        //do some checks if needed
        if (!$this->_request->isPost()) {
            $this->_outputError(\Strings::get('errors.invalid_request_method'));
        }

        try {
            $data = $this->parseInput();
            //then validate
            $handler = new ReservationInitiateHandler($this->webServiceClient);
            if ($handler->process($data)) {
                //send resulting data.
                $response = [
                    'ResponseCode' => self::ERR_SUCCESS,
                    'ReservationId' => $data['ReservationId'],
                    'PaymentUrl' => $this->_getPaymentUrl($data['ReservationId'], isset($data['ReturnUrl']) ?
                        $data['ReturnUrl'] : null)
                ];
                $this->_output($response);
            } else {
                $errors = $handler->getErrors();
                $this->_outputError(\Strings::get('errors.input_validation'), self::ERR_VALIDATION, [
                    'Errors' => $errors
                ]);
            }

        } catch (\Exception $e) {
            $this->_outputError($e->getMessage(), $e->getCode() ?: self::ERR_INPUT);
        }

    }

    private function _getPaymentUrl($reservationId, $returnUrl)
    {
        $data = ['rid' => $reservationId];
        if ($returnUrl) {
            $data['returnUrl'] = $returnUrl;
        }

        return $this->_request->buildURL('default', 'pay', null, null, http_build_query($data));
    }

    private function _output($data)
    {
        $this->_ajaxOutputJson($data);
    }

    private function _outputError($message, $code = null, $extraData = null)
    {
        $this->badRequest();
        $data = [
            'ResponseCode' => $code ?: self::ERR_ERROR,
            'ResponseDescription' => $message
        ];

        if ($extraData) {
            $data = array_merge($data, (array)$extraData);
        }

        $this->_output($data);
    }

}