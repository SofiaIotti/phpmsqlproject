<?php

require_once __DIR__ . '/../models/Trip.php';

class TripController {
    private $requestMethod;
    private $tripId;
    private $trip;

    public function __construct($requestMethod, $tripId = null)
    {
        $this->requestMethod = $requestMethod;
        $this->tripId = $tripId;
        $this->trip = new Trip();
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if ($this->tripId) {
                    $response = $this->getTrip($this->tripId);
                } else {
                    $response = $this->getAllTrips();
                }
                break;
            case 'POST':
                $response = $this->createTripFromRequest();
                break;
            case 'PUT':
                $response = $this->updateTripFromRequest($this->tripId);
                break;
            case 'DELETE':
                $response = $this->deleteTrip($this->tripId);
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

    private function getAllTrips()
    {
        $result = $this->trip->getAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function getTrip($id)
    {
        $result = $this->trip->find($id);
        if (!$result) {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function createTripFromRequest()
    {
        $input = (array)json_decode(file_get_contents('php://input'), TRUE);
        if (!$this->validateTrip($input)) {
            return $this->unprocessableEntityResponse();
        }
        $this->trip->create($input['available_seats'], $input['countries']);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = null;
        return $response;
    }

    private function updateTripFromRequest($id)
    {
        $result = $this->trip->find($id);
        if (!$result) {
            return $this->notFoundResponse();
        }
        $input = (array)json_decode(file_get_contents('php://input'), TRUE);
        if (!$this->validateTrip($input)) {
            return $this->unprocessableEntityResponse();
        }
        $this->trip->update($id, $input['available_seats'], $input['countries']);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }

    private function deleteTrip($id)
    {
        $result = $this->trip->find($id);
        if (!$result) {
            return $this->notFoundResponse();
        }
        $this->trip->delete($id);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }

    private function validateTrip($input)
    {
        if (!isset($input['available_seats']) || !isset($input['countries'])) {
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
