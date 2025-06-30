<?php
session_start();
if (!isset($_SESSION['trainee'])) {
    header("Location: trainee-login.php");
    exit();
}

$traineeID = $_SESSION['trainee']['trainee_id'] ?? null;

$conn = new mysqli("localhost", "root", "", "unispa");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle attendance submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['attendance'] as $workshopId => $status) {
        if ($status !== '') {
            $check = $conn->prepare("SELECT * FROM attendance WHERE trainee_id = ? AND workshop_id = ?");
            $check->bind_param("ss", $traineeID, $workshopId);
            $check->execute();
            $result = $check->get_result();

            if ($result->num_rows === 0) {
                $stmt = $conn->prepare("INSERT INTO attendance (trainee_id, workshop_id, status) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $traineeID, $workshopId, $status);
                $stmt->execute();
                $stmt->close();
            }
            $check->close();
        }
    }
    echo "<script>alert('Attendance submitted!');window.location.href='trainee-attendance.php';</script>";
    exit();
}

// Workshops not yet attended
$workshops = $conn->query("
    SELECT * FROM workshop 
    WHERE workshop_id NOT IN (
        SELECT workshop_id FROM attendance WHERE trainee_id = '$traineeID'
    ) 
    ORDER BY date ASC
");

// Workshops already marked (present or absent)
$stmt = $conn->prepare("
    SELECT w.date, w.title, w.description, w.location, a.status 
    FROM attendance a 
    JOIN workshop w ON a.workshop_id = w.workshop_id 
    WHERE a.trainee_id = ? 
    ORDER BY w.date DESC
");
$stmt->bind_param("s", $traineeID);
$stmt->execute();
$attended = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Trainee Attendance | UniSpa</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;600&display=swap" rel="stylesheet">
  
  <style>
    body {
      font-family: 'Source Sans Pro', sans-serif;
      background: linear-gradient(to right, #e9e4f0, #d3cce3);
      padding: 40px;
    }

    .container {
      max-width: 1000px;
      margin: auto;
      background: white;
      padding: 30px;
      border-radius: 20px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    h1, h2 {
      text-align: center;
      color: #4B0082;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 25px;
    }

    table th, table td {
      padding: 12px;
      border: 1px solid #ccc;
      text-align: left;
    }

    table th {
      background-color: #f3f0fa;
    }

    select {
      padding: 8px;
      border-radius: 8px;
      border: 1px solid #ccc;
    }

    .submit-btn {
      background: #4B0082;
      color: white;
      padding: 12px 25px;
      border: none;
      border-radius: 8px;
      font-size: 1em;
      font-weight: 600;
      cursor: pointer;
      display: block;
      margin: 0 auto 40px;
    }

    .submit-btn:hover {
      background-color: #6a0dad;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Workshop Attendance</h1>
    <form method="post">
      <table>
        <thead>
          <tr>
            <th>Date</th>
            <th>Title</th>
            <th>Description</th>
            <th>Location</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($workshops->num_rows > 0): ?>
            <?php while ($w = $workshops->fetch_assoc()): ?>
              <tr>
                <td><?= htmlspecialchars($w['date']) ?></td>
                <td><?= htmlspecialchars($w['title']) ?></td>
                <td><?= htmlspecialchars($w['description']) ?></td>
                <td><?= htmlspecialchars($w['location']) ?></td>
                <td>
                  <select name="attendance[<?= $w['workshop_id'] ?>]">
                    <option value="">-- Select --</option>
                    <option value="Present">Present</option>
                    <option value="Absent">Absent</option>
                  </select>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="5">You have responded to all workshops.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
      <?php if ($workshops->num_rows > 0): ?>
        <button class="submit-btn" type="submit">Submit Attendance</button>
      <?php endif; ?>
    </form>

    <h2>Workshops You Have Responded To</h2>
    <table>
      <thead>
        <tr>
          <th>Date</th>
          <th>Title</th>
          <th>Description</th>
          <th>Location</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($attended->num_rows > 0): ?>
          <?php while ($a = $attended->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($a['date']) ?></td>
              <td><?= htmlspecialchars($a['title']) ?></td>
              <td><?= htmlspecialchars($a['description']) ?></td>
              <td><?= htmlspecialchars($a['location']) ?></td>
              <td><?= htmlspecialchars($a['status']) ?></td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="5">You haven't submitted attendance for any workshops yet.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
<a href="trainee-dashboard.php" class="back-link">&larr; Back to Dashboard</a>
  </div>
</body>
</html>

<?php $conn->close(); ?>
