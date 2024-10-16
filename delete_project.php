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

// Menghapus project
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $project_id = $_POST['id'];

    $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
    if ($stmt->execute([$project_id])) {
        echo "Project deleted successfully!";
    } else {
        echo "Failed to delete project!";
    }
}
?>
