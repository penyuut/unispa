<?php
session_start();

// Redirect to login if not logged in as admin
if (!isset($_SESSION['admin'])) {
    header("Location: admin-login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard | UniSpa</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      margin: 0;
      font-family: 'Source Sans Pro', sans-serif;
      background: linear-gradient(to right, #d3cce3, #e9e4f0);
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 60px 20px;
    }

    h1 {
      color: #4B0082;
      margin-bottom: 40px;
      text-align: center;
    }

    .role-buttons {
      display: flex;
      flex-direction: column;
      gap: 20px;
      width: 100%;
      max-width: 300px;
    }

    .role-buttons a {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      padding: 15px;
      background-color: #4B0082;
      color: #fff;
      text-decoration: none;
      border-radius: 10px;
      font-weight: 600;
      font-size: 1.1em;
      transition: background 0.3s ease;
    }

    .role-buttons a:hover {
      background-color: #6a0dad;
    }

    .back-link {
      margin-top: 30px;
    }

    .back-link a {
      text-decoration: none;
      color: #4B0082;
      font-size: 0.95em;
      border-bottom: 1px dotted #4B0082;
    }

    .back-link a:hover {
      border-bottom: 1px solid transparent;
    }
  </style>
</head>
<body>

  <h1><i class="fas fa-user-shield"></i> Admin Dashboard</h1>

  <div class="role-buttons">
    <a href="manage-staff.php"><i class="fas fa-users-cog"></i> Manage Staff</a>
    <a href="manage-trainer.php"><i class="fas fa-chalkboard-teacher"></i> Manage Trainers</a>
    <a href="manage-trainee.php"><i class="fas fa-user-graduate"></i> Manage Trainees</a>
  </div>

  <div class="back-link">
    <a href="index.html"><i class="fas fa-arrow-left"></i> Logout</a>
  </div>

</body>
</html>
