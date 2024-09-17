<?php

require_once __DIR__ . '/../models/Country.php';

class CountryController {
    private $requestMethod;
    private $countryId;
    private $country;

    public function __construct($requestMethod, $countryId = null)
    {
        $this->requestMethod = $requestMethod;
        $this->countryId = $countryId;
        $this->country = new Country();
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if ($this->countryId) {
                    $response = $this->getCountry($this->countryId);
                } else {
                    $response = $this->getAllCountries();
                }
                break;
            case 'POST':
                $response = $this->createCountryFromRequest();
                break;
            case 'PUT':
                $response = $this->updateCountryFromRequest($this->countryId);
                break;
            case 'DELETE':
                $response = $this->deleteCountry($this->countryId);
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }

    private function getAllCountries()
    {
        $result = $this->country->findAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function getCountry($id)
    {
        $result = $this->country->find($id);
        if (!$result) {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function createCountryFromRequest()
    {
        $input = (array)json_decode(file_get_contents('php://input'), TRUE);
        if (!$this->validateCountry($input)) {
            return $this->unprocessableEntityResponse();
        }
        $this->country->create($input['name']);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = null;
        return $response;
    }

    private function updateCountryFromRequest($id)
    {
        $result = $this->country->find($id);
        if (!$result) {
            return $this->notFoundResponse();
        }
        $input = (array)json_decode(file_get_contents('php://input'), TRUE);
        if (!$this->validateCountry($input)) {
            return $this->unprocessableEntityResponse();
        }
        $this->country->update($id, $input['name']);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }

    private function deleteCountry($id)
    {
        $result = $this->country->find($id);
        if (!$result) {
            return $this->notFoundResponse();
        }
        $this->country->delete($id);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }

    private function validateCountry($input)
    {
        if (!isset($input['name'])) {
            return false;
        }
        return true;
    }

    private function unprocessableEntityResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
        $response['body'] = json_encode([
            'error' => 'Invalid input'
        ]);
        return $response;
    }

    private function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = null;
        return $response;
    }
}
