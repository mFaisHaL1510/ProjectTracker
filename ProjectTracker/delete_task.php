<?php
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

// Menghapus task
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task_id = $_POST['id'];

    $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ?");
    if ($stmt->execute([$task_id])) {
        echo "Task deleted successfully!";
    } else {
        echo "Failed to delete task!";
    }
}
?>
