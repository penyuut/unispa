<?php
// 1. Connect to database
$host = "localhost";
$user = "root";
$pass = "";
$db   = "unispoa_db";
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// 2. Simulate trainee_id (replace with session ID in real use)
$trainee_id = 1;

// 3. Check if file is uploaded
if (isset($_FILES['logbook'])) {
  $file = $_FILES['logbook'];
  $filename = basename($file['name']);
  $targetDir = "logbook_uploads/";
  $targetFile = $targetDir . uniqid() . "_" . $filename;

  if (move_uploaded_file($file["tmp_name"], $targetFile)) {
    // 4. Save to database
    $stmt = $conn->prepare("INSERT INTO trainee_logbooks (trainee_id, filename, uploaded_at) VALUES (?, ?, NOW())");
    $stmt->bind_param("is", $trainee_id, $targetFile);
    $stmt->execute();

    echo "<script>alert('Logbook uploaded successfully!'); window.location.href='upload-logbook.html';</script>";
  } else {
    echo "<script>alert('Failed to upload file.'); window.location.href='upload-logbook.html';</script>";
  }
} else {
  echo "<script>alert('No file was selected.'); window.location.href='upload-logbook.html';</script>";
}

$conn->close();
?>
