<?php

namespace service;

use GuzzleHttp\Client;

/**
 * Created by PhpStorm.
 * User: intelWorx
 * Date: 01/11/2015
 * Time: 7:15 PM
 *
 * @method \stdClass CreateBusinessOwner(array $params, callable $onError = null)
 * @method \stdClass CreateCustomer(array $params, callable $onError = null)
 * @method \stdClass CreateMerchant(array $params, callable $onError = null)
 * @method \stdClass CreateMerchantUser(array $params, callable $onError = null)
 * @method \stdClass CustomerBio(array $params, callable $onError = null)
 * @method \stdClass GetCustomerBio(array $params, callable $onError = null)
 * @method \stdClass GetReservationStatus(array $params, callable $onError = null)
 * @method \stdClass ReservationRequest(array $params, callable $onError = null)
 * @method \stdClass SendNewCustomerSource(array $params, callable $onError = null)
 * @method \stdClass SendSecureFundUpdate(array $params, callable $onError = null)
 * @method \stdClass UpdateUserInfo(array $params, callable $onError = null)
 * @method \stdClass UserLogin(array $params, callable $onError = null)
 */
 
class WebServiceClient extends \IdeoObject {

    private static $methods = [
        "CreateBusinessOwner" => 'POST'
        , "CreateCustomer" => 'POST'
        , "CreateMerchant" => 'POST'
        , "CreateMerchantUser" => 'POST'
        , "CustomerBio" => 'POST'
        , "GetCustomerBio" => 'GET'
        , "GetReservationStatus" => 'GET'
        , "ReservationRequest" => 'POST'
        , "SendNewCustomerSource" => 'POST'
        , "SendSecureFundUpdate" => 'POST'
        , "UpdateUserInfo" => 'POST'
        , "UserLogin" => 'POST'
    ];

    const ERR_SUCCESS = '00';

    private $serviceEndpoint;

    /**
     * @var Client
     */
    private $httpClient;

    /**
     * WebServiceClient constructor.
     * @param Client $client
     */
    public function __construct(Client $client = null) {
        $this->serviceEndpoint = \SystemConfig::getInstance()->service['endpoint'];
        $this->httpClient = $client ? : new Client([
            'base_uri' => $this->serviceEndpoint,
            'verify' => false
        ]);
    }

    public function __call($name, $arguments) {
        $name = ucfirst($name);
        if (!array_key_exists($name, self::$methods)) {
            throw new \BadMethodCallException("The specified method [{$name}] does not exist.");
        }

        $method = self::$methods[$name];
        try {
            return $this->execute($name, $method, $arguments[0]);
        } catch (\Exception $e) {
            if (isset($arguments[1]) && is_callable($arguments[1])) {
                call_user_func($arguments[1], $e);
                return null;
            } else {
                throw $e;
            }
        }
    }

    private function execute($operation, $method, $body) {
        $params = [
            'headers' => [
                'User-Agent' => 'PaymentGatewayClientV/1.0',
                'Accept' => 'application/json',
                'ClientID' => \SystemConfig::getInstance()->service['clientid'],
                'ClientSecretKey' => \SystemConfig::getInstance()->service['clientsecretkey']
            ],
            'http_errors' => false
        ];
        if ($method === 'GET') {
            $params['query'] = $body;
        } else {
            $params['json'] = $body;
            \SystemLogger::debug('Data', json_encode($params['json'], JSON_PRETTY_PRINT));
        }

        $response = $this->httpClient->request($method, $operation, $params);

        if ($response->getStatusCode() !== 200) {
            throw new ServiceException($response->getReasonPhrase(), $response->getStatusCode());
        }


        $contents = $response->getBody()->getContents();
        \SystemLogger::info('Service returned the following: ', $contents);
        $response = json_decode($contents);


        if ($response->ResponseCode !== self::ERR_SUCCESS) {
            throw new BadResponseException($response->ResponseDescription ? : 'The code is not the expected response
            code', $response->ResponseCode);
        }

        return $response;
    }

}
