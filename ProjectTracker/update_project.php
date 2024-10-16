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

$projectId = $_POST['projectId'];
$projectName = $_POST['editProjectName'];
$projectStatus = $_POST['editProjectStatus'];
$completionProgress = $_POST['editCompletionProgress'];
$completionProgress = $_POST['completion_progress'];

$query = "UPDATE projects SET completion_progress = $completionProgress WHERE id = $projectId";
mysqli_query($conn, $query);

$query = "UPDATE projects SET name = '$projectName', status = '$projectStatus', completion_progress = '$completionProgress' WHERE id = '$projectId'";

if ($mysqli->query($query) === TRUE) {
    echo "Data project berhasil diupdate";
} else {
    echo "Error updating record: " . $mysqli->error;
}

$mysqli->close();
?>