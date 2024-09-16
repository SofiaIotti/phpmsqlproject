<?php

require_once __DIR__ . '/../models/country.php';

class CountryController {
    public function create() {
        $data = json_decode(file_get_contents('php://input'), true);
        $country = new Country();
        $id = $country->create($data['name']);
        echo json_encode(['id' => $id]);
    }

    public function update($id) {
        $data = json_decode(file_get_contents('php://input'), true);
        $country = new Country();
        $country->update($id, $data['name']);
        http_response_code(204);
    }

    public function delete($id) {
        $country = new Country();
        $country->delete($id);
        http_response_code(204);
    }
}
