<?php
session_start();
if (!isset($_SESSION['trainer'])) {
    header("Location: trainer-login.php");
    exit();
}

$trainer_id = $_SESSION['trainer']['id'];

$conn = new mysqli("localhost", "root", "", "unispa");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT * FROM workshop");

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Workshops | UniSpa</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f5f3ff;
      padding: 40px;
    }
    h1 {
      color: #4B0082;
      text-align: center;
      margin-bottom: 30px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      background: white;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    th, td {
      padding: 12px;
      border-bottom: 1px solid #ccc;
      text-align: left;
    }
    th {
      background-color: #e8ddf7;
    }
    a.button {
      text-decoration: none;
      background: #4B0082;
      color: white;
      padding: 8px 15px;
      border-radius: 6px;
      font-size: 0.9em;
    }
    a.button:hover {
      background: #6a0dad;
    }
    .top-button {
      display: flex;
      justify-content: flex-end;
      margin-bottom: 20px;
    }
  </style>
</head>
<body>

<h1><i class="fas fa-calendar-check"></i> Manage My Workshops</h1>

<div class="top-button">
  <a href="trainer-create-workshop.php" class="button"><i class="fas fa-plus"></i> Create Workshop</a>
</div>

<table>
  <thead>
    <tr>
      <th>Title</th>
      <th>Description</th>
      <th>Date</th>
      <th>Location</th>
      <th>Capacity</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?= htmlspecialchars($row['title']) ?></td>
      <td><?= htmlspecialchars($row['description']) ?></td>
      <td><?= $row['date'] ?></td>
      <td><?= htmlspecialchars($row['location']) ?></td>
      <td><?= $row['capacity'] ?></td>
      <td>
        <a class="button" href="trainer-edit-workshop.php?id=<?= $row['workshop_id'] ?>"><i class="fas fa-edit"></i> Edit</a>
        <a class="button" href="trainer-delete-workshop.php?id=<?= $row['workshop_id'] ?>" onclick="return confirm('Are you sure you want to delete this workshop?');"><i class="fas fa-trash-alt"></i> Delete</a>
      </td>
    </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<div style="margin-top: 20px; text-align: left;">
  <a href="trainer-dashboard.php" class="button" style="background: #888; margin-top: 20px;">
    <i class="fas fa-arrow-left"></i> Back to Dashboard
  </a>
</div>


</body>
</html>
