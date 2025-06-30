<?php
session_start();

// OPTIONAL: Redirect if not staff
// if (!isset($_SESSION['staff'])) {
//     header("Location: staff-login.php");
//     exit();
// }

$conn = new mysqli("localhost", "root", "", "unispa");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$selectedDate = $_GET['date'] ?? null;
$bookings = [];

if ($selectedDate) {
    $stmt = $conn->prepare("SELECT * FROM booking WHERE bookingDate = ?");
    $stmt->bind_param("s", $selectedDate);
    $stmt->execute();
    $result = $stmt->get_result();
    $bookings = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Booking List for <?= htmlspecialchars($selectedDate) ?></title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #d3cce3, #e9e4f0);
      margin: 0;
      padding: 30px;
    }

    .container {
      max-width: 1000px;
      margin: auto;
      background: white;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    h1 {
      text-align: center;
      color: #4B0082;
      margin-bottom: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    th, td {
      padding: 10px;
      border: 1px solid #ccc;
      text-align: left;
    }

    th {
      background-color: #eee;
      color: #333;
    }

    .no-bookings {
      text-align: center;
      color: #777;
      margin-top: 30px;
    }

    a.back-btn {
      display: inline-block;
      margin-top: 20px;
      padding: 10px 20px;
      background-color: #4B0082;
      color: white;
      border-radius: 8px;
      text-decoration: none;
      font-weight: bold;
    }

    a.back-btn:hover {
      background-color: #6a0dad;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1><i class="fas fa-calendar-check"></i> Bookings for <?= htmlspecialchars($selectedDate) ?></h1>

    <?php if (count($bookings) > 0): ?>
      <table>
        <thead>
          <tr>
            <th>Booking ID</th>
            <th>Customer</th>
            <th>Service</th>
            <th>Time</th>
            <th>Payment Status</th>
            <th>Extra Request</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($bookings as $book): ?>
            <tr>
              <td><?= htmlspecialchars($book['bookID']) ?></td>
              <td><?= htmlspecialchars($book['customerName']) ?></td>
              <td><?= htmlspecialchars($book['serviceName']) ?></td>
              <td><?= substr($book['bookingTime'], 0, 5) ?></td>
              <td><?= htmlspecialchars($book['paymentStatus']) ?></td>
              <td><?= htmlspecialchars($book['extraRequest']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p class="no-bookings">No bookings found for this date.</p>
    <?php endif; ?>

    <a href="staff-viewBooking.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Calendar</a>
  </div>
</body>
</html>
