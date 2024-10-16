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

// Menyimpan project
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $status = $_POST['status'];
    $completion_progress = $_POST['completion_progress'];

    $stmt = $pdo->prepare("INSERT INTO projects (name, status, completion_progress) VALUES (?, ?, ?)");
    $stmt->execute([$name, $status, $completion_progress]);

    echo "Project saved successfully!";
}
?>
