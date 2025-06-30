<?php
session_start();

if (!isset($_SESSION['trainee'])) {
    header("Location: trainee-login.php");
    exit();
}

$traineeId = $_SESSION['trainee']['trainee_id'] ?? null;

$conn = new mysqli("localhost", "root", "", "unispa");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get trainee info
$stmt = $conn->prepare("SELECT trainee_name, progress_level FROM trainee WHERE trainee_id = ?");
$stmt->bind_param("s", $traineeId);
$stmt->execute();
$trainee = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Get attended workshops
$stmt2 = $conn->prepare("
    SELECT w.title, w.description, w.date, w.location 
    FROM attendance a 
    JOIN workshop w ON a.workshop_id = w.workshop_id 
    WHERE a.trainee_id = ? AND a.status = 'Present'
    ORDER BY w.date DESC
");
$stmt2->bind_param("s", $traineeId);
$stmt2->execute();
$workshops = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt2->close();
$conn->close();

// Calculate progress bar
$progressWidth = '30%';
if ($trainee['progress_level'] === 'Intermediate') {
    $progressWidth = '60%';
} elseif ($trainee['progress_level'] === 'Advanced') {
    $progressWidth = '100%';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Training Progress | UniSpa</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      margin: 0;
      font-family: 'Source Sans Pro', sans-serif;
      background: linear-gradient(to right, #f5f5fa, #e4e0f4);
      padding: 30px;
    }

    .container {
      max-width: 800px;
      margin: auto;
      background: #fff;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }

    h1 {
      text-align: center;
      color: #4B0082;
      margin-bottom: 20px;
    }

    .progress-section {
      margin-bottom: 30px;
    }

    .progress-bar {
      height: 20px;
      background: #ddd;
      border-radius: 10px;
      overflow: hidden;
    }

    .progress-fill {
      height: 100%;
      background: #4B0082;
      width: <?= $progressWidth ?>;
      transition: width 0.5s;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
      background-color: #fff;
    }

    th, td {
      padding: 12px;
      border: 1px solid #ccc;
      text-align: left;
    }

    th {
      background-color: #ede7f6;
      color: #4B0082;
    }

    .back-link {
      display: inline-block;
      margin-top: 20px;
      color: #4B0082;
      text-decoration: none;
    }

    .back-link:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1><i class="fas fa-chart-line"></i> Training Progress</h1>

    <div class="progress-section">
      <p><strong>Trainee:</strong> <?= htmlspecialchars($trainee['trainee_name']) ?></p>
      <p><strong>Progress Level:</strong> <?= htmlspecialchars($trainee['progress_level']) ?></p>
      <div class="progress-bar">
        <div class="progress-fill"></div>
      </div>
    </div>

    <h2>Completed Workshops</h2>

    <?php if (!empty($workshops)): ?>
      <table>
        <thead>
          <tr>
            <th>Title</th>
            <th>Description</th>
            <th>Date</th>
            <th>Location</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($workshops as $w): ?>
            <tr>
              <td><?= htmlspecialchars($w['title']) ?></td>
              <td><?= htmlspecialchars($w['description']) ?></td>
              <td><?= htmlspecialchars($w['date']) ?></td>
              <td><?= htmlspecialchars($w['location']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>No completed workshops yet.</p>
    <?php endif; ?>

    <a href="trainee-dashboard.php" class="back-link">&larr; Back to Dashboard</a>
  </div>
</body>
</html>
