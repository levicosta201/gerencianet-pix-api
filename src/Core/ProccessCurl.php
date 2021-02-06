<?php

namespace Gerencianet\Pix\Core;

use Gerencianet\Pix\Connect;
use Gerencianet\Pix\Helper;

class ProccessCurl
{
    public static function runCurl($url, $clientId, $clientSecret, $certFile)
    {
        $curl = curl_init();
        $authorization =  base64_encode("{$clientId}:{$clientSecret}");

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url, // Rota base, desenvolvimento ou produção
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => '{"grant_type": "client_credentials"}',
            CURLOPT_SSLCERT => $certFile, // Caminho do certificado
            CURLOPT_SSLCERTPASSWD => "",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Basic $authorization",
                "Content-Type: application/json"
            ),
        ));
        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            return [
                'success' => false,
                'data' => curl_error($curl)
            ];
        }
        curl_close($curl);

        return [
            'success' => true,
            'data' => json_decode($response),
        ];
    }

    public static function runCurlCharge($url, $certFile, $accessToken, $body, $tokenType, $method)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_SSLCERT => $certFile,
            CURLOPT_SSLCERTPASSWD => "",
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_HTTPHEADER => array(
                "authorization: $tokenType $accessToken",
                "Content-Type: application/json"
            ),
        ));
        curl_setopt($curl, CURLOPT_TIMEOUT_MS, 5000);

        $response = curl_exec($curl);

        Helper::checkFailure($response);
        curl_close($curl);

        return [
            'success' => true,
            'data' => json_decode($response, true),
        ];
    }

    public static function runCurlListIssued($pixUrl, $params, $certFile, $tokenType, $accessToken)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $pixUrl . '?' . $params,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_SSLCERT => $certFile,
            CURLOPT_SSLCERTPASSWD => "",
            CURLOPT_HTTPHEADER => array(
                "authorization: $tokenType $accessToken"
            ),
        ));
        curl_setopt($curl, CURLOPT_TIMEOUT_MS, 5000);

        $listPixRecebidos = json_decode(curl_exec($curl), true);
        curl_close($curl);

        Helper::checkFailure($listPixRecebidos);
        return $listPixRecebidos;
    }

    public static function runGetPixByTxId($url, $certFile, $accessToken, $tokenType)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_SSLCERT => $certFile,
            CURLOPT_SSLCERTPASSWD => "",
            CURLOPT_HTTPHEADER => array(
                "authorization: $tokenType $accessToken"
            ),
        ));
        curl_setopt($curl, CURLOPT_TIMEOUT_MS, 5000);

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            return [
                'success' => false,
                'data' => curl_error($curl)
            ];
        }
        curl_close($curl);

        return [
            'success' => true,
            'data' => json_decode($response, true),
        ];
    }

    public static function runWebhook($url, $certFile, $webhookUrl, $accessToken, $tokenType)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_SSLCERT => $certFile,
            CURLOPT_SSLCERTPASSWD => "",
            CURLOPT_POSTFIELDS => json_encode([
                'webhookUrl' => $webhookUrl
            ]),
            CURLOPT_HTTPHEADER => array(
                "authorization: $tokenType $accessToken",
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            return [
                'success' => false,
                'data' => curl_error($curl)
            ];
        }
        curl_close($curl);

        return [
            'success' => true,
            'data' => json_decode($response, true),
        ];
    }
}
