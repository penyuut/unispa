<?php
session_start();
if (!isset($_SESSION['trainer'])) {
    header("Location: trainer-login.php");
    exit();
}
$trainer_name = $_SESSION['trainer']['name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Trainer Dashboard | UniSpa</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      margin: 0;
      font-family: 'Source Sans Pro', sans-serif;
      background: linear-gradient(to right, #f8f8ff, #e9e4f0);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .dashboard-container {
      background: #fff;
      padding: 40px 30px;
      border-radius: 20px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
      width: 400px;
      text-align: center;
    }

    .dashboard-container .icon {
      font-size: 3.5em;
      color: #4B0082;
      margin-bottom: 10px;
    }

    .dashboard-container h1 {
      color: #4B0082;
      margin-bottom: 10px;
    }

    .dashboard-container p {
      color: #555;
      margin-bottom: 25px;
    }

    .btn {
      display: block;
      background: #4B0082;
      color: white;
      padding: 12px;
      margin-bottom: 15px;
      border: none;
      border-radius: 8px;
      font-size: 1em;
      font-weight: 600;
      text-decoration: none;
      width: 100%;
      transition: background 0.3s ease;
    }

    .btn i {
      margin-right: 8px;
    }

    .btn:hover {
      background-color: #6a0dad;
    }
  </style>
</head>
<body>
  <div class="dashboard-container">
    <div class="icon">
      <i class="fas fa-chalkboard-teacher"></i>
    </div>
    <h1>Welcome, <?= htmlspecialchars($trainer_name) ?>!</h1>
    <p>Choose your action:</p>

    <a href="trainer-manage-workshop.php" class="btn">
      <i class="fas fa-tools"></i> Manage Workshop
    </a>

    <a href="trainer-view-trainee.php" class="btn">
  <i class="fas fa-search"></i> Search & Choose Trainee
</a>

<a href="trainer-login.php" class="back-link">&larr; Back</a>
 

  </div>
</body>
</html>
