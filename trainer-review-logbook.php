<?php
session_start();
if (!isset($_SESSION['trainer'])) {
    header("Location: trainer-login.php");
    exit();
}

$traineeId = $_GET['traineeId'] ?? '';
$logbooks = [];

$conn = new mysqli("localhost", "root", "", "unispa");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($traineeId) {
    $stmt = $conn->prepare("
    SELECT l.*, w.title AS workshop_title, w.date AS workshop_date
    FROM logbook l
    LEFT JOIN workshop w ON l.workshop_id = w.workshop_id
    WHERE l.trainee_id = ?
    ORDER BY l.date_submitted DESC
");
$stmt->bind_param("s", $traineeId);
$stmt->execute();
    $logbooks = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Review Logbooks | UniSpa</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(to right, #f8f8ff, #e9e4f0);
      margin: 0;
      padding: 30px;
    }

    .container {
      max-width: 900px;
      margin: auto;
      background: #fff;
      padding: 30px;
      border-radius: 20px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    h1 {
      text-align: center;
      color: #4B0082;
      margin-bottom: 30px;
    }

    .logbook-entry {
      border: 1px solid #ccc;
      border-radius: 10px;
      padding: 20px;
      margin-bottom: 20px;
      background: #fafafa;
    }

    .logbook-entry h3 {
      margin: 0 0 10px;
      color: #4B0082;
    }

    .logbook-entry p {
      margin: 5px 0;
    }

    .no-record {
      color: red;
      text-align: center;
    }

    .back-button {
      display: inline-block;
      background: #4B0082;
      color: white;
      padding: 10px 20px;
      text-decoration: none;
      border-radius: 8px;
      margin-top: 20px;
      font-weight: 600;
    }

    .back-button:hover {
      background-color: #6a0dad;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1><i class="fas fa-book-open"></i> Logbook Entries</h1>

    <?php if ($traineeId && $logbooks): ?>
      <?php foreach ($logbooks as $entry): ?>
        <div class="logbook-entry">
          <h3>Submitted on <?= htmlspecialchars($entry['date_submitted']) ?></h3>

          <p><strong>Workshop:</strong>
            <?php if (!empty($entry['workshop_title'])): ?>
              <?= htmlspecialchars($entry['workshop_title']) ?> (<?= htmlspecialchars($entry['workshop_date']) ?>)
            <?php else: ?>
              <em>Not linked to any workshop</em>
            <?php endif; ?>
          </p>

          <p><strong>Content:</strong></p>
          <p><?= nl2br(htmlspecialchars($entry['content'])) ?></p>
        </div>
      <?php endforeach; ?>
    <?php elseif ($traineeId): ?>
      <p class="no-record">No logbook entries found for this trainee.</p>
    <?php else: ?>
      <p class="no-record">No trainee selected.</p>
    <?php endif; ?>

    <a href="trainer-view-trainee.php" class="back-button"><i class="fas fa-arrow-left"></i> Back to Trainee Records</a>
  </div>
</body>
</html>
