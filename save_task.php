<?php
// Koneksi ke database
$host = 'localhost';
$dbname = 'aptavis';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Menyimpan task baru
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $status = $_POST['status'];
    $project_id = $_POST['project_id'];
    $weight = $_POST['weight'];

    $stmt = $pdo->prepare("INSERT INTO tasks (name, status, project_id, weight) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$name, $status, $project_id, $weight])) {
        echo "Task saved successfully!";
    } else {
        echo "Failed to save task!";
    }
}
?>
