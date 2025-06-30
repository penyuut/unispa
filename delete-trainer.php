<?php
session_start();

// Only admin can delete
if (!isset($_SESSION['admin'])) {
    header("Location: admin-login.php");
    exit();
}

// Check if ID is provided
if (!isset($_GET['id'])) {
    echo "No trainer ID provided.";
    exit();
}

$trainer_id = $_GET['id'];

// Connect to DB
$conn = new mysqli("localhost", "root", "", "unispa");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and execute delete
$stmt = $conn->prepare("DELETE FROM trainer WHERE trainer_id = ?");
$stmt->bind_param("s", $trainer_id);
$stmt->execute();

$stmt->close();
$conn->close();

// Redirect back
header("Location: manage-trainer.php");
exit();
?>
