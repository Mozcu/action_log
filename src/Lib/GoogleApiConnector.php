<?php

namespace App\Lib;

class GoogleApiConnector {
    
    private $clientId;
    
    private $serviceAccountName;
    
    private $keyFile;
    
    private $projectId;
    
    private $client;
    
    public function __construct(array $googleApiData) {
        $this->clientId = $googleApiData['client_id'];
        $this->serviceAccountName = $googleApiData['service_acount_name'];
        $this->keyFile = $googleApiData['key_file'];
        $this->projectId = $googleApiData['project_id'];
    }
    
    public function connect($scope) {
        $this->client = new \Google_Client();
        $this->client->setApplicationName($this->projectId);
        
        $key = file_get_contents($this->keyFile);
        $this->client->setAssertionCredentials(new \Google_Auth_AssertionCredentials(
            $this->serviceAccountName,
            array($scope),
            $key)
        );
        $this->client->setScopes($scope);
        $this->client->setClientId($this->clientId);
    }
    
    /**
     * @return Google_Client
     **/
    public function getClient() {
        return $this->client;
    }
    
    public function getProjectId()
    {
        return $this->projectId;
    }
    
    
}
