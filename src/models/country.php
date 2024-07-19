<?php

require_once __DIR__ . '/../database.php';

class Country
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function create($name)
    {
        $stmt = $this->pdo->prepare('INSERT INTO countries (name) VALUES (:name)');
        $stmt->bindParam(':name', $name);
        $stmt->execute();
        return $this->pdo->lastInsertId();
    }

    public function update($id, $name)
    {
        $stmt = $this->pdo->prepare('UPDATE countries SET name = :name WHERE id = :id');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM countries WHERE id = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
}
