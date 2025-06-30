<?php
session_start();
if (!isset($_POST['attendance']) || !isset($_POST['trainee_id'])) {
    die("Invalid submission.");
}

$traineeID = $_POST['trainee_id'];
$workshopIDs = $_POST['attendance']; // array

$conn = new mysqli("localhost", "root", "", "unispa");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("INSERT INTO attendance (trainee_id, workshop_id) VALUES (?, ?)");
foreach ($workshopIDs as $wid) {
    $stmt->bind_param("ii", $traineeID, $wid);
    $stmt->execute();
}
$stmt->close();
$conn->close();

header("Location: trainee-dashboard.php");
exit();
