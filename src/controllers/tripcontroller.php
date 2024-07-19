<?php

require_once __DIR__ . '/../Models/Trip.php';

class TripController {
    public function create() {
        $data = json_decode(file_get_contents('php://input'), true);
        $trip = new Trip();
        $id = $trip->create($data['available_seats'], $data['countries']);
        echo json_encode(['id' => $id]);
    }

    public function update($id) {
        $data = json_decode(file_get_contents('php://input'), true);
        $trip = new Trip();
        $trip->update($id, $data['available_seats'], $data['countries']);
        http_response_code(204);
    }

    public function delete($id) {
        $trip = new Trip();
        $trip->delete($id);
        http_response_code(204);
    }

    public function getAll() {
        $trip = new Trip();
        $trips = $trip->getAll();
        echo json_encode($trips);
    }

    public function filter() {
        $trip = new Trip();
        $trips = $trip->filter($_GET);
        echo json_encode($trips);
    }
}