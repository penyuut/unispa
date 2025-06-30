<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['customer'])) {
  header("Location: customer-signin.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Make Booking | UniSpa</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;600&display=swap" rel="stylesheet"/>
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

    .booking-container {
      background: rgba(255, 255, 255, 0.95);
      border-radius: 20px;
      padding: 40px 30px;
      width: 500px;
      height: 600px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
      text-align: center;
      display: flex;
      flex-direction: column;
      justify-content: flex-start;
    }

    h1 {
      color: #4B0082;
      margin-bottom: 10px;
    }

    p {
      color: #555;
      margin-bottom: 25px;
    }

    .service-button {
      display: flex;
      align-items: center;
      justify-content: flex-start;
      background-color: #4B0082;
      color: white;
      border: none;
      border-radius: 10px;
      padding: 15px 20px;
      margin: 8px 0;
      width: 90%;
      height: 55px;
      font-size: 1em;
      font-weight: 600;
      cursor: pointer;
      text-decoration: none;
      transition: background 0.3s ease;
    }

    .service-button:hover {
      background-color: #6a0dad;
    }

    .service-button i {
      margin-right: 15px;
      font-size: 1.2em;
      min-width: 20px;
      text-align: center;
    }
  </style>
</head>
<body>
  <div class="booking-container">
    <h1><i class="fas fa-calendar-check"></i> Book a Service</h1>
    <p>Select the type of service you want</p>

    <a href="booking-facial.php" class="service-button">
      <i class="fas fa-spa"></i> Facial Treatments
    </a>

    <a href="booking-massage.php" class="service-button">
      <i class="fas fa-hand-holding-heart"></i> Massage Therapy Session
    </a>

    <a href="booking-nail.php" class="service-button">
      <i class="fas fa-hand-sparkles"></i> Nail & Foot Care
    </a>

    <a href="booking-makeup.php" class="service-button">
      <i class="fas fa-paint-brush"></i> Makeup Session
    </a>

    <a href="booking-muslimah.php" class="service-button">
      <i class="fas fa-female"></i> Muslimah Hair Cut & Spa (Women)
    </a>

    <a href="booking-barber.php" class="service-button">
      <i class="fas fa-male"></i> Barber & Hair Spa (Men)
    </a>

<div class="back-link">
      <a href="customer-dashboard.php">&larr; Back</a>
    </div>
  </div>

</body>
</html>
