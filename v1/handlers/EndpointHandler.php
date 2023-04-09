<?php

namespace Api\Handlers;

use Api\Utils\MethodUtils;
use Api\Utils\UriUtils;
use Api\Utils\BodyUtils;
use Api\Utils\HeadersUtils;
use Api\Exceptions\EndpointException;
use Exception;

final class EndpointHandler {
    public MethodUtils $methodUtils;
    public UriUtils $uriUtils;
    public HeadersUtils $headersUtils;
    public BodyUtils $bodyUtils;
    
    public string $resource;
    public string $query;
    
    public object $db;
    
    public bool $isEndpointValid = false;
    
    public function __construct($db)
    {
        try {
            $this->methodUtils = new MethodUtils;
            if (!$this->methodUtils->isMethodValid) {
                throw new EndpointException('405');
            }
            
            $this->uriUtils = new UriUtils();
            if (!$this->uriUtils->isUriValid) {
                throw new EndpointException('404');
            }
            
            $this->headersUtils = new HeadersUtils($db);
            if (!$this->headersUtils->areHeadersValid) {
                throw new EndpointException('400');
            }
            
            $this->bodyUtils = new BodyUtils();
            // if ($this->bodyUtils->isBodyValid) {
            //     echo 'body is okay !';
            // }
            
            // $this->resource = $this->getResource();
        }
        
        catch (EndpointException $e) {
            $responseHandler = new ResponseHandler($e);
        }
        
        catch (Exception $e) {
            $responseHandler = new ResponseHandler('500');
        }
    }
    
    public function getResource()
    {
        return $this->resource;
    }
    
    public function getQuery()
    {
        return $this->query;
    }
}