<?php

namespace MyApp\Models;

use PDO;
use PDOException;

class Model
{
    protected $db;

    public function __construct()
    {
        $host = 'localhost';
        $dbName = 'todo_list';
        $username = 'root';
        $password = 'root';

        try {
            $this->db = new PDO("mysql:host=$host;dbname=$dbName;charset=utf8", $username, $password);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Ошибка подключения к базе данных: " . $e->getMessage());
        }
    }

    public function getTasks($page, $perPage, $sortBy = null, $sortOrder = 'asc'): bool|array
    {
        $offset = ($page - 1) * $perPage;
        $orderBy = '';

        if ($sortBy) {
            $orderBy = "ORDER BY $sortBy $sortOrder";
        }

        $query = "SELECT * FROM tasks $orderBy LIMIT $offset, $perPage";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalTasks()
    {
        return $this->db->query('SELECT COUNT(*) FROM tasks')->fetchColumn();
    }

    public function getTotalPages($perPage): float
    {
        $totalRows = $this->getTotalTasks();
        return ceil($totalRows / $perPage);
    }

    public function createTask($username, $email, $text)
    {
        $query = "INSERT INTO tasks (username, email, text) VALUES (:username, :email, :text)";
        $stmt = $this->db->prepare($query);
        $stmt->execute(array(
            ':username' => $username,
            ':email' => $email,
            ':text' => $text
        ));
    }

    public function getTaskById($taskId)
    {
        $query = "SELECT * FROM tasks WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(array(':id' => $taskId));
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateTask($taskId, $username, $email, $text, $status)
    {
        $query = "UPDATE tasks SET username = :username, email = :email, text = :text, completed = :status WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(array(
            ':id' => $taskId,
            ':username' => $username,
            ':email' => $email,
            ':text' => $text,
            ':status' => $status
        ));
    }

    public function editedByAdmin($taskId, $updated)
    {
        $query = "UPDATE tasks SET updated = :updated WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(array(
            ':id' => $taskId,
            ':updated' => $updated,
        ));
    }

    public function deleteTask($taskId)
    {
        $query = "DELETE FROM tasks WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(array(':id' => $taskId));
    }
}
