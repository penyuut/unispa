<?php
session_start();
$conn = new mysqli("localhost", "root", "", "unispa");

// Redirect to login if not logged in
if (!isset($_SESSION['customer'])) {
    header("Location: customer-signin.php");
    exit();
}

$custID = $_SESSION['customer']['custID'];
$result = $conn->query("SELECT * FROM customers WHERE custID = '$custID'");
$customer = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Customer Profile | UniSpa</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #d3cce3, #e9e4f0);
      margin: 0;
      padding: 30px;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }

    .profile-container {
      background: #fff;
      padding: 40px;
      border-radius: 16px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
      max-width: 400px;
      width: 100%;
      text-align: center;
    }

    h2 {
      color: #4B0082;
      margin-bottom: 20px;
    }

    .profile-picture {
      margin-bottom: 20px;
    }

    .profile-picture img {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      border: 4px solid #4B0082;
      object-fit: cover;
    }

    .profile-info p {
      margin: 10px 0;
      font-size: 1em;
      color: #333;
    }

    .profile-info strong {
      color: #4B0082;
    }

    .btn-back {
      display: inline-block;
      margin-top: 25px;
      padding: 10px 20px;
      background-color: #4B0082;
      color: white;
      text-decoration: none;
      border-radius: 8px;
      transition: background 0.3s ease;
    }

    .btn-back:hover {
      background-color: #6a0dad;
    }
  </style>
</head>
<body>
  <div class="profile-container">
    <h2><i class="fas fa-user-circle"></i> Customer Profile</h2>

    <div class="profile-picture">
      <img src="<?= htmlspecialchars($customer['profile_picture'] ?? 'uploads/default-avatar.png') ?>" alt="Profile Picture">
    </div>

    <div class="profile-info">
      <p><strong>Username:</strong> <?= htmlspecialchars($customer['username']) ?></p>
      <p><strong>Email:</strong> <?= htmlspecialchars($customer['email']) ?></p>
      <p><strong>Contact:</strong> <?= htmlspecialchars($customer['contact_number']) ?></p>
      <p><strong>Date of Birth:</strong> <?= htmlspecialchars($customer['dob']) ?></p>
      <p><strong>Gender:</strong> <?= htmlspecialchars($customer['gender']) ?></p>
      <p><strong>Customer Type:</strong> <?= htmlspecialchars($customer['custType']) ?></p>
    </div>

    <a class="btn-back" href="customer-dashboard.php"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
  </div>
</body>
</html>
