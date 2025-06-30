<?php
session_start();
if (!isset($_SESSION['staff'])) {
    header("Location: staff-login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Staff Dashboard | UniSpa</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      margin: 0;
      font-family: 'Source Sans Pro', sans-serif;
      background: linear-gradient(to right, #d3cce3, #e9e4f0);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .dashboard-container {
      background: #fff;
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
      width: 90%;
      max-width: 600px;
      text-align: center;
    }

    h1 {
      margin-bottom: 30px;
      color: #4B0082;
    }

    .button-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
    }

    .dashboard-button {
      background-color: #4B0082;
      color: white;
      border: none;
      border-radius: 10px;
      padding: 20px;
      font-size: 1em;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.3s ease;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .dashboard-button i {
      font-size: 1.8em;
      margin-bottom: 10px;
    }

    .dashboard-button:hover {
      background-color: #6a0dad;
    }

    @media (max-width: 500px) {
      .button-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>
  <div class="dashboard-container">
    <h1><i class="fas fa-users-cog"></i> Staff Dashboard</h1>
    <div class="button-grid">
      <a href="staff-booking.php" class="dashboard-button">
        <i class="fas fa-calendar-check"></i>
        Manage Booking
      </a>
      <a href="staff-services.php" class="dashboard-button">
        <i class="fas fa-concierge-bell"></i>
        Manage Services
      </a>
      <a href="staff-viewBooking.php" class="dashboard-button">
        <i class="fas fa-users"></i>
        View Booking Customers
      </a>
      <a href="staff-viewCustomer.php" class="dashboard-button">
        <i class="fas fa-id-card"></i>
        View Customer Profile
      </a>
    </div>
  </div>
<div style="margin-top: 30px;">
  <a href="staff-logout.php" class="dashboard-button" style="background: #a10000;">
    <i class="fas fa-sign-out-alt"></i>
    Logout
  </a>
</div>

</body>
</html>
