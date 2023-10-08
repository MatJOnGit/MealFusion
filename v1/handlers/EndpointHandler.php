<?php

namespace Api\Handlers;

use Api\Utils\MethodUtils;
use Api\Utils\UriUtils;
use Api\Utils\BodyUtils;
use Api\Utils\HeadersUtils;
use Api\Exceptions\EndpointException;
use Exception;

final class EndpointHandler
{
    public MethodUtils $methodUtils;
    public UriUtils $uriUtils;
    public HeadersUtils $headersUtils;
    public BodyUtils $bodyUtils;

    private string $_method;
    private string $_resource;
    private string $_query;
    private string $_queryParam;
    private $_body;
    private $_queryAction;

    public bool $isEndpointValid = false;

    public function __construct($db)
    {
        try {
            $this->methodUtils = new MethodUtils;
            if (!$this->methodUtils->isMethodValid) {
                throw new EndpointException(405, 'Method not allowed');
            }

            $this->uriUtils = new UriUtils();
            if (!$this->uriUtils->isUriValid) {
                throw new EndpointException(404, 'Invalid request');
            }

            $this->headersUtils = new HeadersUtils($db);
            if (!$this->headersUtils->areHeadersValid) {
                throw new EndpointException(400, 'Bad request');
            }

            $this->_method = $this->methodUtils->getMethod();
            $this->_resource = $this->uriUtils->getResource();
            $this->_query = $this->uriUtils->getQuery();

            $this->bodyUtils = new BodyUtils($this->_method, $this->_resource, $this->_query);
            if ($this->bodyUtils->areDeeperBodyTestsRequired) {
                $this->bodyUtils->checkBodyContent();
            }

            $this->_queryParam = $this->uriUtils->getQueryParam();
            $this->_body = $this->bodyUtils->getBody();
            $this->_queryAction = $this->bodyUtils->getQueryAction();

            $this->_checkEndpointPermissions();

            $this->isEndpointValid = true;
        } catch (EndpointException $e) {
            $responseHandler = new ResponseHandler($e->getCode(), $e->getMessage());
        } catch (Exception $e) {
            $responseHandler = new ResponseHandler(500, 'Internal server error');
        }
    }

    /*********************************************************************************
    Instanciate a error handler if the key owner is not authorized to use that request
     *********************************************************************************/
    private function _checkEndpointPermissions()
    {
        try {
            foreach ($this->bodyUtils->getRoutes() as $route) {
                if ($this->_queryAction === $route['action']) {
                    if (!in_array($this->headersUtils->getPermissions(), $route['permissions'])) {
                        throw new EndpointException(403, 'Unauthorized request');
                    }
                }
            }
        } catch (EndpointException $e) {
            $responseHandler = new ResponseHandler($e->getCode(), $e->getMessage());
        } catch (Exception $e) {
            $responseHandler = new ResponseHandler(500, 'Internal server error');
        }
    }

    public function getBody()
    {
        return $this->_body;
    }

    public function getMethod()
    {
        return $this->_method;
    }

    public function getQuery()
    {
        return $this->_query;
    }

    public function getQueryAction()
    {
        return $this->_queryAction;
    }

    public function getQueryParam()
    {
        return $this->_queryParam;
    }

    public function getResource()
    {
        return $this->_resource;
    }
}
