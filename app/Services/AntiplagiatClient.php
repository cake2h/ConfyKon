<?php

namespace App\Services;

use SoapClient;
use Antiplagiat\ApiCorp\IApiCorp;

class AntiplagiatClient
{
    protected SoapClient $client;

    public function __construct()
    {
        $this->client = new SoapClient(
            config('services.antiplagiat.wsdl'),
            [
                'login'        => config('services.antiplagiat.login'),
                'password'     => config('services.antiplagiat.password'),
                'soap_version' => SOAP_1_1,
                'trace'        => 1,   // 🔥 ключ для дебага
                'exceptions'   => true // выкидывает исключения вместо Warning
            ]
        );
    }

    public function ping()
    {
        return $this->client->Ping();
    }

    public function uploadDocument(string $contentBase64, string $filename, string $filetype, string $externalUserId)
    {
        $data = [
            'FileName' => $filename,
            'FileType' => $filetype,
            'Data' => base64_decode($contentBase64), 
            'ExternalUserID' => $externalUserId,
        ];

        try {
            $response = $this->client->UploadDocument(['data' => $data]);
            return $response;
        } catch (\SoapFault $e) {
            // Логируем и выводим полезную инфу
            dd([
                'error' => $e->getMessage(),
                'last_request' => $this->client->__getLastRequest(),
                'last_response' => $this->client->__getLastResponse(),
                'last_request_headers' => $this->client->__getLastRequestHeaders(),
                'last_response_headers' => $this->client->__getLastResponseHeaders(),
            ]);
        }
    }

    public function checkDocument($documentId, array $servicesList = null, $params = null)
    {
        $docId = [
            'Id' => $documentId,
            'External' => null
        ];

        return $this->client->CheckDocument([
            'docId' => $docId,
            'checkServicesList' => $servicesList ?? array('wikipedia'),
            'checkDocParameters' => $params,
        ]);
    }

    public function getCheckStatus($documentId)
    {
        $docId = [
            'Id' => $documentId,
            'External' => null
        ];

        return $this->client->GetCheckStatus(['docId' => $docId]);
    }

    public function getReportView($documentId, $options = null)
    {
        $docId = [
            'Id' => $documentId,
            'External' => null
        ];

        return $this->client->GetReportView([
            'docId' => $docId,
            'options' => $options,
        ]);
    }
    
    public function debugFunctions()
{
    return $this->client->__getFunctions();
}
}
