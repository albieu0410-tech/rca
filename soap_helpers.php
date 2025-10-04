<?php
/**
 * SOAP helper functions for interacting with the Asiguram broker API.
 */

if (!function_exists('soap_helpers_config')) {
    /**
     * Loads the global configuration array and returns it.
     *
     * @return array
     */
    function soap_helpers_config(): array
    {
        global $_CONFIG;
        if (!isset($_CONFIG)) {
            $_CONFIG = [];
        }

        return $_CONFIG;
    }
}

if (!function_exists('broker_client')) {
    /**
     * Builds a SoapClient configured for the broker endpoint.
     *
     * @throws SoapFault
     */
    function broker_client(): SoapClient
    {
        if (!class_exists('SoapClient')) {
            throw new RuntimeException('SOAP extension is not available in this environment.');
        }

        $config = soap_helpers_config();

        if (empty($config['ws_brokerurl'])) {
            throw new RuntimeException('Webservice URL missing from config.');
        }

        $options = [
            'location'   => $config['ws_brokerurl'],
            'uri'        => 'http://asiguram.ro/ws',
            'trace'      => true,
            'exceptions' => true,
        ];

        if (!empty($config['ws_username'])) {
            $options['login']    = $config['ws_username'];
            $options['password'] = $config['ws_parola'] ?? '';
        }

        return new SoapClient(null, $options);
    }
}

if (!function_exists('adauga_oferta')) {
    /**
     * Calls the AdaugaOferta SOAP method and returns the response payload as array.
     *
     * @param array $payload
     * @return array{ idoferta?: string }
     */
    function adauga_oferta(array $payload): array
    {
        $client = broker_client();

        $response = $client->__soapCall('AdaugaOferta', [$payload]);

        if (is_object($response)) {
            $response = (array) $response;
        }

        return $response;
    }
}

if (!function_exists('tarife_oferta')) {
    /**
     * Calls the TarifeOferta SOAP method for a given offer id.
     *
     * @param string $idOferta
     * @return array<int, array|object>
     */
    function tarife_oferta(string $idOferta): array
    {
        $client = broker_client();

        $response = $client->__soapCall('TarifeOferta', [['idoferta' => $idOferta]]);

        if (!is_object($response)) {
            return [];
        }

        $response = (array) $response;

        if (!isset($response['tarif'])) {
            return [];
        }

        $tarife = $response['tarif'];

        if (is_object($tarife)) {
            $tarife = [$tarife];
        }

        if (is_array($tarife)) {
            return array_map(static function ($row) {
                return is_object($row) ? (array) $row : $row;
            }, $tarife);
        }

        return [];
    }
}

