<?php
session_start();
if (!isset($_SESSION['trainer'])) {
    header("Location: trainer-login.php");
    exit();
}

$trainer_name = $_SESSION['trainer']['name'];
$traineeData = null;
$attendance = [];

$conn = new mysqli("localhost", "root", "", "unispa");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$traineeId = $_GET['traineeId'] ?? '';

// Get specific trainee info (if searched)
if ($traineeId) {
    $stmt = $conn->prepare("SELECT * FROM trainee WHERE trainee_id = ?");
    $stmt->bind_param("s", $traineeId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        $traineeData = $result->fetch_assoc();
    }
    $stmt->close();

    // Get attendance
    $stmt2 = $conn->prepare("SELECT w.title, w.date, a.status 
                             FROM attendance a 
                             JOIN workshop w ON a.workshop_id = w.workshop_id 
                             WHERE a.trainee_id = ?");
    $stmt2->bind_param("s", $traineeId);
    $stmt2->execute();
    $attendance = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt2->close();
}

// Get all trainees
$allTrainees = $conn->query("SELECT * FROM trainee ORDER BY trainee_id ASC");
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>View Trainees | UniSpa</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      margin: 0;
      font-family: 'Source Sans Pro', sans-serif;
      background: linear-gradient(to right, #f8f8ff, #e9e4f0);
      padding: 20px;
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
      margin-bottom: 20px;
    }

    .form-group {
      margin-bottom: 20px;
    }

    label {
      font-weight: 600;
      color: #333;
    }

    input {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 1em;
      background-color: #f9f9f9;
    }

    input:focus {
      border-color: #4B0082;
      background-color: #fff;
    }

    button {
      background: #4B0082;
      color: white;
      padding: 10px 15px;
      border: none;
      border-radius: 8px;
      font-size: 1em;
      font-weight: 600;
      cursor: pointer;
      margin-top: 10px;
    }

    button:hover {
      background-color: #6a0dad;
    }

    .result-box, .trainee-table {
      margin-top: 30px;
    }

    .result-box h2 {
      color: #4B0082;
      margin-bottom: 10px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
      background: #fff;
    }

    th, td {
      padding: 12px;
      border: 1px solid #ccc;
      text-align: left;
    }

    th {
      background-color: #e8ddf7;
    }

    ul {
      padding-left: 20px;
    }

    .no-record {
      color: red;
      margin-top: 10px;
    }

    .logbook-button {
  display: inline-block;
  margin-top: 10px;
  background: #4B0082;
  color: white;
  padding: 10px 15px;
  border-radius: 8px;
  font-weight: 600;
  text-decoration: none;
}

.logbook-button:hover {
  background-color: #6a0dad;
}

  </style>
</head>
<body>
  <div class="container">
    <h1><i class="fas fa-user-graduate"></i> Trainee Records</h1>

    <form method="get" action="trainer-view-trainee.php">
      <div class="form-group">
        <label for="traineeId">Search Trainee by ID:</label>
        <input type="text" id="traineeId" name="traineeId" placeholder="Enter Trainee ID" value="<?= htmlspecialchars($traineeId) ?>" />
        <button type="submit"><i class="fas fa-search"></i> Search</button>
      </div>
    </form>

    <?php if ($traineeData): ?>
      <div class="result-box">
        <h2>Trainee Info</h2>
        <p><strong>ID:</strong> <?= htmlspecialchars($traineeData['trainee_id']) ?></p>
        <p><strong>Name:</strong> <?= htmlspecialchars($traineeData['trainee_name']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($traineeData['email']) ?></p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($traineeData['contact_number']) ?></p>

        <h3>Attendance</h3>
<?php if (!empty($attendance)): ?>
  <table>
    <thead>
      <tr>
        <th>Workshop Title</th>
        <th>Date</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($attendance as $att): ?>
        <tr>
          <td><?= htmlspecialchars($att['title']) ?></td>
          <td><?= htmlspecialchars($att['date']) ?></td>
          <td><?= htmlspecialchars($att['status']) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php else: ?>
  <p>No attendance records found.</p>
<?php endif; ?>


        <h3>Logbook</h3>
        <a href="trainer-review-logbook.php?traineeId=<?= urlencode($traineeData['trainee_id']) ?>" class="logbook-button">
          <i class="fas fa-book-open"></i> Review Logbook
        </a>
      </div>
    <?php elseif ($traineeId): ?>
      <p class="no-record">Trainee not found.</p>
    <?php endif; ?>

    <div class="trainee-table">
      <h2>All Trainees</h2>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($allTrainees->num_rows > 0): ?>
            <?php while($row = $allTrainees->fetch_assoc()): ?>
              <tr>
                <td><?= htmlspecialchars($row['trainee_id']) ?></td>
                <td><?= htmlspecialchars($row['trainee_name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['contact_number']) ?></td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="4">No trainees found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>
