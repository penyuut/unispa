<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin-login.php");
    exit();
}

$id = $_GET['id'];
$conn = new mysqli("localhost", "root", "", "unispa");
$stmt = $conn->prepare("DELETE FROM trainee WHERE trainee_id = ?");
$stmt->bind_param("s", $id);
$stmt->execute();
$stmt->close();

$conn->close();

header("Location: manage-trainee.php");
exit();
?>
