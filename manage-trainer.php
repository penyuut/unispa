<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin-login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "unispa");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$trainerList = [];
$result = $conn->query("SELECT * FROM trainer");
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $trainerList[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Manage Trainers | UniSpa</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: linear-gradient(to right, #d3cce3, #e9e4f0);
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
      font-weight: bold;
      cursor: pointer;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background: white;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    th, td {
      padding: 12px;
      border: 1px solid #ddd;
      text-align: center;
    }

    th {
      background-color: #4B0082;
      color: white;
    }

    .update-btn {
      background-color: #ffc107;
      padding: 6px 12px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    .delete-btn {
      background-color: #dc3545;
      color: white;
      padding: 6px 12px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
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

  <h1><i class="fas fa-chalkboard-teacher"></i> Manage Trainer Accounts</h1>

  <div class="actions">
    <a href="create-trainer.php">
      <button><i class="fas fa-user-plus"></i> Create New Trainer</button>
    </a>
  </div>

  <table>
    <thead>
  <tr>
    <th>No</th>
    <th>Trainer ID</th>
    <th>Username</th>
    <th>Name</th>
    <th>Email</th>
    <th>Contact</th>
    <th>DOB</th>
    <th>Gender</th>
    <th>Speciality</th>
    <th>Qualification</th>
    <th>Role</th>
    <th>Action</th>
  </tr>
</thead>
<tbody>
  <?php $no = 1; foreach ($trainerList as $trainer): ?>
    <tr>
      <td><?= $no++ ?></td>
      <td><?= htmlspecialchars($trainer['trainer_id']) ?></td>
      <td><?= htmlspecialchars($trainer['username']) ?></td>
      <td><?= htmlspecialchars($trainer['trainer_name']) ?></td>
      <td><?= htmlspecialchars($trainer['email']) ?></td>
      <td><?= htmlspecialchars($trainer['contact_number']) ?></td>
      <td><?= htmlspecialchars($trainer['date_of_birth']) ?></td>
      <td><?= htmlspecialchars($trainer['gender']) ?></td>
      <td><?= htmlspecialchars($trainer['speciality']) ?></td>
      <td><?= htmlspecialchars($trainer['qualification']) ?></td>
      <td><?= htmlspecialchars($trainer['role']) ?></td>
      <td>
        <a href="update-trainer.php?id=<?= $trainer['trainer_id'] ?>">
          <button class="update-btn">Update</button>
        </a>
        <a href="delete-trainer.php?id=<?= $trainer['trainer_id'] ?>" onclick="return confirm('Are you sure you want to delete this trainer?');">
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
