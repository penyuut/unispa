<?php
session_start();
$conn = new mysqli("localhost", "root", "", "unispa");

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Fetch barber services (Barber or Men)
$sql = "SELECT * FROM services WHERE category = 'Barber & Hair Spa (Men)'";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Barber & Hair Spa | UniSpa</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #a1c4fd, #c2e9fb);
      margin: 0;
      padding: 40px;
    }

    .container {
      max-width: 900px;
      margin: auto;
      background: white;
      border-radius: 15px;
      padding: 30px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    h1 {
      text-align: center;
      color: #0077b6;
      margin-bottom: 30px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      text-align: left;
    }

    th, td {
      padding: 12px 15px;
      border-bottom: 1px solid #ddd;
    }

    th {
      background-color: #0077b6;
      color: white;
    }

 .book-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  background-color: #0077b6;
  color: white;
  padding: 6px 10px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-size: 0.85em;
  text-decoration: none;
  white-space: nowrap;
  transition: background-color 0.3s ease;
}

    

    .book-btn:hover {
      background-color: #0096c7;
    }

    .promo {
      color: green;
      font-weight: bold;
    }

.back-link {
      margin-top: 20px;
      text-align: center;
    }

    .back-link a {
      color: #4B0082;
      text-decoration: none;
      font-weight: bold;
    }

    .back-link a:hover {
      text-decoration: underline;
    }


  </style>
</head>
<body>
  <div class="container">
    <h1><i class="fas fa-male"></i> Barber & Hair Spa (Men)</h1>
    <table>
      <thead>
        <tr>
          <th>Service Name</th>
          <th>Description</th>
          <th>Duration (mins)</th>
          <th>Price (RM)</th>
          <th>Promo</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['service_name']) ?></td>
              <td><?= htmlspecialchars($row['description']) ?></td>
              <td><?= $row['duration'] ?></td>
              <td><?= number_format($row['price'], 2) ?></td>
              <td class="promo"><?= $row['promo_details'] ?: '-' ?></td>
              <td>
                <a href="make-booking.php?serviceID=<?= $row['serviceID'] ?>" class="book-btn">
                  <i class="fas fa-calendar-plus"></i> Book Now
                </a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="6">No barber or men services available.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>


    <div class="back-link">
      <a href="customer-booking.php">&larr; Back</a>
    </div>


  </div>
</body>
</html>
