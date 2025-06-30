<?php
session_start();

// Check if customer is logged in
if (!isset($_SESSION['customer'])) {
    header("Location: customer-signin.php");
    exit();
}

$customer = $_SESSION['customer'];
$username = $customer['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Customer Dashboard | UniSpa</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;600&display=swap" rel="stylesheet"/>
  <style>
    body {
      margin: 0;
      font-family: 'Source Sans Pro', sans-serif;
      background: linear-gradient(to right, #e0c3fc, #8ec5fc);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .dashboard-container {
      background: rgba(255, 255, 255, 0.95);
      border-radius: 20px;
      padding: 40px 30px;
      width: 380px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
      text-align: center;
    }

    .dashboard-container h1 {
      color: #4B0082;
      margin-bottom: 10px;
    }

    .dashboard-container p {
      color: #555;
      margin-bottom: 30px;
      font-size: 1em;
    }

    .icon-top {
      font-size: 3em;
      color: #4B0082;
      margin-bottom: 10px;
    }

    .dashboard-btn {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      background: #4B0082;
      color: white;
      padding: 12px;
      border: none;
      border-radius: 8px;
      font-size: 1em;
      font-weight: 600;
      margin: 10px 0;
      cursor: pointer;
      width: 100%;
      text-decoration: none;
      transition: background 0.3s ease;
    }

    .dashboard-btn:hover {
      background-color: #6a0dad;
    }

    .dashboard-btn i {
      font-size: 1.1em;
    }

.back-link {
      margin-top: 20px;
      text-align: center;
    }

    .back-link a {
      color: #white;
      text-decoration: none;
      font-weight: bold;
    }

    .back-link a:hover {
      text-decoration: underline;
    }


  </style>
</head>
<body>
  <div class="dashboard-container">
    <div class="icon-top">
      <i class="fas fa-spa"></i>
    </div>
    <h1>Welcome, <?= htmlspecialchars($username) ?>!</h1>
    <p>What would you like to do today?</p>

    <a href="customer-viewProfile.php" class="dashboard-btn"><i class="fas fa-user-circle"></i> View Profile</a>
    <a href="customer-updateProfile.php" class="dashboard-btn"><i class="fas fa-user-edit"></i> Update Profile</a>
    <a href="customer-booking.php" class="dashboard-btn"><i class="fas fa-calendar-check"></i> Make Booking</a>
    <a href="view-booking.php" class="dashboard-btn"><i class="fas fa-calendar-alt"></i> View Booking</a>

 <div class="back-link">
      <a href="index.html">&larr; Back To Home</a>

    </div>
  </div>

</body>
</html>
