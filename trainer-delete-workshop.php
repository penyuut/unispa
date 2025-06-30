<?php
session_start();
if (!isset($_SESSION['trainer'])) {
    header("Location: trainer-login.php");
    exit();
}

$trainer_id = $_SESSION['trainer']['id'];

// Validate workshop ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: trainer-manage-workshop.php");
    exit();
}

$workshop_id = intval($_GET['id']);

// Connect to database
$conn = new mysqli("localhost", "root", "", "unispa");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Verify ownership before deleting
$stmt = $conn->prepare("SELECT * FROM workshop WHERE workshop_id = ? AND trainer_id = ?");
$stmt->bind_param("ii", $workshop_id, $trainer_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    $stmt->close();
    $conn->close();
    header("Location: trainer-manage-workshop.php?error=notfound");
    exit();
}

// Proceed to delete
$stmt->close();
$stmt = $conn->prepare("DELETE FROM workshop WHERE workshop_id = ? AND trainer_id = ?");
$stmt->bind_param("ii", $workshop_id, $trainer_id);
$stmt->execute();
$stmt->close();
$conn->close();

header("Location: trainer-manage-workshop.php?deleted=success");
exit();
?>
