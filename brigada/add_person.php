<?php
include 'logic.php';
include 'templates/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $_POST['full_name'];
    $brigade_id = $_POST['brigade_id'];
    $date_of_birth = $_POST['date_of_birth'];
    $photo = $_FILES['photo']['name'];
    
    // Загрузка фото
    $target_dir = "uploads/";
    move_uploaded_file($_FILES['photo']['tmp_name'], $target_dir . $photo);

    // Добавление нового человека
    addPerson($pdo, $full_name, $brigade_id, $date_of_birth, $target_dir . $photo);

    header("Location: index.php");
    exit;
}

// Получение списка бригад для формы
$brigades = getBrigades($pdo);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить нового человека</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Добавить нового человека</h1>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="full_name">ФИО</label>
                <input type="text" class="form-control" id="full_name" name="full_name" required>
            </div>
            <div class="form-group">
                <label for="brigade_id">Бригада</label>
                <select class="form-control" id="brigade_id" name="brigade_id">
                    <?php foreach ($brigades as $brigade): ?>
                        <option value="<?= $brigade['id']; ?>"><?= $brigade['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="date_of_birth">Дата рождения</label>
                <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" required>
            </div>
            <div class="form-group">
                <label for="photo">Фото</label>
                <input type="file" class="form-control" id="photo" name="photo" accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-success">Добавить</button>
            <a href="index.php" class="btn btn-secondary">Назад</a>
        </form>
    </div>
</body>
</html>
<?php include 'templates/footer.php'; ?>