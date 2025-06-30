<?php
session_start();
$conn = new mysqli("localhost", "root", "", "unispa");

// Redirect to login if customer is not logged in
if (!isset($_SESSION['customer'])) {
    header("Location: customer-signin.php");
    exit();
}

$customerName = $_SESSION['customer']['username']; // Assumes 'username' is used as customerName

// Fetch bookings for this customer
$stmt = $conn->prepare("SELECT * FROM booking WHERE customerName = ?");
$stmt->bind_param("s", $customerName);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>My Bookings | UniSpa</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #d3cce3, #e9e4f0);
      padding: 40px;
    }
    .container {
      max-width: 900px;
      margin: auto;
      background: white;
      padding: 30px;
      border-radius: 16px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    h1 {
      text-align: center;
      color: #4B0082;
      margin-bottom: 20px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }
    th, td {
      border: 1px solid #ccc;
      padding: 12px 10px;
      text-align: center;
    }
    th {
      background-color: #4B0082;
      color: white;
    }
    tr:nth-child(even) {
      background-color: #f9f9f9;
    }
    .btn-back {
      margin-top: 20px;
      display: inline-block;
      background-color: #4B0082;
      color: white;
      padding: 10px 20px;
      text-decoration: none;
      border-radius: 8px;
      font-weight: bold;
    }
    .btn-back:hover {
      background-color: #6a0dad;
    }
    .proof-img {
      width: 80px;
      height: auto;
      border-radius: 5px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1><i class="fas fa-calendar-alt"></i> My Bookings</h1>

    <?php if ($result->num_rows > 0): ?>
    <table>
      <thead>
        <tr>
          <th>Booking ID</th>
          <th>Service Name</th>
          <th>Date</th>
          <th>Time</th>
          <th>Payment Status</th>
          <th>Payment Proof</th>
          <th>Extra Request</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($row['bookID']) ?></td>
          <td><?= htmlspecialchars($row['serviceName']) ?></td>
          <td><?= htmlspecialchars($row['bookingDate']) ?></td>
          <td><?= htmlspecialchars($row['bookingTime']) ?></td>
          <td><?= htmlspecialchars($row['paymentStatus']) ?></td>
          <td>
            <?php if (!empty($row['paymentProof'])): ?>
              <img src="<?= htmlspecialchars($row['paymentProof']) ?>" class="proof-img" alt="Proof">
            <?php else: ?>
              N/A
            <?php endif; ?>
          </td>
          <td><?= htmlspecialchars($row['extraRequest']) ?></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
    <?php else: ?>
      <p style="text-align: center;">You have no bookings yet.</p>
    <?php endif; ?>

    <div style="text-align: center;">
      <a class="btn-back" href="customer-dashboard.php"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    </div>
  </div>
</body>
</html>
