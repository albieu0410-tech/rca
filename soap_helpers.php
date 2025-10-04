<?php
declare(strict_types=1);

/**
 * SOAP helper functions for interacting with the Asiguram broker API.
 */

if (!function_exists('soap_helpers_config')) {
    /**
     * Loads the global configuration array and returns it.
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

if (!function_exists('soap_helpers_normalize')) {
    /**
     * Recursively converts SOAP responses into plain PHP arrays/scalars.
     *
     * @param mixed $value
     * @return mixed
     */
    function soap_helpers_normalize(mixed $value): mixed
    {
        if (is_object($value)) {
            $value = (array) $value;
        }

        if (is_array($value)) {
            foreach ($value as $key => $item) {
                $value[$key] = soap_helpers_normalize($item);
            }
        }

        return $value;
    }
}

if (!function_exists('broker_client')) {
    /**
     * Builds a SoapClient configured for the broker endpoint.
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
            'location'     => $config['ws_brokerurl'],
            'uri'          => 'http://asiguram.ro/ws',
            'trace'        => true,
            'exceptions'   => true,
            'soap_version' => SOAP_1_1,
            'features'     => SOAP_SINGLE_ELEMENT_ARRAYS,
        ];

        if (!empty($config['ws_username'])) {
            $options['login']    = $config['ws_username'];
            $options['password'] = $config['ws_parola'] ?? '';
        }

        return new SoapClient(null, $options);
    }
}

if (!function_exists('broker_header')) {
    /**
     * Builds the CredentialHeader required by the broker SOAP service.
     */
    function broker_header(): SoapHeader
    {
        $config = soap_helpers_config();
        $username = $config['ws_username'] ?? '';
        $password = $config['ws_parola'] ?? '';

        if ($username === '' || $password === '') {
            throw new RuntimeException('SOAP credentials missing from config.');
        }

        return new SoapHeader(
            'http://asiguram.ro/ws',
            'CredentialHeader',
            [
                'Username' => $username,
                'Password' => $password,
            ]
        );
    }
}

if (!function_exists('broker_call')) {
    /**
     * Executes a SOAP call and returns the normalized array response.
     */
    function broker_call(string $method, array $payload): array
    {
        $client = broker_client();
        $client->__setSoapHeaders(broker_header());

        $response = $client->__soapCall($method, [$payload]);
        $normalized = soap_helpers_normalize($response);

        return is_array($normalized) ? $normalized : [];
    }
}

if (!function_exists('adauga_oferta')) {
    /**
     * Calls the AdaugaOferta SOAP method and returns the response payload as array.
     */
    function adauga_oferta(array $payload): array
    {
        if (isset($payload['datavalabilitate'])) {
            $timestamp = strtotime((string) $payload['datavalabilitate']);
            if ($timestamp !== false) {
                $payload['datavalabilitate'] = date('Y-m-d', $timestamp);
            }
        }

        return broker_call('AdaugaOferta', $payload);
    }
}

if (!function_exists('tarife_oferta')) {
    /**
     * Calls the TarifeOferta SOAP method for a given offer id and returns the full payload.
     */
    function tarife_oferta(string $idOferta): array
    {
        $response = broker_call('TarifeOferta', ['idoferta' => $idOferta]);

        $tarife = $response['tarif'] ?? [];

        if ($tarife === null) {
            $tarife = [];
        }

        if (!is_array($tarife)) {
            $tarife = [];
        }

        $isAssociative = $tarife !== [] && array_keys($tarife) !== range(0, count($tarife) - 1);
        if ($isAssociative) {
            $tarife = [$tarife];
        }

        $response['tarif'] = array_map(
            static fn ($row): array => is_array($row) ? $row : (array) soap_helpers_normalize($row),
            $tarife
        );

        return $response;
    }
}

