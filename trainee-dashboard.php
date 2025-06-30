<?php
session_start();

if (!isset($_SESSION['trainee'])) {
    header("Location: trainee-login.php");
    exit();
}

// Connect to database
$conn = new mysqli("localhost", "root", "", "unispa");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get trainee_id from session
$traineeId = $_SESSION['trainee']['trainee_id'] ?? null;

if (!$traineeId) {
    die("Session trainee_id not found.");
}


// Fetch trainee full info
$stmt = $conn->prepare("SELECT * FROM trainee WHERE trainee_id = ?");
$stmt->bind_param("s", $traineeId);
$stmt->execute();
$result = $stmt->get_result();
$trainee = $result->fetch_assoc();
$stmt->close();
$conn->close();

// Set progress bar width based on progress_level
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
  <title>Trainee Dashboard | UniSpa</title>
  <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
    body {
      margin: 0;
      font-family: 'Source Sans Pro', sans-serif;
      background: linear-gradient(to right, #d3cce3, #e9e4f0);
      padding: 20px;
    }

    .container {
      max-width: 800px;
      margin: auto;
      background: #fff;
      border-radius: 20px;
      padding: 30px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }

    h1 {
      text-align: center;
      color: #4B0082;
      margin-bottom: 20px;
    }

    .profile-info p {
      margin: 8px 0;
      font-size: 1em;
    }

    .progress-bar {
      height: 20px;
      background: #eee;
      border-radius: 10px;
      margin-top: 10px;
      overflow: hidden;
    }

    .progress-fill {
      height: 100%;
      border-radius: 10px;
      background: #4B0082;
      width: <?= $progressWidth ?>;
      transition: width 0.4s ease;
    }

    .dashboard-links {
      display: flex;
      justify-content: space-around;
      margin-top: 30px;
      flex-wrap: wrap;
      gap: 15px;
    }

    .dashboard-card {
      flex: 1 1 30%;
      background: #f3f0fa;
      padding: 20px;
      border-radius: 15px;
      text-align: center;
      text-decoration: none;
      color: #333;
      box-shadow: 0 4px 10px rgba(0,0,0,0.05);
      transition: transform 0.3s;
    }

    .dashboard-card:hover {
      transform: translateY(-5px);
      background-color: #e6dcf8;
    }

    .dashboard-card i {
      font-size: 2em;
      color: #4B0082;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Welcome, <?= htmlspecialchars($trainee['trainee_name']) ?>!</h1>

    <div class="profile-info">
      <p><strong>ID:</strong> <?= htmlspecialchars($trainee['trainee_id']) ?></p>
      <p><strong>Email:</strong> <?= htmlspecialchars($trainee['email']) ?></p>
      <p><strong>Phone:</strong> <?= htmlspecialchars($trainee['contact_number']) ?></p>
      <p><strong>Date of Birth:</strong> <?= htmlspecialchars($trainee['date_of_birth']) ?></p>
      <p><strong>Gender:</strong> <?= htmlspecialchars($trainee['gender']) ?></p>
      <p><strong>Enrollment Date:</strong> <?= htmlspecialchars($trainee['enrollment_date']) ?></p>
      <p><strong>Progress Level:</strong> <?= htmlspecialchars($trainee['progress_level']) ?></p>
      <div class="progress-bar">
        <div class="progress-fill"></div>
      </div>
    </div>

    <div class="dashboard-links">
      <a href="trainee-progress.php" class="dashboard-card">
        <i class="fas fa-chart-line"></i>
        <div>View Progress</div>
      </a>
      <a href="trainee-attendance.php" class="dashboard-card">
        <i class="fas fa-calendar-check"></i>
        <div>Mark Attendance</div>
      </a>
      <a href="trainee-logbook.php" class="dashboard-card">
        <i class="fas fa-book"></i>
        <div>Upload Logbook</div>
      </a>
    </div>
<a href="trainee-login.php" class="back-link">&larr; Back</a>
  </div>
</body>
</html>
