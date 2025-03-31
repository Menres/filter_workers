<?php
include 'logic.php';
include 'templates/header.php';

// Получение фильтров из формы
$filters = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $filters['full_name'] = $_POST['full_name'] ?? '';
    $filters['year'] = $_POST['year'] ?? '';
    $filters['brigade'] = $_POST['brigade'] ?? '';
}

// Удаление человека
if (isset($_GET['delete'])) {
    deletePerson($pdo, $_GET['delete']);
    header("Location: index.php");
    exit;
}

$people = getPeople($pdo, $filters);
$brigades = getBrigades($pdo);
?>

<div class="container mt-5">
    <h1 class="text-center mb-4">Список людей</h1>

    <!-- Форма фильтрации -->
    <form method="POST" class="mb-4">
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="full_name">ФИО</label>
                <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Введите ФИО">
            </div>
            <div class="form-group col-md-4">
                <label for="year">Год рождения</label>
                <input type="number" class="form-control" id="year" name="year" placeholder="Введите год рождения">
            </div>
            <div class="form-group col-md-4">
                <label for="brigade">Бригада</label>
                <select class="form-control" id="brigade" name="brigade">
                    <option value="">Все</option>
                    <?php foreach ($brigades as $brigade): ?>
                        <option value="<?= $brigade['id']; ?>"><?= $brigade['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Фильтровать</button>
        <a href="index.php" class="btn btn-secondary">Сбросить фильтры</a>
    </form>

    <!-- Таблица с данными -->
    <table class="table table-hover table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>Фото</th>
                <th>ФИО</th>
                <th>Бригада</th>
                <th>Дата рождения</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($people as $person): ?>
                <tr>
                    <td><img src="<?= $person['photo']; ?>" alt="Фото" width="100" class="img-thumbnail"></td>
                    <td><?= htmlspecialchars($person['full_name']); ?></td>
                    <td><?= htmlspecialchars($person['brigade_name']); ?></td>
                    <td><?= htmlspecialchars($person['date_of_birth']); ?></td>
                    <td>
                        <a href="person.php?id=<?= $person['id']; ?>" class="btn btn-info btn-sm">Подробнее</a>
                        <a href="?delete=<?= $person['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Вы уверены, что хотите удалить?');">Удалить</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="text-center">
        <a href="add_person.php" class="btn btn-success btn-lg">Добавить нового человека</a>
    </div>
</div>

<?php include 'templates/footer.php'; ?>
