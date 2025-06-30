<?php
$conn = new mysqli("localhost", "root", "", "unispa");

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM staff WHERE staff_id = ?");
    $stmt->bind_param("s", $id); // "s" means string
    $stmt->execute();
    $stmt->close();
}

$conn->close();
header("Location: manage-staff.php");
exit();
?>
