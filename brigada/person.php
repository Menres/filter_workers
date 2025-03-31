<?php
include 'logic.php';
include 'templates/header.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$person = getPersonById($pdo, $_GET['id']);

if (!$person) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Детали человека</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1><?= htmlspecialchars($person['full_name']); ?></h1>
        <img src="<?= $person['photo']; ?>" alt="Фото" width="200">
        <p><strong>Бригада:</strong> <?= htmlspecialchars($person['brigade_name']); ?></p>
        <p><strong>Дата рождения:</strong> <?= htmlspecialchars($person['date_of_birth']); ?></p>
        <a href="index.php" class="btn btn-secondary">Назад</a>
    </div>
</body>
</html>
<?php include 'templates/footer.php'; ?>