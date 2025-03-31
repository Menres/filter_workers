<?php
// Подключение к базе данных
$host = 'localhost';
$db = 'brigadalab'; // Имя вашей базы данных
$user = 'root';     // Ваше имя пользователя базы данных
$pass = '';     // Ваш пароль

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

// Функция для получения всех людей с фильтрацией
function getPeople($pdo, $filters = []) {
    $filter_sql = "SELECT p.*, b.name AS brigade_name FROM people p LEFT JOIN brigades b ON p.brigade_id = b.id WHERE 1=1";
    $params = [];

    if (!empty($filters['full_name'])) {
        $filter_sql .= " AND p.full_name LIKE :full_name";
        $params[':full_name'] = "%" . $filters['full_name'] . "%";
    }

    if (!empty($filters['year'])) {
        $filter_sql .= " AND YEAR(p.date_of_birth) = :year";
        $params[':year'] = $filters['year'];
    }

    if (!empty($filters['brigade'])) {
        $filter_sql .= " AND p.brigade_id = :brigade";
        $params[':brigade'] = $filters['brigade'];
    }

    $stmt = $pdo->prepare($filter_sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Функция для получения списка бригад
function getBrigades($pdo) {
    $stmt = $pdo->query("SELECT * FROM brigades");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Функция для добавления нового человека
function addPerson($pdo, $full_name, $brigade_id, $date_of_birth, $photo) {
    $stmt = $pdo->prepare("INSERT INTO people (full_name, brigade_id, date_of_birth, photo) VALUES (:full_name, :brigade_id, :date_of_birth, :photo)");
    $stmt->execute([
        ':full_name' => $full_name,
        ':brigade_id' => $brigade_id,
        ':date_of_birth' => $date_of_birth,
        ':photo' => $photo
    ]);
}

// Функция для удаления человека
function deletePerson($pdo, $id) {
    // Получаем информацию о человеке, чтобы извлечь путь к фото
    $stmt = $pdo->prepare("SELECT photo FROM people WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $person = $stmt->fetch(PDO::FETCH_ASSOC);

    // Удаляем запись из базы данных
    $stmt = $pdo->prepare("DELETE FROM people WHERE id = :id");
    $stmt->execute([':id' => $id]);

    // Удаляем файл изображения, если он существует
    if ($person && file_exists($person['photo'])) {
        unlink($person['photo']);
    }
}

// Функция для получения человека по ID
function getPersonById($pdo, $id) {
    $stmt = $pdo->prepare("SELECT p.*, b.name AS brigade_name FROM people p LEFT JOIN brigades b ON p.brigade_id = b.id WHERE p.id = :id");
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

