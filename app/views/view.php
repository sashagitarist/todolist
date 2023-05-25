<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>ToDo List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>

<div class="container mt-5">
    <h1>ToDo List</h1>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-dismissible alert-info" role="alert">
            <?= $_SESSION['message']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['admin'])): ?>
        <a href="index.php?action=logout" class="btn btn-danger text-end">Выйти</a>
    <?php else: ?>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#loginModal">
            Войти как администратор
        </button>
    <?php endif; ?>

    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
        Добавить задачу
    </button>

    <?php if ($tasks): ?>

        <h4 class="mt-4">Список задач</h4>
        <div style="overflow-x: auto">
            <table class="table">
                <thead>
                <tr>
                    <th>
                        <a href="index.php?page=<?= $page; ?>&sort_by=username&sort_order=<?= $sortOrder === 'asc' ? 'desc' : 'asc'; ?>">
                            Имя пользователя
                            <?php if ($sortBy === 'username'): ?>
                                <?php if ($sortOrder === 'asc'): ?>
                                    <i class="fas fa-sort-up"></i>
                                <?php else: ?>
                                    <i class="fas fa-sort-down"></i>
                                <?php endif; ?>
                            <?php endif; ?>
                        </a>
                    </th>
                    <th>
                        <a href="index.php?page=<?= $page; ?>&sort_by=email&sort_order=<?= $sortOrder === 'asc' ? 'desc' : 'asc'; ?>">
                            Email
                            <?php if ($sortBy === 'email'): ?>
                                <?php if ($sortOrder === 'asc'): ?>
                                    <i class="fas fa-sort-up"></i>
                                <?php else: ?>
                                    <i class="fas fa-sort-down"></i>
                                <?php endif; ?>
                            <?php endif; ?>
                        </a>
                    </th>
                    <th>Текст задачи</th>
                    <th>
                        <a href="index.php?page=<?= $page; ?>&sort_by=completed&sort_order=<?= $sortOrder === 'asc' ? 'desc' : 'asc'; ?>">
                            Статус
                            <?php if ($sortBy === 'completed'): ?>
                                <?php if ($sortOrder === 'asc'): ?>
                                    <i class="fas fa-sort-up"></i>
                                <?php else: ?>
                                    <i class="fas fa-sort-down"></i>
                                <?php endif; ?>
                            <?php endif; ?>
                        </a>
                    </th>
                    <?php if (isset($_SESSION['admin'])): ?>
                        <th>Действия</th>
                    <?php endif; ?>
                </tr>
                </thead>

                <tbody>
                <?php foreach ($tasks as $task): ?>
                    <tr>
                        <td><?= $task['username']; ?></td>
                        <td><?= $task['email']; ?></td>
                        <td><?= $task['text']; ?></td>
                        <td>
                            <?= $task['completed'] == 1 ? '<span class="badge text-bg-success">Выполнено</span>' : 'В ожидании'; ?>
                            <?= $task['updated'] == 1  && $task['completed'] == 1 ? '<br> <span class="badge text-bg-warning">Отредактировано админом</span>' : '' ?>

                        </td>
                        <?php if (isset($_SESSION['admin'])): ?>
                            <td>
                                <div class="d-flex">
                                    <a href="#" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#editModal"
                                       data-task-id="<?= $task['id']; ?>"
                                       data-username="<?= $task['username']; ?>"
                                       data-email="<?= $task['email']; ?>"
                                       data-text="<?= $task['text']; ?>"
                                       data-status="<?= $task['completed']; ?>">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form action="index.php?action=delete" method="POST">
                                        <input type="hidden" name="id" value="<?= $task['id']; ?>">
                                        <button class="btn btn-danger" id="submitButton" type="submit"><i class="fa fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="text-center fs-2">Отсутствуют задачи</div>
    <?php endif; ?>

    <?php if ($totalPages > 1): ?>
        <nav>
            <ul class="pagination">
                <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="index.php?page=<?= $page - 1; ?>&sort_by=<?= $_GET['sort_by'] ?? ''; ?>&sort_order=<?= $_GET['sort_order'] ?? 'asc'; ?>">
                            Предыдущая
                        </a>
                    </li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= $i == $page ? 'active' : ''; ?>">
                        <a class="page-link" href="index.php?page=<?= $i; ?>&sort_by=<?= $_GET['sort_by'] ?? ''; ?>&sort_order=<?= $_GET['sort_order'] ?? 'asc'; ?>">
                            <?= $i; ?>
                        </a>
                    </li>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <li class="page-item">
                        <a class="page-link" href="index.php?page=<?= $page + 1; ?>&sort_by=<?= $_GET['sort_by'] ?? ''; ?>&sort_order=<?= $_GET['sort_order'] ?? 'asc'; ?>">
                            Следующая
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    <?php endif; ?>
</div>

<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Редактировать задачу</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm" action="index.php?action=update" method="post">
                    <input type="hidden" name="id" id="editId">
                    <div class="mb-3">
                        <label for="editUsername" class="form-label">Имя пользователя</label>
                        <input type="text" name="username" id="editUsername" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="editEmail" class="form-label">Email</label>
                        <input type="email" name="email" id="editEmail" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="editText" class="form-label">Текст задачи</label>
                        <textarea name="text" id="editText" class="form-control" required></textarea>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="status" id="editStatus">
                        <label class="form-check-label" for="editStatus">
                            Выполнено
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">Вход для администратора</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="index.php?action=login" method="post">
                    <div class="mb-3">
                        <label for="username" class="form-label">Имя пользователя</label>
                        <input type="text" name="username" id="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Пароль</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Войти</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Добавить новую задачу</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="index.php?action=create" method="post">
                    <div class="mb-3">
                        <label for="username" class="form-label">Имя пользователя</label>
                        <input type="text" name="username" id="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="text" class="form-label">Текст задачи</label>
                        <textarea name="text" id="text" class="form-control" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Добавить</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
<script src="/app/../public/assets/js/main.js"></script>
</body>
</html>