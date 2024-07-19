<?php

require_once __DIR__ . '/../database.php';

class Trip
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function create($availableSeats, $countries)
    {
        try {
            $this->pdo->beginTransaction();
            $stmt = $this->pdo->prepare('INSERT INTO trips (available_seats) VALUES (:available_seats)');
            $stmt->bindParam(':available_seats', $availableSeats, PDO::PARAM_INT);
            $stmt->execute();
            $tripId = $this->pdo->lastInsertId();

            $stmt = $this->pdo->prepare('INSERT INTO trip_countries (trip_id, country_id) VALUES (:trip_id, :country_id)');
            foreach ($countries as $countryId) {
                $stmt->bindParam(':trip_id', $tripId, PDO::PARAM_INT);
                $stmt->bindParam(':country_id', $countryId, PDO::PARAM_INT);
                $stmt->execute();
            }
            $this->pdo->commit();
            return $tripId;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function update($id, $availableSeats, $countries)
    {
        try {
            $this->pdo->beginTransaction();
            $stmt = $this->pdo->prepare('UPDATE trips SET available_seats = :available_seats WHERE id = :id');
            $stmt->bindParam(':available_seats', $availableSeats, PDO::PARAM_INT);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $stmt = $this->pdo->prepare('DELETE FROM trip_countries WHERE trip_id = :id');
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $stmt = $this->pdo->prepare('INSERT INTO trip_countries (trip_id, country_id) VALUES (:trip_id, :country_id)');
            foreach ($countries as $countryId) {
                $stmt->bindParam(':trip_id', $id, PDO::PARAM_INT);
                $stmt->bindParam(':country_id', $countryId, PDO::PARAM_INT);
                $stmt->execute();
            }
            $this->pdo->commit();
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM trips WHERE id = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function getAll()
    {
        $stmt = $this->pdo->prepare(
            'SELECT trips.id, trips.available_seats, GROUP_CONCAT(countries.name) AS countries
             FROM trips
             JOIN trip_countries ON trips.id = trip_countries.trip_id
             JOIN countries ON countries.id = trip_countries.country_id
             GROUP BY trips.id'
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function filter($params)
    {
        $query = '
            SELECT trips.id, trips.available_seats, GROUP_CONCAT(countries.name) AS countries
            FROM trips
            JOIN trip_countries ON trips.id = trip_countries.trip_id
            JOIN countries ON countries.id = trip_countries.country_id
            WHERE 1 = 1
        ';

        $conditions = [];
        $bindings = [];
        $types = '';

        if (isset($params['country'])) {
            $conditions[] = 'countries.name = :country';
            $bindings[':country'] = $params['country'];
        }

        if (isset($params['seats'])) {
            $conditions[] = 'trips.available_seats >= :seats';
            $bindings[':seats'] = $params['seats'];
        }

        if (!empty($conditions)) {
            $query .= ' AND ' . implode(' AND ', $conditions);
        }

        $query .= ' GROUP BY trips.id';

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($bindings);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
