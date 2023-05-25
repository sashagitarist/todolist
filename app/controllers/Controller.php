<?php

namespace MyApp\Controllers;

use MyApp\models\Model;

class Controller
{
    protected Model $model;

    public function __construct()
    {
        $this->model = new Model();
    }

    public function indexAction()
    {
        $page = $_GET['page'] ?? 1;
        $sortBy = $_GET['sort_by'] ?? null;
        $sortOrder = $_GET['sort_order'] ?? 'asc';
        $perPage = 3;
        $tasks = $this->model->getTasks($page, $perPage, $sortBy, $sortOrder);
        $totalPages = $this->model->getTotalPages($perPage);
        include __DIR__ . '/../views/view.php';
    }

    public function createAction()
    {
        $username = htmlspecialchars($_POST['username'], ENT_QUOTES);
        $email = $_POST['email'];
        $text = htmlspecialchars($_POST['text'], ENT_QUOTES);

        $this->model->createTask($username, $email, $text);

        $_SESSION['message'] = 'Задача успешно создана!';
        header('Location: index.php');
    }

    public function loginAction()
    {
        $username = $_POST['username'];
        $password = $_POST['password'];

        if ($username === 'admin' && $password === '123') {
            $_SESSION['admin'] = true;
        } else {
            $_SESSION['message'] = 'Неправильные имя пользователя или пароль';
        }

        header('Location: index.php');
    }

    public function logoutAction()
    {
        unset($_SESSION['admin']);
        header('Location: index.php');
    }

    public function editAction()
    {
        if (!isset($_SESSION['admin'])) {
            header('Location: index.php');
            return;
        }
        $taskId = $_GET['id'];
        $task = $this->model->getTaskById($taskId);

        include __DIR__ . '/../views/view.php';
    }

    public function updateAction()
    {
        if (!isset($_SESSION['admin'])) {
            header('Location: index.php');
            return;
        }

        $taskId = $_POST['id'];
        $username = htmlspecialchars($_POST['username'], ENT_QUOTES);
        $email = $_POST['email'];
        $text = htmlspecialchars($_POST['text'], ENT_QUOTES);
        $status = isset($_POST['status']) ? 1 : 0;
        $task = $this->model->getTaskById($taskId);
        $task['text'] != $text ? $this->model->editedByAdmin($taskId, 1) : $this->model->editedByAdmin($taskId, 0);
        $this->model->updateTask($taskId, $username, $email, $text, $status);

        $_SESSION['message'] = 'Задача успешно обновлена!';
        header('Location: index.php');
    }

    public function deleteAction()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_SESSION['admin'])) {
                header('Location: index.php');
                return;
            }
            $taskId = $_POST['id'];
            $this->model->deleteTask($taskId);
            $_SESSION['message'] = 'Задача успешно удалена!';
            header('Location: index.php');
        }
    }
}
