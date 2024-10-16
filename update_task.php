<?php
$host = 'localhost';
$db = 'aptavis';
$user = 'root'; 
$pass = ''; 

$mysqli = new mysqli($host, $user, $pass, $db);

// Cek koneksi
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Ambil data dari form
$taskId = $_POST['taskId'];
$taskName = $_POST['editTaskName'];
$taskStatus = $_POST['editTaskStatus'];
$taskProject = $_POST['editTaskProject'];
$taskWeight = $_POST['editTaskWeight'];

$stmt = $mysqli->prepare("UPDATE tasks SET name = ?, status = ?, project_id = ?, weight = ? WHERE id = ?");
$stmt->bind_param("ssssi", $taskName, $taskStatus, $taskProject, $taskWeight, $taskId);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "Tugas berhasil diperbarui!";
} else {
    echo "Gagal memperbarui tugas: " . $mysqli->error;
}

// Tutup koneksi
$stmt->close();
$mysqli->close();
?>