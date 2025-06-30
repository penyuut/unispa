<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin-login.php");
    exit();
}

// Connect to database
$conn = new mysqli("localhost", "root", "", "unispa");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch trainee list
$traineeList = [];
$result = $conn->query("SELECT * FROM trainee");
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $traineeList[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Trainees | UniSpa</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f0e8ff;
      padding: 40px;
    }

    h1 {
      text-align: center;
      color: #4B0082;
      margin-bottom: 30px;
    }

    .actions {
      text-align: center;
      margin-bottom: 20px;
    }

    .actions a button {
      background-color: #4B0082;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-weight: bold;
    }

    table {
      width: 95%;
      margin: auto;
      border-collapse: collapse;
      background: white;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    th, td {
      padding: 12px 15px;
      border: 1px solid #ddd;
      text-align: center;
    }

    th {
      background-color: #4B0082;
      color: white;
    }

    td button {
      padding: 6px 12px;
      border: none;
      border-radius: 5px;
      font-size: 0.9em;
      cursor: pointer;
      margin: 0 3px;
    }

    .update-btn {
      background-color: #ffc107;
    }

    .delete-btn {
      background-color: #dc3545;
      color: white;
    }

    .back-link {
      text-align: center;
      margin-top: 30px;
    }

    .back-link a {
      text-decoration: none;
      color: #4B0082;
      font-weight: 500;
    }
  </style>
</head>
<body>
  <h1>Manage Trainee Accounts</h1>

  <div class="actions">
    <a href="create-trainee.php">
      <button><i class="fas fa-user-plus"></i> Create New Trainee</button>
    </a>
  </div>

  <table>
    <thead>
      <tr>
        <th>No</th>
        <th>ID</th>
        <th>Username</th>
        <th>Name</th>
        <th>Email</th>
        <th>Contact</th>
        <th>DOB</th>
        <th>Gender</th>
        <th>Progress</th>
        <th>Enroll Date</th>
        <th>Role</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php $no = 1; foreach ($traineeList as $trainee): ?>
        <tr>
          <td><?= $no++ ?></td>
          <td><?= $trainee['trainee_id'] ?></td>
          <td><?= htmlspecialchars($trainee['username']) ?></td>
          <td><?= htmlspecialchars($trainee['trainee_name']) ?></td>
          <td><?= htmlspecialchars($trainee['email']) ?></td>
          <td><?= htmlspecialchars($trainee['contact_number']) ?></td>
          <td><?= htmlspecialchars($trainee['date_of_birth']) ?></td>
          <td><?= htmlspecialchars($trainee['gender']) ?></td>
          <td><?= htmlspecialchars($trainee['progress_level']) ?></td>
          <td><?= htmlspecialchars($trainee['enrollment_date']) ?></td>
          <td><?= htmlspecialchars($trainee['role']) ?></td>
          <td>
            <a href="update-trainee.php?id=<?= $trainee['trainee_id'] ?>">
              <button class="update-btn">Update</button>
            </a>
            <a href="delete-trainee.php?id=<?= $trainee['trainee_id'] ?>" onclick="return confirm('Are you sure to delete this trainee?');">
              <button class="delete-btn">Delete</button>
            </a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <div class="back-link">
    <a href="admin-dashboard.php"><i class="fas fa-arrow-left"></i> Back to Admin Dashboard</a>
  </div>
</body>
</html>
